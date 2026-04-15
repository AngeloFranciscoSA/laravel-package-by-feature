<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import EditCarModal from '../Components/EditCarModal.vue'

const props = defineProps({
    cars: Object,
    flash: Object,
})

// null = modal fechado | objeto car = modal aberto com esse carro
const editingCar = ref(null)
</script>

<template>
    <Head title="Cars" />

    <div class="min-h-screen bg-gray-50 py-10 px-4">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
            Cars Available
        </h1>

        <!-- Flash message -->
        <div
            v-if="flash?.msg"
            :class="flash.type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
            class="max-w-xl mx-auto mb-6 px-4 py-3 rounded text-sm text-center"
        >
            {{ flash.msg }}
        </div>

        <!-- Car grid -->
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

                <button
                    @click="editingCar = car"
                    class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition"
                >
                    Edit
                </button>
            </div>
        </div>

        <!-- Pagination -->
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

    <!-- O modal recebe o carro selecionado e emite 'close' para se fechar -->
    <EditCarModal :car="editingCar" @close="editingCar = null" />
</template>
