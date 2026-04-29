<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import CarPlaceholder from '../Components/CarPlaceholder.vue'
import Badge from '../Components/Badge.vue'
import AppLayout from '../Components/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps(['car', 'seller', 'relatedCars'])

const activeTab = ref('details')
const contacted = ref(false)
const showPhone = ref(false)

const fmtPrice = computed(() => {
  return 'R$ ' + props.car.price.toLocaleString('pt-BR')
})

const fmtKm = (n) => {
  return n.toLocaleString('pt-BR') + ' km'
}

const sellerInitial = computed(() => {
  return props.seller?.name?.charAt(0) || '?'
})
</script>

<template>
  <Head :title="`${car.brand} ${car.model} — AutoVia`" />
  
  <div class="page">
    <Link href="/cars" class="back-link">← Voltar</Link>
    
    <div class="grid">
      <!-- Left Column -->
      <div class="left-col">
        <!-- Photo Card -->
        <div class="photo-card">
          <CarPlaceholder :car="car" :height="360" />
        </div>
        
        <!-- Tabs Card -->
        <div class="tabs-card">
          <div class="tab-bar">
            <button 
              :class="['tab-btn', { active: activeTab === 'details' }]"
              @click="activeTab = 'details'"
            >
              Details
            </button>
            <button 
              :class="['tab-btn', { active: activeTab === 'specs' }]"
              @click="activeTab = 'specs'"
            >
              Specifications
            </button>
          </div>
          <div class="tab-content">
            <!-- Details Tab -->
            <div v-if="activeTab === 'details'" class="spec-list">
              <div class="spec-row"><span class="label">Brand</span><span class="value">{{ car.brand }}</span></div>
              <div class="spec-row"><span class="label">Model</span><span class="value">{{ car.model }}</span></div>
              <div class="spec-row"><span class="label">Year</span><span class="value">{{ car.year }}</span></div>
              <div class="spec-row"><span class="label">Mileage</span><span class="value">{{ fmtKm(car.mileage) }}</span></div>
              <div class="spec-row"><span class="label">Fuel</span><span class="value">{{ car.fuel }}</span></div>
              <div class="spec-row"><span class="label">Transmission</span><span class="value">{{ car.transmission }}</span></div>
              <div class="spec-row"><span class="label">Color</span><span class="value">{{ car.color }}</span></div>
              <div class="spec-row"><span class="label">Location</span><span class="value">{{ car.location }}</span></div>
            </div>
            <!-- Specs Tab -->
            <div v-if="activeTab === 'specs'" class="spec-list">
              <div class="spec-row"><span class="label">Engine</span><span class="value">2.0 16V DOHC</span></div>
              <div class="spec-row"><span class="label">Power</span><span class="value">170 cv</span></div>
              <div class="spec-row"><span class="label">Torque</span><span class="value">20.4 kgfm</span></div>
              <div class="spec-row"><span class="label">0-100</span><span class="value">8.5s</span></div>
              <div class="spec-row"><span class="label">Top Speed</span><span class="value">210 km/h</span></div>
              <div class="spec-row"><span class="label">Fuel Economy</span><span class="value">12.5 km/l</span></div>
              <div class="spec-row"><span class="label">Steering</span><span class="value">Electric</span></div>
              <div class="spec-row"><span class="label">Doors</span><span class="value">4</span></div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Right Column -->
      <div class="right-col">
        <!-- Price Card -->
        <div class="price-card">
          <div class="price-card-top">
            <div>
              <div class="car-title">{{ car.brand }} {{ car.model }}</div>
              <div class="car-subtitle">{{ car.year }} · {{ fmtKm(car.mileage) }}</div>
            </div>
            <Badge v-if="car.badge" :text="car.badge" />
          </div>
          <div class="price">{{ fmtPrice }}</div>
          <div class="views">{{ car.views?.toLocaleString() }} views</div>
          <button 
            class="contact-btn"
            :class="{ contacted: contacted }"
            @click="contacted = true"
          >
            {{ contacted ? '✓ Message Sent!' : 'Contact Seller' }}
          </button>
          <div v-if="!showPhone" class="phone-btn" @click="showPhone = true">
            Show Phone
          </div>
          <div v-else class="phone-display">{{ seller.phone }}</div>
        </div>
        
        <!-- Seller Card -->
        <div class="seller-card" @click="router.visit(`/sellers/${seller.id}`)">
          <div class="seller-label">Anunciante</div>
          <div class="seller-info">
            <div class="avatar">{{ sellerInitial }}</div>
            <div>
              <div class="seller-name">{{ seller.name }}</div>
              <div class="seller-city">{{ seller.city }}, {{ seller.state }}</div>
            </div>
          </div>
          <div class="seller-rating">
            <span>⭐ {{ seller.rating }}</span>
            <span>{{ seller.reviews }} reviews</span>
          </div>
        </div>
        
        <!-- Related Cars -->
        <div v-if="relatedCars.length > 0" class="related-section">
          <div class="related-title">Outros {{ car.brand }}</div>
          <div class="related-list">
            <div 
              v-for="rc in relatedCars" 
              :key="rc.id" 
              class="related-item"
              @click="router.visit(`/cars/${rc.id}`)"
            >
              <div class="related-photo">
                <CarPlaceholder :car="rc" :height="42" />
              </div>
              <div>
                <div class="related-model">{{ rc.model }}, {{ rc.year }}</div>
                <div class="related-price">R$ {{ rc.price.toLocaleString('pt-BR') }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 32px 24px;
}
.back-link {
  border: none;
  background: none;
  cursor: pointer;
  color: #64748B;
  font-size: 14px;
  margin-bottom: 20px;
  display: inline-block;
  text-decoration: none;
}
.grid {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 32px;
}
.left-col {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
.photo-card {
  background: white;
  border-radius: 14px;
  overflow: hidden;
  border: 1px solid #E2E8F0;
}
.tabs-card {
  background: white;
  border-radius: 14px;
  border: 1px solid #E2E8F0;
  overflow: hidden;
}
.tab-bar {
  display: flex;
  border-bottom: 1px solid #E2E8F0;
}
.tab-btn {
  flex: 1;
  padding: 14px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  color: #64748B;
  border-bottom: 2px solid transparent;
}
.tab-btn.active {
  font-weight: 700;
  color: #f07040;
  border-bottom-color: #f07040;
}
.tab-content {
  padding: 24px;
}
.spec-row {
  padding: 12px 0;
  border-bottom: 1px solid #F1F5F9;
  display: flex;
  justify-content: space-between;
}
.spec-row .label {
  font-size: 14px;
  color: #64748B;
}
.spec-row .value {
  font-size: 14px;
  font-weight: 600;
  color: #0F172A;
}
.right-col {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.price-card {
  background: white;
  border-radius: 14px;
  border: 1px solid #E2E8F0;
  padding: 24px;
}
.price-card-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}
.car-title {
  font-size: 22px;
  font-weight: 800;
}
.car-subtitle {
  font-size: 14px;
  color: #64748B;
}
.price {
  font-size: 32px;
  font-weight: 800;
  color: #f07040;
  margin: 16px 0;
}
.views {
  font-size: 12px;
  color: #CBD5E1;
  margin-bottom: 16px;
}
.contact-btn {
  width: 100%;
  background: #f07040;
  color: white;
  border-radius: 10px;
  padding: 14px;
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  border: none;
  margin-bottom: 8px;
}
.contact-btn.contacted {
  background: #10B981;
}
.phone-btn, .phone-display {
  width: 100%;
  background: #F1F5F9;
  color: #0F172A;
  border-radius: 10px;
  padding: 12px;
  font-weight: 600;
  text-align: center;
  cursor: pointer;
  border: none;
}
.seller-card {
  background: white;
  border-radius: 14px;
  border: 1px solid #E2E8F0;
  padding: 20px;
  cursor: pointer;
}
.seller-label {
  font-size: 12px;
  font-weight: 700;
  color: #64748B;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 12px;
}
.seller-info {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 8px;
}
.avatar {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: #f0704020;
  color: #f07040;
  font-size: 18px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
}
.seller-name {
  font-weight: 600;
}
.seller-city {
  font-size: 13px;
  color: #64748B;
}
.seller-rating {
  display: flex;
  gap: 16px;
  font-size: 13px;
  color: #64748B;
}
.related-section {
  margin-top: 8px;
}
.related-title {
  font-size: 14px;
  font-weight: 700;
  color: #0F172A;
  margin-bottom: 12px;
}
.related-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.related-item {
  background: white;
  border-radius: 10px;
  border: 1px solid #E2E8F0;
  padding: 12px;
  display: flex;
  gap: 10px;
  align-items: center;
  cursor: pointer;
}
.related-photo {
  width: 60px;
  height: 42px;
  border-radius: 6px;
  overflow: hidden;
  flex-shrink: 0;
}
.related-model {
  font-size: 13px;
  font-weight: 600;
}
.related-price {
  font-size: 13px;
  font-weight: 700;
  color: #f07040;
}
</style>
