# Guia de Otimização de Tokens — MCP deepseek-coder

> Referência prática para reduzir o custo de tokens do Claude ao usar o MCP `deepseek-coder` para geração de código em massa.

---

## 1. O Problema

O Claude mantém **todo o histórico da conversa** em contexto. A cada chamada a uma ferramenta MCP, esse contexto inteiro é relido (`cache_read`). Conforme a conversa cresce — com cada arquivo gerado sendo adicionado ao histórico —, o custo de cache_read se acumula em cada nova chamada.

**Exemplo real desta sessão:**

| Métrica | Valor |
|---|---|
| Arquivos gerados | 30 |
| Chamadas MCP | ~25 |
| cache_read tokens | 4,7M |
| cache_write tokens | 104,7k |
| Custo total | **$2,41** |

### Tabela de preços do Claude Sonnet 4.6 (aproximado)

| Tipo | Preço por MTok |
|---|---|
| Input | $3,00 |
| Output | $15,00 |
| cache_write | $3,75 |
| cache_read | **$0,30** |

O `cache_read` é ~10× mais barato que input normal — mas com 4,7M tokens, ainda representa o maior custo da sessão.

### Como o contexto cresce por chamada

```
Chamada 1:  [██        ]  5k tokens  → $0,001
Chamada 5:  [████      ] 20k tokens  → $0,006
Chamada 10: [██████    ] 50k tokens  → $0,015
Chamada 15: [████████  ] 90k tokens  → $0,027
Chamada 20: [█████████ ]130k tokens  → $0,039
Chamada 25: [██████████]190k tokens  → $0,057

↑ Cada barra = custo de cache_read daquela chamada individual.
  O custo por chamada aumenta a cada arquivo adicionado ao contexto.
```

---

## 2. Como o Cache do Claude Funciona

| Conceito | Descrição | Custo relativo |
|---|---|---|
| `cache_write` | Primeira vez que um bloco entra no cache | ~1,25× input |
| `cache_read` | Leituras subsequentes do mesmo bloco | ~0,1× input |
| Contexto da conversa | Relido inteiro a cada turno/MCP call | Cresce por turno |
| `CLAUDE.md` | Escrito uma vez, relido barato em todo turno | cache_read barato |

**O ponto-chave:** o conteúdo do `CLAUDE.md` é escrito uma vez (`cache_write`) e depois relido em cache em todos os turnos subsequentes. Quanto mais contexto de projeto estiver lá, menos precisa ser reinjetado manualmente a cada instrução.

---

## 3. Estratégias de Uso (Client-side)

### 3.1 `/compact` antes de tasks grandes

O comando `/compact` resume o histórico da conversa, substituindo mensagens longas por um resumo compacto.

- **Quando usar:** antes de qualquer task com 5+ arquivos a gerar
- **Impacto:** conversa com 80k tokens → resumida para ~8-12k
- **Resultado:** as primeiras chamadas MCP partem de um contexto muito menor

```
Antes do /compact:  contexto = 80k tokens
Após o /compact:    contexto = 10k tokens

Economia na chamada seguinte: 70k tokens × $0,30/MTok = $0,021 só na primeira call
```

### 3.2 `/clear` para tasks independentes

Se a nova task **não depende** do histórico anterior, iniciar com contexto limpo.

- cache_read = 0 nas primeiras chamadas
- Ideal para implementações do zero (novo módulo, novo projeto)

### 3.3 Agrupar arquivos por camada ⚡ (maior impacto)

**O problema:** uma chamada por arquivo faz o contexto crescer a cada passo. Na chamada 20, o Claude já está relendo ~130k tokens só de contexto.

**A solução:** agrupar arquivos relacionados numa única instrução ao DeepSeek.

#### Abordagem atual (ruim)
```
Call 1:  Badge.vue          (contexto: 5k)
Call 2:  CarPlaceholder.vue (contexto: 8k)
Call 3:  CarCard.vue        (contexto: 12k)
Call 4:  Navbar.vue         (contexto: 18k)
Call 5:  AppLayout.vue      (contexto: 24k)
...
Call 25: Compare.vue        (contexto: 190k)
```

