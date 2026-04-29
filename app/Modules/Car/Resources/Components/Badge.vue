<template>
  <span
    :style="{
      background: badgeColor,
      color: '#ffffff',
      fontSize: '11px',
      fontWeight: 700,
      padding: '3px 8px',
      borderRadius: '4px',
      letterSpacing: '0.3px',
      display: 'inline-block'
    }"
  >
    {{ label }}
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  label: {
    type: String,
    required: true,
    validator: (value) => ['Featured', 'Top Viewed', 'New', 'Premium'].includes(value)
  }
});

const colorMap = {
  Featured: '#f07040',
  'Top Viewed': '#F59E0B',
  New: '#10B981',
  Premium: '#8B5CF6'
};

const badgeColor = computed(() => colorMap[props.label] || '#64748B');
</script>