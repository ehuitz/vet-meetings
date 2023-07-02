<template>
    <Head title="Veterinarians" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Veterinarians</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">This is the Veterinarians!</div>

                    <div v-if="isLoading">
                        Loading...
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="sticky top-0">
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-right"></th>
                                <th class="px-6 py-3 bg-gray-50 text-left">  <input v-model="search_id" type="text"
                                        class="mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        placeholder="Search by Employee ID or Name"></th>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <div class="flex flex-row items-center justify-between cursor-pointer"
                                        @click="updateOrdering('employee_id')">
                                        <div class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                            :class="{ 'font-bold text-blue-600': orderColumn === 'id' }">
                                            Employee ID
                                        </div>
                                        <div class="select-none">
                                            <span :class="{
                                                'text-blue-600': orderDirection === 'asc' && orderColumn === 'id',
                                                'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'id',
                                            }">&uarr;</span>
                                            <span :class="{
                                                'text-blue-600': orderDirection === 'desc' && orderColumn === 'id',
                                                'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'id',
                                            }">&darr;</span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span
                                        class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Employee</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                            <tr v-for="veterinarian in veterinarians" :key="veterinarian.id">
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ veterinarian.employee_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ veterinarian.name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import useVeterinarians from '../Composables/veterinarians';

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    setup() {
        const { veterinarians, getVeterinarians } = useVeterinarians();
        const isLoading = ref(true)
        const search_id = ref('')
        const orderColumn = ref('employee_id')
        const orderDirection = ref('desc')

        onMounted(async () => {
            await getVeterinarians();
            isLoading.value = false;
        });

        const updateOrdering = (column) => {
            orderColumn.value = column;
            orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc';
            getVeterinarians(
                1,
                search_id.value,
                orderColumn.value,
                orderDirection.value
            );
        }

        watch(search_id, (current, previous) => {
            getVeterinarians(
                1,
                current
            )
        })

        return {
            veterinarians,
            isLoading,
            updateOrdering,
            search_id,
            orderColumn,
            orderDirection,
            updateOrdering,
        };
    },
};
</script>
