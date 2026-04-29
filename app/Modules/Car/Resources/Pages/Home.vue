<template>
  <Head title="Home — AutoVia" />

  <!-- HERO SECTION -->
  <section
    style="background: linear-gradient(135deg, #0F172A 0%, #1E3A5F 100%); padding: 64px 24px;"
  >
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
      <p
        style="
          font-size: 12px;
          text-transform: uppercase;
          letter-spacing: 2px;
          color: #f07040;
          font-weight: 700;
          margin: 0 0 12px 0;
        "
      >
        Car Marketplace
      </p>
      <h1
        style="
          font-size: 42px;
          font-weight: 800;
          color: #fff;
          line-height: 1.15;
          letter-spacing: -1px;
          margin-bottom: 16px;
        "
      >
        Buy and sell cars<br /><span style="color: #f07040;">with confidence</span>
      </h1>
      <p style="font-size: 16px; color: #94A3B8; margin-bottom: 36px;">
        Over 12,000 listings from private sellers across Brazil
      </p>
      <!-- Search bar -->
      <div
        style="
          display: flex;
          gap: 8px;
          max-width: 560px;
          margin: 0 auto;
          background: #fff;
          border-radius: 12px;
          padding: 8px;
          box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        "
      >
        <input
          v-model="searchQuery"
          placeholder="Search by brand, model..."
          style="
            flex: 1;
            border: none;
            font-size: 15px;
            outline: none;
            padding: 0 8px;
          "
          @keydown.enter="search"
        />
        <button
          style="
            background: #f07040;
            color: #fff;
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 700;
            border: none;
            cursor: pointer;
          "
          @click="search"
        >
          Search
        </button>
      </div>
      <!-- Stats row -->
      <div
        style="
          display: flex;
          gap: 24px;
          justify-content: center;
          margin-top: 28px;
        "
      >
        <div>
          <div style="font-size: 20px; font-weight: 800; color: #fff;">12k+</div>
          <div style="font-size: 12px; color: #64748B;">Listings</div>
        </div>
        <div>
          <div style="font-size: 20px; font-weight: 800; color: #fff;">4.8★</div>
          <div style="font-size: 12px; color: #64748B;">Rating</div>
        </div>
        <div>
          <div style="font-size: 20px; font-weight: 800; color: #fff;">98%</div>
          <div style="font-size: 12px; color: #64748B;">Safety</div>
        </div>
      </div>
    </div>
  </section>

  <!-- MAIN CONTENT AREA -->
  <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px 64px;">
    <!-- Featured section -->
    <div style="margin-top: 48px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
          <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0;">Featured</h2>
          <p style="font-size: 14px; color: #64748B; margin: 4px 0 0 0;">Handpicked for you</p>
        </div>
        <Link
          href="/cars/search"
          style="
            border: 1px solid #f07040;
            color: #f07040;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
          "
        >
          Ver todos →
        </Link>
      </div>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
        <CarCard
          v-for="car in featured"
          :key="car.id"
          :car="car"
          layout="grid"
          @click="goToDetail(car)"
        />
      </div>
    </div>

    <!-- Most Viewed section -->
    <div style="margin-top: 52px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
          <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0;">Maiores Visualizações</h2>
          <p style="font-size: 14px; color: #64748B; margin: 4px 0 0 0;">Most searched this week</p>
        </div>
        <Link
          href="/cars/search"
          style="
            border: 1px solid #f07040;
            color: #f07040;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
          "
        >
          Ver todos →
        </Link>
      </div>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
        <div
          v-for="(car, index) in mostViewed"
          :key="car.id"
          style="position: relative;"
        >
          <div
            style="
              position: absolute;
              top: -10px;
              left: -8px;
              z-index: 2;
              width: 28px;
              height: 28px;
              border-radius: 50%;
              background: #f07040;
              color: #fff;
              font-size: 13px;
              font-weight: 800;
              display: flex;
              align-items: center;
              justify-content: center;
            "
            :style="{ background: index < 3 ? '#f07040' : '#94A3B8' }"
          >
            {{ index + 1 }}
          </div>
          <CarCard
            :car="car"
            layout="grid"
            @click="goToDetail(car)"
          />
        </div>
      </div>
    </div>

    <!-- More Listings section -->
    <div style="margin-top: 52px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
          <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0;">Mais Anúncios</h2>
        </div>
        <Link
          href="/cars/search"
          style="
            border: 1px solid #f07040;
            color: #f07040;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
          "
        >
          Ver todos →
        </Link>
      </div>
      <div style="display: flex; flex-direction: column; gap: 12px;">
        <CarCard
          v-for="car in others"
          :key="car.id"
          :car="car"
          layout="list"
          @click="goToDetail(car)"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import CarCard from '../Components/CarCard.vue'
import AppLayout from '../Components/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
  featured: Array,
  mostViewed: Array,
  others: Array,
})

const searchQuery = ref('')

function search() {
  router.get('/cars/search', { brand: searchQuery.value })
}

function goToDetail(car) {
  router.visit('/cars/' + car.id)
}
</script>