#### Abordagem otimizada (boa)
```
Call 1: Migrations (2-3 arquivos juntos)          (contexto: 5k)
Call 2: Models + Repository interfaces            (contexto: 10k)
Call 3: Repositories + Service                    (contexto: 16k)
Call 4: Actions (3-4 arquivos juntos)             (contexto: 23k)
Call 5: Vue components (Badge, CarCard, Navbar…)  (contexto: 30k)
Call 6: Vue pages grupo 1 (Home, Search, Detail)  (contexto: 50k)
Call 7: Vue pages grupo 2 (Seller, NewListing…)   (contexto: 70k)
Call 8: Auth pages                                (contexto: 85k)
```

#### Estimativa de economia

| Abordagem | Calls | Cache read (est.) | Custo (est.) |
|---|---|---|---|
| 1 arquivo/call | 25 | 4,7M tokens | $2,41 |
| Agrupado por camada | 8 | 1,2M tokens | $0,65 |
| **Economia** | **-17 calls** | **-3,5M tokens** | **-$1,76 (~73%)** |

#### Como escrever instruções multi-arquivo para o DeepSeek

Peça ao DeepSeek para separar os arquivos com um delimitador claro:

```
Gere os seguintes arquivos separados por "=== FILE: <path> ===":

=== FILE: app/Modules/Car/Models/Car.php ===
[instruções para Car.php]

=== FILE: app/Modules/Car/Models/Seller.php ===
[instruções para Seller.php]
```

O MCP pode então fazer parse por delimitador e escrever cada arquivo separadamente.

### 3.4 Evitar `read_file` Redundante

Cada chamada `read_file` adiciona o conteúdo do arquivo **permanentemente** ao contexto da conversa.

- **Regra:** ler cada arquivo de referência no máximo uma vez por sessão
- **Se o conteúdo já foi lido antes:** passar diretamente no campo `context` do `generate_and_write`

```js
// Ruim: lê o arquivo novamente (adiciona ao contexto pela 2ª vez)
await read_file({ file_path: 'app/Modules/Car/Services/CarService.php' })
await generate_and_write({ file_path: '...', instructions: '...' })

// Bom: reutiliza o conteúdo já no contexto da conversa
await generate_and_write({
  file_path: '...',
  instructions: '...',
  context: '<conteúdo que já foi mostrado anteriormente nesta sessão>'
})
```

### 3.5 `CLAUDE.md` como Cache Permanente

Tudo no `CLAUDE.md` é processado via `cache_write` uma vez e relido barato em todos os turnos. Use-o para:

- Estrutura de diretórios do projeto
- Convenções de nomenclatura
- Padrões de código com exemplos (`Action`, `Service`, `Repository`)
- Instruções de quais ferramentas usar (MCP deepseek-coder)

Quanto mais rico o `CLAUDE.md`, menos contexto precisa ser reinjetado manualmente em cada instrução, reduzindo o tamanho do prompt enviado ao DeepSeek.

### 3.6 Sempre usar `generate_and_write` em vez de `generate_code` + `write_file`

```
generate_code → 1 call ao contexto
write_file    → 1 call ao contexto
─────────────────────────────────
Total: 2 cache_reads

generate_and_write → 1 call ao contexto
─────────────────────────────────
Total: 1 cache_read
```

Nunca separar em dois passos quando `generate_and_write` existe.

---

## 4. Tools Disponíveis no Servidor MCP

Todas as otimizações abaixo já estão implementadas no servidor `deepseek-coder`.

### Tabela de tools e quando usar cada uma

| Situação | Tool |
|---|---|
| 2+ arquivos relacionados (mesma camada) | `generate_and_write_multiple` |
| 1 arquivo, criação ou reescrita total | `generate_and_write` |
| Mudança incremental em arquivo existente | `patch_file` |
| Inspecionar código antes de salvar | `generate_code` → `write_file` |
| **Nunca** | `generate_code` + `write_file` como passos separados para o mesmo arquivo |

