<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

// O componente recebe o carro a ser editado (ou null quando fechado)
const props = defineProps({
    car: Object,
})

const emit = defineEmits(['close'])

const form = useForm({
    brand: '',
    model: '',
    year:  '',
    color: '',
    price: '',
})

// Sempre que o carro mudar (ao abrir o modal), sincroniza o form com os dados dele
watch(() => props.car, (car) => {
    if (car) {
        form.brand = car.brand
        form.model = car.model
        form.year  = car.year
        form.color = car.color
        form.price = car.price
    }
})

function close() {
    form.reset()
    form.clearErrors()
    emit('close')
}

function submit() {
    form.put(`/cars/${props.car.id}`, {
        onSuccess: () => close(),
    })
}
</script>

<template>
    <Teleport to="body">
        <div
            v-if="car"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="close"
        >
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">

                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-800">
                        Edit — {{ car.brand }} {{ car.model }}
                    </h2>
                    <button
                        @click="close"
                        class="text-gray-400 hover:text-gray-600 text-xl leading-none"
                    >
                        &times;
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <input
                            v-model="form.brand"
                            type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.brand }"
                        />
                        <p v-if="form.errors.brand" class="text-red-500 text-xs mt-1">{{ form.errors.brand }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                        <input
                            v-model="form.model"
                            type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.model }"
                        />
                        <p v-if="form.errors.model" class="text-red-500 text-xs mt-1">{{ form.errors.model }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                            <input
                                v-model="form.year"
                                type="number"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.year }"
                            />
                            <p v-if="form.errors.year" class="text-red-500 text-xs mt-1">{{ form.errors.year }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                            <input
                                v-model="form.color"
                                type="text"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.color }"
                            />
                            <p v-if="form.errors.color" class="text-red-500 text-xs mt-1">{{ form.errors.color }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input
                            v-model="form.price"
                            type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.price }"
                        />
                        <p v-if="form.errors.price" class="text-red-500 text-xs mt-1">{{ form.errors.price }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="close"
                            class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Teleport>
</template>
