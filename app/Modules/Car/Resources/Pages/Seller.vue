<template>
  <Head :title="`${seller.name} — AutoVia`" />
  
  <div class="seller-page">
    <Link href="/cars/search" class="back-button">← Voltar</Link>
    
    <div class="seller-header-card">
      <div class="header-gradient">
        <div class="header-row">
          <div class="avatar">{{ seller.name.charAt(0) }}</div>
          <div class="info">
            <h1>{{ seller.name }}</h1>
            <p>{{ seller.city }}, {{ seller.state }} · Member since {{ seller.since }}</p>
            <div class="info-row">
              <span class="rating">⭐ <strong>{{ seller.rating }}</strong> ({{ seller.reviews }} reviews)</span>
              <span class="listings-count">{{ cars.length }} active listings</span>
            </div>
          </div>
          <div class="spacer"></div>
          <button class="contact-btn">Contato</button>
        </div>
      </div>
    </div>
    
    <div class="listings-section">
      <h2>Anúncios de {{ seller.name }}</h2>
      <div class="grid">
        <CarCard
          v-for="car in cars"
          :key="car.id"
          :car="car"
          layout="grid"
          @click="router.visit(`/cars/${car.id}`)"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineOptions } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import CarCard from '../Components/CarCard.vue'
import AppLayout from '../Components/AppLayout.vue'

defineOptions({
  layout: AppLayout,
})

defineProps({
  seller: Object,
  cars: Array,
})
</script>

<style scoped>
.seller-page {
  max-width: 1000px;
  margin: 0 auto;
  padding: 32px 24px;
}

.back-button {
  display: inline-block;
  margin-bottom: 24px;
  color: #64748B;
  text-decoration: none;
  font-size: 14px;
}

.back-button:hover {
  color: #f07040;
}

.seller-header-card {
  background: white;
  border-radius: 16px;
  border: 1px solid #E2E8F0;
  overflow: hidden;
  margin-bottom: 24px;
}

.header-gradient {
  padding: 32px 32px 24px;
  background: linear-gradient(135deg, rgba(240,112,64,0.15), rgba(240,112,64,0.05));
}

.header-row {
  display: flex;
  gap: 20px;
  align-items: center;
}

.avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: rgba(240,112,64,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  font-weight: 800;
  color: #f07040;
  flex-shrink: 0;
}

.info h1 {
  font-size: 24px;
  font-weight: 800;
  margin: 0;
}

.info p {
  color: #64748B;
  margin-top: 4px;
  font-size: 14px;
}

.info-row {
  display: flex;
  gap: 16px;
  margin-top: 8px;
  font-size: 14px;
}

.rating {
  font-size: 14px;
}

.listings-count {
  color: #64748B;
}

.spacer {
  margin-left: auto;
}

.contact-btn {
  background: #f07040;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 12px 24px;
  font-weight: 700;
  cursor: pointer;
  font-size: 14px;
}

.contact-btn:hover {
  background: #e05a2e;
}

.listings-section {
  margin-top: 0;
}

.listings-section h2 {
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 16px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}
</style>