---

### 4.1 `init_project_context`

Carrega arquivos-chave do projeto **uma vez** no início da sessão. O servidor armazena o conteúdo em memória — chamadas subsequentes ao `generate_and_write` e `generate_and_write_multiple` recebem esse contexto automaticamente, sem que precise ser reinjetado em cada instrução.

**Uso — sempre chamar no início de uma sessão de geração:**
```
init_project_context([
  "CLAUDE.md",
  "app/Modules/Car/Services/CarService.php",
  "app/Modules/Car/Repositories/CarRepository.php"
])
```

**Impacto:** elimina a necessidade de `read_file` para arquivos de referência e reduz o tamanho do campo `instructions` em cada chamada.

---

### 4.2 `register_pattern` / `list_patterns`

Armazena templates reutilizáveis no servidor por nome. Evita repetir a mesma descrição de padrão em cada instrução — reduz o tamanho das instruções em 50-70% para padrões repetitivos.

**Registrar padrões no início da sessão:**
```
register_pattern("laravel-invokable-action", "
  Invokable controller em namespace App\Modules\{Module}\Interfaces\Http\Action.
  Constructor injeta {Module}Service via readonly property.
  __invoke(Request $request): Response|JsonResponse
  Verifica wantsJson() para content negotiation.
  Delega toda lógica ao service — nunca toca o repository diretamente.
  Segue PSR-12.
")

register_pattern("laravel-repository", "
  Implementa {Module}RepositoryInterface.
  Todas as queries via Eloquent — sem SQL raw.
  Retorna tipos declarados: Model, Collection, LengthAwarePaginator.
  PSR-12.
")
```

**Uso em instruções (muito mais curto):**
```
// Sem pattern: instrução com 200+ tokens descrevendo o padrão
// Com pattern: instrução com ~30 tokens
"Crie ListBrandAction usando o pattern 'laravel-invokable-action'.
 Service: BrandService. Método: paginate(15). Renderiza 'Brand/Index'."
```

**Listar padrões registrados:**
```
list_patterns()
```

---

### 4.3 `generate_and_write_multiple`

Gera e escreve N arquivos em **uma única chamada à API do DeepSeek**. É a principal ferramenta para reduzir o número de round-trips ao Claude.

**Uso — agrupar arquivos por camada arquitetural:**
```js
generate_and_write_multiple({
  shared_context: "Projeto Laravel 12 Package by Feature. Módulo Brand.",
  files: [
    {
      file_path: "app/Modules/Brand/Models/Brand.php",
      instructions: "Crie o Eloquent model Brand com fillable ['name','slug']. Namespace App\\Modules\\Brand\\Models."
    },
    {
      file_path: "app/Modules/Brand/Repositories/Contracts/BrandRepositoryInterface.php",
      instructions: "Interface com métodos: getAll(): Collection, getById(int $id): Brand, create(array $data): Brand."
    },
    {
      file_path: "app/Modules/Brand/Repositories/BrandRepository.php",
      instructions: "Implementa BrandRepositoryInterface usando o pattern 'laravel-repository'."
    }
  ]
})
```

**Regra prática:** nunca gerar menos de 2 arquivos por chamada quando os arquivos pertencem à mesma camada.

---

### 4.4 `patch_file`

Para **mudanças incrementais** em arquivos existentes. Gera apenas o diff em vez de reescrever o arquivo inteiro — economiza output tokens significativos em arquivos grandes.

**Quando usar:** adicionar um método, corrigir um bug, ajustar uma validação.  
**Quando NÃO usar:** refatorações grandes, mudança de estrutura, reescrita de lógica central.

**Uso:**
```js
patch_file({
  file_path: "app/Modules/Car/Services/CarService.php",
  instructions: "Adicione o método getByBrand(string $brand): Collection que filtra carros pela coluna 'brand' usando o repository.",
  model: "deepseek-v4-flash"
})
```

