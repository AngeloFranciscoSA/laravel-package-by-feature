<template>
  <Head title="Search — AutoVia" />
  <div style="max-width: 1200px; margin: 0 auto; padding: 32px 24px">
    <h1 style="font-size: 24px; font-weight: 800; margin-bottom: 24px">
      Buscar Carros
    </h1>

    <div class="search-layout" style="display: grid; grid-template-columns: 280px 1fr; gap: 32px">
      <!-- Sidebar Filters -->
      <aside class="sidebar" style="position: sticky; top: 80px">
        <div style="background: #fff; border-radius: 14px; border: 1px solid #E2E8F0; padding: 20px">
          <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 20px">Filtros</h3>

          <!-- Brand -->
          <div style="margin-bottom: 16px">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; display: block; margin-bottom: 4px">Marca</label>
            <select v-model="localFilters.brand" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 100%; outline: none; background: #fff">
              <option value=""></option>
              <option v-for="b in brands" :key="b" :value="b">{{ b }}</option>
            </select>
          </div>

          <!-- State -->
          <div style="margin-bottom: 16px">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; display: block; margin-bottom: 4px">Estado</label>
            <select v-model="localFilters.state" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 100%; outline: none; background: #fff">
              <option value=""></option>
              <option v-for="s in states" :key="s" :value="s">{{ s }}</option>
            </select>
          </div>

          <!-- Fuel -->
          <div style="margin-bottom: 16px">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; display: block; margin-bottom: 4px">Combustível</label>
            <select v-model="localFilters.fuel" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 100%; outline: none; background: #fff">
              <option value=""></option>
              <option v-for="f in fuels" :key="f" :value="f">{{ f }}</option>
            </select>
          </div>

          <!-- Transmission -->
          <div style="margin-bottom: 16px">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; display: block; margin-bottom: 4px">Câmbio</label>
            <select v-model="localFilters.transmission" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 100%; outline: none; background: #fff">
              <option value=""></option>
              <option v-for="t in transmissions" :key="t" :value="t">{{ t }}</option>
            </select>
          </div>

          <!-- Color -->
          <div style="margin-bottom: 16px">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; display: block; margin-bottom: 4px">Cor</label>
            <select v-model="localFilters.color" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 100%; outline: none; background: #fff">
              <option value=""></option>
              <option v-for="c in colors" :key="c" :value="c">{{ c }}</option>
            </select>
          </div>

          <!-- Price -->
          <div style="margin-bottom: 16px">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; display: block; margin-bottom: 4px">Preço (R$)</label>
            <div style="display: flex; gap: 8px">
              <input type="number" v-model="localFilters.min_price" placeholder="Min" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 50%; outline: none; background: #fff" />
              <input type="number" v-model="localFilters.max_price" placeholder="Max" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 50%; outline: none; background: #fff" />
            </div>
          </div>

          <!-- Year -->
          <div style="margin-bottom: 16px">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; display: block; margin-bottom: 4px">Ano</label>
            <div style="display: flex; gap: 8px">
              <input type="number" v-model="localFilters.min_year" placeholder="Min" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 50%; outline: none; background: #fff" />
              <input type="number" v-model="localFilters.max_year" placeholder="Max" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 50%; outline: none; background: #fff" />
            </div>
          </div>

          <!-- Km -->
          <div style="margin-bottom: 20px">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; display: block; margin-bottom: 4px">Km</label>
            <div style="display: flex; gap: 8px">
              <input type="number" v-model="localFilters.min_km" placeholder="Min" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 50%; outline: none; background: #fff" />
              <input type="number" v-model="localFilters.max_km" placeholder="Max" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; width: 50%; outline: none; background: #fff" />
            </div>
          </div>

          <!-- Clear filters -->
          <button @click="clearFilters" style="width: 100%; background: #F1F5F9; color: #64748B; border: none; border-radius: 8px; padding: 10px; font-size: 13px; font-weight: 600; cursor: pointer">
            Limpar filtros
          </button>
        </div>
      </aside>

      <!-- Results -->
      <main>
        <!-- top bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px">
          <span style="font-size: 14px; color: #64748B">
            <strong>{{ cars.length }}</strong> cars found
          </span>
          <div style="display: flex; align-items: center; gap: 16px">
            <select v-model="sortBy" style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 13px; outline: none; background: #fff">
              <option value="views">Most viewed</option>
              <option value="price_asc">Lowest price</option>
              <option value="price_desc">Highest price</option>
              <option value="year">Newest (year)</option>
            </select>
            <div style="display: flex; gap: 4px; background: #F1F5F9; border-radius: 8px; padding: 3px">
              <button @click="viewMode = 'grid'" :style="{
                background: viewMode === 'grid' ? '#fff' : 'transparent',
                border: 'none',
                borderRadius: '6px',
                padding: '5px 10px',
                boxShadow: viewMode === 'grid' ? '0 1px 2px rgba(0,0,0,0.05)' : 'none',
                cursor: 'pointer',
                fontSize: '16px',
                lineHeight: 1
              }">⊞</button>
              <button @click="viewMode = 'list'" :style="{
                background: viewMode === 'list' ? '#fff' : 'transparent',
                border: 'none',
                borderRadius: '6px',
                padding: '5px 10px',
                boxShadow: viewMode === 'list' ? '0 1px 2px rgba(0,0,0,0.05)' : 'none',
                cursor: 'pointer',
                fontSize: '16px',
                lineHeight: 1
              }">☰</button>
            </div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-if="cars.length === 0" style="text-align: center; padding: 60px 0">
          <div style="font-size: 40px">🔍</div>
          <div style="font-weight: 600; margin-top: 12px">Nenhum carro encontrado</div>
          <div style="font-size: 14px; color: #64748B; margin-top: 4px">Tente ajustar os filtros</div>
        </div>

        <!-- Grid results -->
        <div v-else-if="viewMode === 'grid'" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px">
          <CarCard v-for="car in cars" :key="car.id" :car="car" layout="grid" @click="goToDetail(car)" style="cursor: pointer" />
        </div>

        <!-- List results -->
        <div v-else style="display: flex; flex-direction: column; gap: 12px">
          <CarCard v-for="car in cars" :key="car.id" :car="car" layout="list" @click="goToDetail(car)" style="cursor: pointer" />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'
