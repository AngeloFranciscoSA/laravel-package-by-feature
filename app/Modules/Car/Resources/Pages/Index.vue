<script setup>
// Importações do Inertia:
// - Head: define o <title> da página sem sair do Vue
// - Link: substitui o <a href> normal — faz navegação sem reload (SPA)
import { Head, Link } from '@inertiajs/vue3'

// defineProps recebe os dados enviados pelo Inertia::render() na Action PHP.
// 'cars' vem de $this->service->getAllCarsPaginated() — é um LengthAwarePaginator
// serializado automaticamente pelo Laravel como objeto com: data, links, meta, etc.
// 'flash' vem do HandleInertiaRequests::share() — disponível em todas as páginas.
const props = defineProps({
    cars: Object,
    flash: Object,
})
</script>

<template>
    <Head title="Cars" />

    <div class="min-h-screen bg-gray-50 py-10 px-4">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
            Cars Available
        </h1>

        <!-- Mensagem de flash (vinda de redirect()->with() no PHP) -->
        <!-- O tipo define a cor: success = verde, qualquer outro = vermelho -->
        <div
            v-if="flash?.msg"
            :class="flash.type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
            class="max-w-xl mx-auto mb-6 px-4 py-3 rounded text-sm text-center"
        >
            {{ flash.msg }}
        </div>

        <!-- cars.data → array de registros da página atual (LengthAwarePaginator) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            <div
                v-for="car in cars.data"
                :key="car.id"
                class="bg-white rounded-xl shadow p-5 flex flex-col items-center text-center"
            >
                <img
                    :src="car.image_url ?? 'https://placehold.co/640x360?text=No+Image'"
                    :alt="`${car.brand} ${car.model}`"
                    class="w-full h-40 object-cover rounded-lg mb-4"
                />
                <h3 class="text-lg font-bold text-gray-800">{{ car.brand }}</h3>
                <p class="text-sm text-gray-500">{{ car.model }} — {{ car.year }}</p>
                <p class="text-sm text-gray-500">{{ car.color }}</p>
                <p class="mt-2 text-blue-600 font-semibold">
                    {{ Number(car.price).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) }}
                </p>

                <!-- Link do Inertia: navega para /cars/:id sem reload de página -->
                <Link
                    :href="`/cars/${car.id}`"
                    class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition"
                >
                    Edit
                </Link>
            </div>
        </div>

        <!-- Paginação: cars.links vem do LengthAwarePaginator do Laravel -->
        <!-- Cada link tem: url (null se desabilitado), label (HTML com &laquo;/&raquo;), active -->
        <div class="flex justify-center mt-10 gap-2 flex-wrap">
            <Link
                v-for="link in cars.links"
                :key="link.label"
                :href="link.url ?? '#'"
                v-html="link.label"
                :class="[
                    'px-3 py-1 rounded text-sm border',
                    link.active
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-100',
                    !link.url ? 'opacity-40 pointer-events-none' : ''
                ]"
            />
        </div>
    </div>
</template>