**Exemplo de diff gerado:**
```diff
--- a/app/Modules/Car/Services/CarService.php
+++ b/app/Modules/Car/Services/CarService.php
@@ -45,4 +45,10 @@
     public function deleteCar(int $id): ?bool
     {
         return $this->repository->destroy(id: $id);
     }
+
+    public function getByBrand(string $brand): Collection
+    {
+        return $this->repository->getByBrand($brand);
+    }
 }
```

---

### 4.5 Workflow completo de uma sessão otimizada

```
1. /compact                         → limpa contexto acumulado
2. init_project_context([...])      → carrega referências uma vez
3. register_pattern("laravel-invokable-action", "...")
   register_pattern("laravel-repository", "...")
4. generate_and_write_multiple({    → migrations + models juntos
     files: [migration1, migration2, model1, model2]
   })
5. generate_and_write_multiple({    → repositories + service juntos
     files: [repoInterface, repo, service]
   })
6. generate_and_write_multiple({    → actions juntas
     files: [action1, action2, action3, action4]
   })
7. generate_and_write_multiple({    → Vue components juntos
     files: [component1, component2, component3]
   })
8. generate_and_write_multiple({    → Vue pages juntas
     files: [page1, page2, page3]
   })
```

**Resultado: 5-6 chamadas em vez de 25, com ~70% de economia no cache_read do Claude.**

---

## 5. Checklist de Boas Práticas

Antes de iniciar qualquer task com 5+ arquivos via MCP:

**Abertura da sessão**
- [ ] Rodar `/compact` se a conversa já tem histórico acumulado (> 20k tokens)
- [ ] Usar `/clear` se a task é completamente independente do histórico
- [ ] Chamar `init_project_context(["CLAUDE.md", ...arquivos-chave])` uma vez
- [ ] Registrar padrões recorrentes com `register_pattern` (checar `list_patterns` antes de re-registrar)

**Planejamento**
- [ ] Agrupar os arquivos por camada arquitetural (migrations, models, repos, services, actions, components, pages)
- [ ] Meta: ≤ 8 chamadas MCP para tasks de 20-30 arquivos
- [ ] Identificar quais arquivos são **novos** (`generate_and_write_multiple`) vs **pequenas mudanças** (`patch_file`)

**Durante a geração**
- [ ] Usar `generate_and_write_multiple` para 2+ arquivos da mesma camada
- [ ] Usar `patch_file` para adições incrementais em arquivos existentes
- [ ] Nunca usar `generate_code` + `write_file` como passos separados para o mesmo arquivo
- [ ] Não chamar `read_file` para arquivos já carregados via `init_project_context`

---

## 6. Comparativo de Sessão

Mesma task (implementação AutoVia — 30 arquivos) nas três abordagens:

| Métrica | Sem otimização | Com agrupamento | Com novas tools |
|---|---|---|---|
| Chamadas MCP | 25 | 8 | **5–6** |
| `init_project_context` | ✗ | ✗ | ✓ |
| Padrões registrados | ✗ | ✗ | ✓ |
| cache_read tokens (Claude) | 4,7M | ~1,2M | **~0,8M** |
| Custo Claude estimado | $2,41 | ~$0,65 | **~$0,45** |
| Tempo de API | ~10 min | ~4 min | **~3 min** |
| Economia total | — | -73% | **-81%** |

### O que muda em cada abordagem

**Sem otimização (sessão original):**
- 1 arquivo por chamada, sem agrupamento
- Sem `init_project_context` → `read_file` repetido para cada referência
- Sem padrões registrados → instruções longas repetidas

**Com agrupamento (sem novas tools):**
- `/compact` antes de começar → contexto inicial de ~8k em vez de ~40k
- Migrations juntas (1 call), models + repos juntos (1 call), actions juntas (1 call), components juntos (1 call), pages em 2 grupos (2 calls)

