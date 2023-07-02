<template>
    <Head title="Busies" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Busies</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">This is the Busies!</div>

                    <div v-if="isLoading">
                        Loading...
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="sticky top-0">
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-right"></th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <select v-model="search_id" class="inline-block mt-1 w-full rounded-md
                            shadow-sm border-gray-300 focus:border-indigo-300 focus:ring
                            focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="" selected>-- All employees --</option>
                                        <option v-for="veterinarian in veterinarians" :value="veterinarian.id">
                                            {{ veterinarian.name }}
                                        </option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <div class="flex flex-row items-center justify-between cursor-pointer"
                                        @click="updateOrdering('schedule_id')">
                                        <div class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                            :class="{ 'font-bold text-blue-600': orderColumn === 'schedule_id' }">
                                            Employee ID
                                        </div>
                                        <div class="select-none">
                                            <span :class="{
                                                'text-blue-600': orderDirection === 'asc' && orderColumn === 'schedule_id',
                                                'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'schedule_id',
                                            }">&uarr;</span>
                                            <span :class="{
                                                'text-blue-600': orderDirection === 'desc' && orderColumn === 'schedule_id',
                                                'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'schedule_id',
                                            }">&darr;</span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span
                                        class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Employee</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span
                                        class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Start Date</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span
                                        class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">End Date</span>
                                </th>

                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <div class="flex flex-row items-center justify-between cursor-pointer"
                                        @click="updateOrdering('start_time')">
                                        <div class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                            :class="{ 'font-bold text-blue-600': orderColumn === 'start_time' }">
                                            Start Time
                                        </div>
                                        <div class="select-none">
                                            <span :class="{
                                                'text-blue-600': orderDirection === 'asc' && orderColumn === 'start_time',
                                                'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'start_time',
                                            }">&uarr;</span>
                                            <span :class="{
                                                'text-blue-600': orderDirection === 'desc' && orderColumn === 'start_time',
                                                'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'start_time',
                                            }">&darr;</span>
                                        </div>
                                    </div>
                                </th>

                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <div class="flex flex-row items-center justify-between cursor-pointer"
                                        @click="updateOrdering('end_time')">
                                        <div class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                            :class="{ 'font-bold text-blue-600': orderColumn === 'end_time' }">
                                            End Time
                                        </div>
                                        <div class="select-none">
                                            <span :class="{
                                                'text-blue-600': orderDirection === 'asc' && orderColumn === 'end_time',
                                                'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'end_time',
                                            }">&uarr;</span>
                                            <span :class="{
                                                'text-blue-600': orderDirection === 'desc' && orderColumn === 'end_time',
                                                'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'end_time',
                                            }">&darr;</span>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                            <tr v-for="busy in busies" :key="busy.id">
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ busy.veterinarian_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ busy.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ busy.start_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ busy.end_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ busy.start_time }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ busy.end_time }}
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
import useBusies from '../Composables/busies';
import useVeterinarians from '../Composables/veterinarians';

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    setup() {
        const { busies, getBusies } = useBusies();
        const isLoading = ref(true)
        const search_id = ref('')
        const orderColumn = ref('')
        const orderDirection = ref('')
        const { veterinarians, getVeterinarians } = useVeterinarians()

        onMounted(async () => {
            await getBusies();
            await getVeterinarians();
            isLoading.value = false;
        });

        const updateOrdering = (column) => {
            orderColumn.value = column;
            orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc';
            getBusies(
                1,
                search_id.value,
                orderColumn.value,
                orderDirection.value
            );
        }

        watch(search_id, (current, previous) => {
            getBusies(
                1,
                current
            )
        })

        return {
            busies,
            isLoading,
            veterinarians,
            updateOrdering,
            search_id,
            orderColumn,
            orderDirection,
            updateOrdering,
        };
    },
};
</script>