import { watchDebounced } from '@vueuse/core'
import CarCard from '../Components/CarCard.vue'
import AppLayout from '../Components/AppLayout.vue'
import { defineOptions } from 'vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
  cars: {
    type: Array,
    required: true
  },
  filters: {
    type: Object,
    required: true
  },
  sort: {
    type: String,
    default: 'views'
  }
})

// Local reactive copy of filters
const localFilters = reactive({ ...props.filters })
const sortBy = ref(props.sort || 'views')
const viewMode = ref('grid')

// Static filter options
const brands = ['BMW','Chevrolet','Fiat','Ford','Honda','Hyundai','Jeep','Renault','Toyota','Volkswagen']
const states = ['CE','DF','MG','PE','PR','RJ','RS','SP']
const fuels = ['Flex Fuel','Gasoline','Diesel','Hybrid','Electric']
const transmissions = ['Automatic','Manual']
const colors = ['Blue','White','Gray','Orange','Black','Silver','Red','Green']

function applyFilters() {
  router.get('/cars/search', { ...localFilters, sort: sortBy.value }, { preserveState: true, replace: true })
}

watchDebounced([localFilters, sortBy], applyFilters, { debounce: 400 })

function clearFilters() {
  Object.keys(localFilters).forEach(k => localFilters[k] = '')
  sortBy.value = 'views'
}

function goToDetail(car) {
  router.visit('/cars/' + car.id)
}
</script>
