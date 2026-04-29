<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AppLayout from '../Components/AppLayout.vue'
import Swal from 'sweetalert2'

defineOptions({ layout: AppLayout })

const step = ref(1)
const form = useForm({
  brand: '',
  model: '',
  year: '',
  km: '',
  fuel: 'Flex Fuel',
  transmission: 'Automatic',
  color: '',
  price: '',
  state: '',
  city: '',
  description: '',
  name: '',
  phone: '',
  email: ''
})

const BRANDS = ['BMW','Chevrolet','Fiat','Ford','Honda','Hyundai','Jeep','Renault','Toyota','Volkswagen']
const STATES = ['CE','DF','MG','PE','PR','RJ','RS','SP']
const FUELS = ['Flex Fuel','Gasoline','Diesel','Hybrid','Electric']
const TRANSMISSIONS = ['Automatic','Manual']
const COLORS = ['Blue','White','Gray','Orange','Black','Silver','Red','Green']
const steps = ['Vehicle','Pricing','Contact','Review']

const inputStyle = {
  width: '100%',
  border: '1px solid #E2E8F0',
  borderRadius: '8px',
  padding: '10px 12px',
  fontSize: '14px',
  outline: 'none'
}

const labelStyle = {
  fontSize: '13px',
  fontWeight: 600,
  color: '#64748B',
  display: 'block',
  marginBottom: '6px'
}

const progressWidth = computed(() => `${((step.value - 1) / 3) * 75}%`)

const handleBackOrCancel = () => {
  if (step.value === 1) {
    // navigate to /cars via Inertia
    window.location.href = '/cars' // simplified; if using Inertia visit, import router and use router.visit('/cars')
  } else {
    step.value--
  }
}

const handleNextOrPublish = () => {
  if (step.value < 4) {
    step.value++
  } else {
    form.post('/cars/new', {
      onSuccess: () => Swal.fire('🎉', 'Listing published!', 'success')
    })
  }
}
</script>

