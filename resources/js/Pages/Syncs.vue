<template>
    <Head title="Syncs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Syncs</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">This is the Syncs!</div>

                    <div v-if="isLoading">
                        Loading...
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="sticky top-0">
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span
                                        class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span
                                        class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Origin</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span
                                        class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Path</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span
                                        class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <div class="flex flex-row items-center justify-between cursor-pointer"
                                        @click="updateOrdering('created_at')">
                                        <div class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                            :class="{ 'font-bold text-blue-600': orderColumn === 'created_at' }">
                                            Created At
                                        </div>
                                        <div class="select-none">
                                            <span :class="{
                                                'text-blue-600': orderDirection === 'asc' && orderColumn === 'created_at',
                                                'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'created_at',
                                            }">&uarr;</span>
                                            <span :class="{
                                                'text-blue-600': orderDirection === 'desc' && orderColumn === 'created_at',
                                                'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'created_at',
                                            }">&darr;</span>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                            <tr v-for="sync in syncs" :key="sync.id">
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ sync.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ sync.origin }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ sync.path }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ sync.status }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ sync.created_at }}
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
import useSyncs from '../Composables/syncs';

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    setup() {
        const { syncs, getSyncs } = useSyncs();
        const isLoading = ref(true)
        const orderColumn = ref('created_at')
        const orderDirection = ref('desc')

        onMounted(async () => {
            await getSyncs();
            isLoading.value = false;
        });

        const updateOrdering = (column) => {
            orderColumn.value = column;
            orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc';
            getSyncs(
                1,
                orderColumn.value,
                orderDirection.value
            );
        }

        return {
            syncs,
            isLoading,
            updateOrdering,
            orderColumn,
            orderDirection,
            updateOrdering,
        };
    },
};
</script>