**Com novas tools (abordagem ideal):**
- `init_project_context` elimina `read_file` → contexto do projeto não infla a conversa
- `register_pattern` encurta cada instrução em ~60%
- `generate_and_write_multiple` reduz para 5-6 chamadas totais
- `patch_file` para qualquer ajuste incremental pós-geração

---

## 7. Dados Reais de Sessão — `.deepseek-usage.jsonl`

O MCP deepseek-coder grava um log de uso em `.deepseek-usage.jsonl` na raiz do projeto. Cada linha é um JSON com os tokens consumidos por chamada:

```jsonl
{"ts": "2026-04-29T01:08:15Z", "model": "deepseek-v4-flash", "cache_hit": 0, "cache_miss": 1399, "output": 5921, "cost_usd": 0.002879}
{"ts": "2026-04-29T01:27:46Z", "model": "deepseek-v4-pro",   "cache_hit": 0, "cache_miss": 1845, "output": 4599, "cost_usd": 0.002853}
```

### Campos

| Campo | Descrição |
|---|---|
| `ts` | Timestamp da chamada |
| `model` | Modelo DeepSeek utilizado |
| `cache_hit` | Tokens servidos do cache do DeepSeek |
| `cache_miss` | Tokens de input processados (prompt enviado) |
| `output` | Tokens gerados na resposta |
| `cost_usd` | Custo desta chamada em dólares |

### Totais reais da sessão de implementação AutoVia

```
Chamadas totais:   37
Input tokens:      25.923
Output tokens:     50.756
Custo total:       $0,03
Modelos:           deepseek-v4-flash (36 calls) + deepseek-v4-pro (1 call)
Chamada mais cara: $0,0029
```

### O contraste que revela o problema real

| | Claude Sonnet 4.6 | DeepSeek flash |
|---|---|---|
| Custo total | **$2,41** | **$0,03** |
| Tokens de input | 4,7M (cache_read) | 25,9k |
| Tokens de output | 39,3k | 50,7k |
| Proporção do custo | **~99%** | **~1%** |

**O DeepSeek custou 80× menos que o Claude na mesma sessão.**

Isso prova que o gargalo de custo não está na geração de código (DeepSeek), mas no **Claude relendo o contexto crescente** a cada chamada MCP. Otimizar o número de tokens do prompt enviado ao DeepSeek tem impacto mínimo. O foco deve ser sempre em **reduzir o cache_read do Claude**.

### Como usar o arquivo para análise

```bash
# Custo total da sessão
python3 -c "
import json
calls = [json.loads(l) for l in open('.deepseek-usage.jsonl')]
print(f'Calls: {len(calls)}')
print(f'Input: {sum(c[\"cache_miss\"] for c in calls):,} tokens')
print(f'Output: {sum(c[\"output\"] for c in calls):,} tokens')
print(f'Custo: \${sum(c[\"cost_usd\"] for c in calls):.4f}')
"

# Chamadas mais caras (top 5)
python3 -c "
import json
calls = sorted([json.loads(l) for l in open('.deepseek-usage.jsonl')], key=lambda c: -c['cost_usd'])
for c in calls[:5]:
    print(f\"\${c['cost_usd']:.4f}  {c['model']}  in={c['cache_miss']}  out={c['output']}\")
"
```

### Sugestão de melhoria no MCP: alerta de custo acumulado

O servidor poderia emitir um aviso quando o custo acumulado da sessão ultrapassar um threshold:

```json
{
  "result": "Written 48 lines to /workspace/...",
  "tokens": { "prompt": 1399, "completion": 5921, "cost_usd": 0.0029 },
  "session_total_usd": 0.018,
  "warning": "Custo acumulado próximo de $0.02 — considere agrupar as próximas chamadas."
}
```

---

## Referências

- [Claude Token Pricing](https://www.anthropic.com/pricing)
- [Claude Code /compact documentation](https://docs.anthropic.com/claude-code)
- [DeepSeek API Docs](https://api-docs.deepseek.com)
