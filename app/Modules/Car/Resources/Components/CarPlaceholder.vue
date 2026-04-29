<template>
  <div
    :style="{
      width: '100%',
      height: height + 'px',
      backgroundColor: bgColor,
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      justifyContent: 'center',
      gap: '8px',
      borderRadius: '8px'
    }"
  >
    <svg width="72" height="44" viewBox="0 0 72 44" xmlns="http://www.w3.org/2000/svg">
      <rect x="8" y="20" width="56" height="16" rx="3" :fill="tcColor" :opacity="0.3" />
      <path d="M16 20 L22 8 L50 8 L56 20Z" :fill="tcColor" :opacity="0.4" />
      <circle cx="20" cy="36" r="6" :fill="tcColor" :opacity="0.6" />
      <circle cx="52" cy="36" r="6" :fill="tcColor" :opacity="0.6" />
      <rect x="24" y="10" width="10" height="8" rx="1" :fill="tcColor" :opacity="0.25" />
      <rect x="38" y="10" width="10" height="8" rx="1" :fill="tcColor" :opacity="0.25" />
    </svg>
    <span
      :style="{
        fontSize: '11px',
        fontWeight: 600,
        color: tcColor,
        opacity: 0.7,
        fontFamily: 'monospace'
      }"
    >
      {{ car.brand }} {{ car.model }}
    </span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  car: {
    type: Object,
    required: true
  },
  height: {
    type: Number,
    default: 200
  }
})

const bgMap = {
  Toyota: '#E8F4FD', Honda: '#FEF3C7', Jeep: '#F0FDF4',
  Volkswagen: '#EFF6FF', Hyundai: '#FFF7ED', Fiat: '#F5F3FF',
  Chevrolet: '#FFF1F2', Renault: '#ECFEFF', BMW: '#F8FAFC', Ford: '#F0FDF4'
}

const tcMap = {
  Toyota: '#0369A1', Honda: '#D97706', Jeep: '#15803D',
  Volkswagen: '#1D4ED8', Hyundai: '#C2410C', Fiat: '#7C3AED',
  Chevrolet: '#E11D48', Renault: '#0891B2', BMW: '#334155', Ford: '#15803D'
}

const bgColor = computed(() => bgMap[props.car.brand] || '#F1F5F9')
const tcColor = computed(() => tcMap[props.car.brand] || '#475569')
</script>