<template>
  <Head title="New Listing — AutoVia" />
  <div style="max-width: 680px; margin: 0 auto; padding: 40px 24px;">
    <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 8px;">Novo Anúncio</h1>
    <p style="color: #64748B; font-size: 14px; margin-bottom: 32px;">Preencha os dados do seu veículo</p>

    <!-- Stepper -->
    <div style="display: flex; position: relative; margin-bottom: 40px;">
      <!-- Background line -->
      <div style="position: absolute; top: 16px; left: 12.5%; right: 12.5%; height: 2px; background: #E2E8F0; z-index: 0;"></div>
      <!-- Progress line -->
      <div
        :style="{
          position: 'absolute',
          top: '16px',
          left: '12.5%',
          height: '2px',
          background: '#f07040',
          zIndex: 1,
          transition: 'width 0.4s',
          width: progressWidth
        }"
      ></div>
      <!-- Steps -->
      <div
        v-for="(s, index) in steps"
        :key="index"
        style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; z-index: 2;"
      >
        <div
          :style="{
            width: '32px',
            height: '32px',
            borderRadius: '50%',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            fontSize: '13px',
            fontWeight: 700,
            background: step > index + 1 ? '#f07040' : (step === index + 1 ? '#f07040' : '#fff'),
            border: `2px solid ${step > index + 1 ? '#f07040' : (step === index + 1 ? '#f07040' : '#E2E8F0')}`,
            color: step > index + 1 ? '#fff' : (step === index + 1 ? '#fff' : '#94A3B8')
          }"
        >
          {{ step > index + 1 ? '✓' : index + 1 }}
        </div>
        <span
          :style="{
            fontSize: '12px',
            fontWeight: step === index + 1 ? 700 : 500,
            color: step === index + 1 ? '#f07040' : '#94A3B8'
          }"
        >
          {{ s }}
        </span>
      </div>
    </div>

    <!-- Form Card -->
    <div style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; padding: 32px;">
      <!-- Step 1: Vehicle -->
      <div v-if="step === 1" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <label :style="labelStyle">Brand</label>
          <select v-model="form.brand" :style="inputStyle">
            <option value="">Select</option>
            <option v-for="b in BRANDS" :key="b" :value="b">{{ b }}</option>
          </select>
        </div>
        <div>
          <label :style="labelStyle">Model</label>
          <input v-model="form.model" :style="inputStyle" placeholder="e.g. Onix" />
        </div>
        <div>
          <label :style="labelStyle">Year</label>
          <input v-model="form.year" type="number" :style="inputStyle" placeholder="2023" />
        </div>
        <div>
          <label :style="labelStyle">Mileage (km)</label>
          <input v-model="form.km" :style="inputStyle" placeholder="e.g. 20000" />
        </div>
        <div>
          <label :style="labelStyle">Fuel Type</label>
          <select v-model="form.fuel" :style="inputStyle">
            <option v-for="f in FUELS" :key="f" :value="f">{{ f }}</option>
          </select>
        </div>
        <div>
          <label :style="labelStyle">Transmission</label>
          <select v-model="form.transmission" :style="inputStyle">
            <option v-for="t in TRANSMISSIONS" :key="t" :value="t">{{ t }}</option>
          </select>
        </div>
        <div>
          <label :style="labelStyle">Color</label>
          <select v-model="form.color" :style="inputStyle">
            <option v-for="c in COLORS" :key="c" :value="c">{{ c }}</option>
          </select>
        </div>
      </div>

      <!-- Step 2: Pricing & Location -->
      <div v-if="step === 2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <!-- Price full width -->
        <div style="grid-column: span 2;">
          <label :style="labelStyle">Price R$</label>
          <input v-model="form.price" :style="inputStyle" placeholder="e.g. 55000" />
        </div>
        <div>
          <label :style="labelStyle">State</label>
          <select v-model="form.state" :style="inputStyle">
            <option value="">Select</option>
            <option v-for="s in STATES" :key="s" :value="s">{{ s }}</option>
          </select>
        </div>
        <div>
          <label :style="labelStyle">City</label>
          <input v-model="form.city" :style="inputStyle" placeholder="e.g. São Paulo" />
        </div>
        <div style="grid-column: span 2;">
          <label :style="labelStyle">Description</label>
          <textarea v-model="form.description" :style="inputStyle" rows="4" style="resize: vertical;"></textarea>
        </div>
      </div>

      <!-- Step 3: Contact -->
      <div v-if="step === 3" style="display: flex; flex-direction: column; gap: 16px;">
        <div>
          <label :style="labelStyle">Full name</label>
          <input v-model="form.name" :style="inputStyle" placeholder="Your name" />
        </div>
        <div>
          <label :style="labelStyle">Phone / WhatsApp</label>
          <input v-model="form.phone" :style="inputStyle" placeholder="(11) 99999-9999" />
        </div>
        <div>
          <label :style="labelStyle">Email</label>
          <input v-model="form.email" :style="inputStyle" placeholder="email@example.com" />
        </div>
      </div>

      <!-- Step 4: Review -->
      <div v-if="step === 4">
        <div style="background: #F8FAFC; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
          <div style="font-size: 16px; font-weight: 700;">{{ form.brand }} {{ form.model }}</div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.5px;">Brand/Model</div>
            <div style="font-size: 14px; font-weight: 600; color: #0F172A;">{{ form.brand }} {{ form.model }}</div>
          </div>
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.5px;">Year</div>
            <div style="font-size: 14px; font-weight: 600; color: #0F172A;">{{ form.year }}</div>
          </div>
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.5px;">Mileage</div>
            <div style="font-size: 14px; font-weight: 600; color: #0F172A;">{{ form.km }} km</div>
          </div>
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.5px;">Price</div>
            <div style="font-size: 14px; font-weight: 600; color: #0F172A;">R$ {{ form.price }}</div>
          </div>
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.5px;">Fuel</div>
            <div style="font-size: 14px; font-weight: 600; color: #0F172A;">{{ form.fuel }}</div>
          </div>
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.5px;">Transmission</div>
            <div style="font-size: 14px; font-weight: 600; color: #0F172A;">{{ form.transmission }}</div>
          </div>
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.5px;">Location</div>
            <div style="font-size: 14px; font-weight: 600; color: #0F172A;">{{ form.city }}, {{ form.state }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Buttons -->
    <div style="display: flex; justify-content: space-between; margin-top: 28px;">
      <button
        @click="handleBackOrCancel"
        style="background: #F1F5F9; color: #64748B; border-radius: 10px; padding: 12px 24px; font-weight: 600; border: none; cursor: pointer;"
      >
        {{ step === 1 ? 'Cancel' : '← Back' }}
      </button>
      <button
        @click="handleNextOrPublish"
        style="background: #f07040; color: #fff; border-radius: 10px; padding: 12px 32px; font-weight: 700; font-size: 15px; border: none; cursor: pointer;"
      >
        {{ step === 4 ? 'Publish listing ✓' : 'Next →' }}
      </button>
    </div>
  </div>
</template>
