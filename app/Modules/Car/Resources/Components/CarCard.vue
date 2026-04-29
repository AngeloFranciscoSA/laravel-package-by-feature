<template>
  <div
    :style="cardStyle"
    @mouseenter="hov = true"
    @mouseleave="hov = false"
    @click="$emit('click')"
  >
    <!-- Grid layout -->
    <template v-if="layout === 'grid'">
      <div style="position: relative;">
        <CarPlaceholder :car="car" :height="180" />
        <Badge v-if="car.badge" :label="car.badge" style="position: absolute; top: 10px; left: 10px;" />
        <button
          style="position: absolute; top: 8px; right: 8px; width: 32px; height: 32px; border-radius: 50%; border: none; background: rgba(255,255,255,0.9); display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; line-height: 1;"
          :style="{ color: fav ? '#EF4444' : '#94A3B8' }"
          @click.stop="fav = !fav"
        >
          {{ fav ? '♥' : '♡' }}
        </button>
      </div>
      <div style="padding: 14px 16px;">
        <div style="font-size: 15px; font-weight: 700; color: #0F172A;">{{ car.brand }} {{ car.model }}</div>
        <div style="font-size: 12px; color: #94A3B8; margin: 3px 0 10px;">{{ car.year }} · {{ fmtKm }} · {{ car.fuel }}</div>
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <span style="font-size: 17px; font-weight: 800; color: #f07040;">{{ fmtPrice }}</span>
          <span style="font-size: 12px; color: #CBD5E1;">{{ car.city }}, {{ car.state }}</span>
        </div>
      </div>
    </template>

    <!-- List layout -->
    <template v-else>
      <div style="display: flex; flex-direction: row; width: 100%;">
        <div style="width: 220px; flex-shrink: 0; position: relative;">
          <CarPlaceholder :car="car" :height="140" />
          <Badge v-if="car.badge" :label="car.badge" style="position: absolute; top: 10px; left: 10px;" />
        </div>
        <div style="padding: 16px 20px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div style="display: flex; justify-content: space-between;">
              <span style="font-size: 16px; font-weight: 700; color: #0F172A;">{{ car.brand }} {{ car.model }}</span>
              <span style="font-size: 18px; font-weight: 800; color: #f07040;">{{ fmtPrice }}</span>
            </div>
            <div style="font-size: 13px; color: #64748B; margin-top: 2px;">{{ car.year }} · {{ car.transmission }} · {{ car.fuel }}</div>
          </div>
          <div style="display: flex; gap: 16px; font-size: 13px; color: #94A3B8;">
            <span>{{ fmtKm }}</span>
            <span>{{ car.city }}, {{ car.state }}</span>
            <span>{{ car.views }} visualizações</span>
          </div>
        </div>
        <div style="display: flex; align-items: center; padding: 0 16px; border: none; background: none; cursor: pointer; font-size: 20px;"
          :style="{ color: fav ? '#EF4444' : '#CBD5E1' }"
          @click.stop="fav = !fav"
        >
          {{ fav ? '♥' : '♡' }}
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import CarPlaceholder from './CarPlaceholder.vue'
import Badge from './Badge.vue'

const props = defineProps({
  car: {
    type: Object,
    required: true
  },
  layout: {
    type: String,
    default: 'grid'
  }
})

const emit = defineEmits(['click'])

const fav = ref(false)
const hov = ref(false)

const fmtPrice = computed(() => `R$ ${props.car.price.toLocaleString('pt-BR')}`)
const fmtKm = computed(() => `${props.car.km.toLocaleString('pt-BR')} km`)

const cardStyle = computed(() => {
  const base = {
    background: '#fff',
    overflow: 'hidden',
    cursor: 'pointer',
    transition: 'all 0.2s'
  }
  if (props.layout === 'grid') {
    return {
      ...base,
      borderRadius: '14px',
      border: `1px solid ${hov.value ? '#f07040' + '40' : '#E2E8F0'}`,
      boxShadow: hov.value ? '0 8px 24px rgba(0,0,0,0.10)' : '0 1px 4px rgba(0,0,0,0.04)',
      transform: hov.value ? 'translateY(-2px)' : 'none'
    }
  } else {
    return {
      ...base,
      borderRadius: '12px',
      border: `1px solid ${hov.value ? '#f07040' + '40' : '#E2E8F0'}`,
      boxShadow: hov.value ? '0 4px 20px rgba(0,0,0,0.08)' : 'none'
    }
  }
})
</script>