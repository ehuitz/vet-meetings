<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div v-if="isLoading">
                        Loading...
                    </div>

                    <form @submit.prevent="submitForm" class="w-5/6 ml-auto mr-5 mb-5">
                        <div class="p-6 text-gray-900">Search for Free Slots</div>

                        <!-- Date -->
                        <div>
                            <label for="free-date" class="block font-medium text-sm text-gray-700">
                                Date
                            </label>
                            <input v-model="free.date" type="date"
                                class="mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                id="free-date">
                            <div class="text-red-600 mt-1">
                                {{ errors.date }}
                            </div>
                            <div class="text-red-600 mt-1">
                                <div v-for="message in validationErrors?.date">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <!-- Interval -->
                        <div>
                            <label for="free-interval" class="block font-medium text-sm text-gray-700">
                                Interval in minutes
                            </label>
                            <input v-model="free.interval" type="number"
                                class="mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="Enter interval in minutes" id="free-interval">
                            <div class="text-red-600 mt-1">
                                {{ errors.interval }}
                            </div>
                            <div class="text-red-600 mt-1">
                                <div v-for="message in validationErrors?.interval">
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
                                        class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Slot</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                            <tr v-for="free in frees" :key="free">
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ free }}</td>
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
import useFrees from '../Composables/frees';

defineRule('required', required)
defineRule('min', min);

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    setup() {
        const { frees, getFrees, searchFree, validationErrors } = useFrees();
        const isLoading = ref(true)

        onMounted(async () => {
            await getFrees();
            isLoading.value = false;
        });


        const schema =
        {
            date: 'required',
            interval: 'required',
        }

        // Create a form context with the validation schema
        const { validate, errors } = useForm({ validationSchema: schema })

        // Define actual fields for validation
        const { value: date } = useField('date', null, { initialValue: '2020-04-29' });
        const { value: interval } = useField('interval', null, { initialValue: '15' });

        const free = reactive({
            date,
            interval,
        })

        function submitForm() {
            validate().then(form => { if (form.valid) searchFree(free) })
        }

        return {
            free,
            frees,
            isLoading,
            submitForm,
            errors,
            validationErrors,
            searchFree
        };
    },
};
</script>
