<script setup>
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import CarPlaceholder from '../Components/CarPlaceholder.vue'
import AppLayout from '../Components/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
  allCars: {
    type: Array,
    required: true
  }
})

const slots = ref([props.allCars[0] ?? null, props.allCars[1] ?? null, null])
const pickingSlot = ref(null)

function selectCar(car) {
  if (pickingSlot.value !== null) {
    slots.value[pickingSlot.value] = car
    pickingSlot.value = null
  }
}

function removeCar(i) {
  slots.value[i] = null
}

const filledCars = computed(() => slots.value.filter(Boolean))
const bestPrice = computed(() => filledCars.value.length > 1 ? Math.min(...filledCars.value.map(c => c.price)) : null)
const bestKm = computed(() => filledCars.value.length > 1 ? Math.min(...filledCars.value.map(c => c.km)) : null)
const bestYear = computed(() => filledCars.value.length > 1 ? Math.max(...filledCars.value.map(c => c.year)) : null)

function isWinner(car, key) {
  if (filledCars.value.length < 2 || !car) return false
  if (key === 'price') return car.price === bestPrice.value
  if (key === 'km') return car.km === bestKm.value
  if (key === 'year') return car.year === bestYear.value
  return false
}

function fmtPrice(p) {
  return 'R$ ' + p.toLocaleString('pt-BR')
}

function fmtKm(k) {
  return k.toLocaleString('pt-BR') + ' km'
}

const specRows = [
  { label: 'Price', key: 'price', getValue: c => fmtPrice(c.price) },
  { label: 'Year', key: 'year', getValue: c => c.year },
  { label: 'Mileage', key: 'km', getValue: c => fmtKm(c.km) },
  { label: 'Fuel', key: null, getValue: c => c.fuel },
  { label: 'Transmission', key: null, getValue: c => c.transmission },
  { label: 'Color', key: null, getValue: c => c.color },
  { label: 'Location', key: null, getValue: c => c.city + ', ' + c.state },
]
</script>

<template>
  <Head title="Compare — AutoVia" />
  <div style="max-width: 1100px; margin: 0 auto; padding: 32px 24px;">
    <h1 style="font-size: 24px; font-weight: 800; margin-bottom: 6px;">Comparar Carros</h1>
    <p style="font-size: 14px; color: #64748B; margin-bottom: 28px;">
      Selecione até 3 carros para comparar lado a lado
    </p>

    <!-- Overlay modal -->
    <div
      v-if="pickingSlot !== null"
      @click.self="pickingSlot = null"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 200; display: flex; align-items: center; justify-content: center;"
    >
      <div style="background: #fff; border-radius: 16px; padding: 24px; max-width: 560px; width: 90%; max-height: 70vh; overflow: auto;">
        <h2 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Escolha um carro</h2>
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <div
            v-for="car in props.allCars"
            :key="car.id"
            @click="selectCar(car)"
            :style="{
              display: 'flex', alignItems: 'center', gap: '12px', padding: '12px', borderRadius: '10px',
              border: '1px solid #E2E8F0', cursor: 'pointer',
              background: slots.includes(car) ? 'rgba(240,112,64,0.10)' : '#fff'
            }"
          >
            <div style="width: 64px; height: 44px; border-radius: 6px; overflow: hidden;">
              <CarPlaceholder :car="car" :height="44" />
            </div>
            <div style="flex: 1;">
              <div style="font-weight: 600; font-size: 14px;">{{ car.brand }} {{ car.model }} ({{ car.year }})</div>
              <div style="font-size: 13px; color: #64748B;">{{ fmtKm(car.km) }}</div>
            </div>
            <div style="font-weight: 700; color: #f07040;">{{ fmtPrice(car.price) }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Comparison grid -->
    <div style="display: grid; grid-template-columns: 160px repeat(3, 1fr); gap: 12px;">
      <!-- Row 1: Car headers -->
      <div style="padding-left: 4px;"></div> <!-- empty label -->
      <div v-for="i in 3" :key="'header-' + i">
        <div v-if="slots[i-1]" style="background: #fff; border-radius: 14px; border: 2px solid #E2E8F0; overflow: hidden;">
          <CarPlaceholder :car="slots[i-1]" :height="140" />
          <div style="padding: 12px 14px;">
            <div style="font-size: 14px; font-weight: 700;">{{ slots[i-1].brand }} {{ slots[i-1].model }}</div>
            <div style="font-size: 12px; color: #94A3B8; margin: 4px 0 8px;">{{ slots[i-1].year }}</div>
            <div style="display: flex; justify-content: space-between;">
              <button
                @click="router.visit('/cars/' + slots[i-1].id)"
                style="background: #f07040; color: #fff; border: none; border-radius: 6px; padding: 6px 12px; font-size: 12px; font-weight: 700; cursor: pointer;"
              >Ver</button>
              <button
                @click="removeCar(i-1)"
                style="background: none; border: none; cursor: pointer; color: #94A3B8; font-size: 18px;"
              >×</button>
            </div>
          </div>
        </div>
        <div v-else style="width: 100%; height: 200px; background: #F8FAFC; border-radius: 14px; border: 2px dashed #E2E8F0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: #94A3B8; cursor: pointer;" @click="pickingSlot = i-1">
          <div style="width: 40px; height: 40px; border: 2px dashed #f07040; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #f07040; font-size: 20px;">+</div>
          <div style="font-size: 13px; font-weight: 500;">Adicionar carro</div>
        </div>
      </div>

      <!-- Spec rows -->
      <template v-for="(specRow, index) in specRows" :key="'spec-' + index">
        <div style="font-size: 13px; font-weight: 600; color: #64748B; display: flex; align-items: center; padding-left: 4px;">{{ specRow.label }}</div>
        <div v-for="i in 3" :key="'value-' + index + '-' + i">
          <div v-if="slots[i-1]" 
            :style="{
              background: (specRow.key && isWinner(slots[i-1], specRow.key)) ? 'rgba(240,112,64,0.12)' : '#fff',
              borderRadius: '8px',
              padding: '12px 14px',
              fontSize: '14px',
              fontWeight: (specRow.key && isWinner(slots[i-1], specRow.key)) ? '700' : '500',
              color: (specRow.key && isWinner(slots[i-1], specRow.key)) ? '#f07040' : '#0F172A',
              border: (specRow.key && isWinner(slots[i-1], specRow.key)) ? '1px solid rgba(240,112,64,0.30)' : '1px solid #F1F5F9',
              display: 'flex', alignItems: 'center', gap: '6px'
            }"
          >
            <span>{{ specRow.getValue(slots[i-1]) }}</span>
            <span v-if="specRow.key && isWinner(slots[i-1], specRow.key)" 
              style="background: #f07040; color: #fff; border-radius: 4px; padding: 2px 6px; font-size: 11px; line-height: 1;">✓</span>
          </div>
          <div v-else style="color: #E2E8F0; border-radius: 8px; padding: 12px 14px; border: 1px solid #F1F5F9;">—</div>
        </div>
      </template>
    </div>
  </div>
</template>

<style scoped>
/* No custom styles needed; everything is inline */
</style>
