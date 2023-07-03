<template>
    <Head title="Syncs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Syncs</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div v-if="isLoading">
                        Loading...
                    </div>

                    <form @submit.prevent="submitForm" class="w-5/6 ml-auto mr-5 mb-5">
                        <div class="p-6 text-gray-900">Upload a Manual</div>

                        <!-- Origin -->
                        <div>
                            <label for="sync-origin" class="block font-medium text-sm text-gray-700">
                                Origin
                            </label>
                            <select v-model="sync.origin" id="sync-origin" class="inline-block mt-1 w-full rounded-md
                            shadow-sm border-gray-300 focus:border-indigo-300 focus:ring
                            focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="manual" selected>Manual</option>
                            </select>
                            <div class="text-red-600 mt-1">
                                {{ errors.origin }}
                            </div>
                            <div class="text-red-600 mt-1">
                                <div v-for="message in validationErrors?.origin">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <!-- File -->
                        <div class="mt-4">
                            <label for="file" class="block font-medium text-sm text-gray-700">
                                Upload Busy File
                            </label>
                            <input @change="sync.file = $event.target.files[0]" type="file" id="file" />
                            <div class="text-red-600 mt-1">
                                <div v-for="message in validationErrors?.file">
                                    {{ message }}
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-4">
                            <button :disabled="isLoading"
                                class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">
                                <div v-show="isLoading"
                                    class="inline-block animate-spin w-4 h-4 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full">
                                </div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Save</span>
                            </button>
                        </div>
                    </form>

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
                                    <a href="#" @click="openFile(sync.path)">{{ sync.path }}</a>
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
import { ref, onMounted, watch, reactive } from 'vue';
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "../Validation/rules"
import useSyncs from '../Composables/syncs';

defineRule('required', required)
defineRule('min', min);

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    setup() {
        const { syncs, getSyncs, storeSync, validationErrors } = useSyncs();
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

        const schema =
        {
            origin: 'required|min:3',
        }

        // Create a form context with the validation schema
        const { validate, errors } = useForm({ validationSchema: schema })

        // Define actual fields for validation
        const { value: status } = useField('status', null, { initialValue: '' });
        const { value: origin } = useField('origin', null, { initialValue: '' });

        const sync = reactive({
            status,
            origin,
            file: ''
        })

        function submitForm() {
            validate().then(form => { if (form.valid) storeSync(sync) })
        }

        function openFile(path) {
            const url = path;
            window.open(url, '_blank');
        }

        return {
            sync,
            syncs,
            isLoading,
            updateOrdering,
            orderColumn,
            orderDirection,
            updateOrdering,
            submitForm,
            errors,
            validationErrors,
            openFile
        };
    },
};
</script>
