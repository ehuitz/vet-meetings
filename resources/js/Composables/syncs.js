import { ref} from 'vue';
import axios from 'axios';

export default function useSyncs() {
    const syncs = ref({textFile: ''});
    const isLoading = ref(false)
     const validationErrors = ref({})

    const getSyncs = async (
        page = 1,
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
             axios.get('/api/syncs?page=' + page +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)

            .then(response => {
                syncs.value = response.data.data;
            })
    }

    const storeSync = async (sync) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializedSync = new FormData()
        for (let item in sync) {
            if (sync.hasOwnProperty(item)) {
                serializedSync.append(item, sync[item])
            }
        }

        axios.post('/api/syncs', serializedSync)
        .then(response => {
            syncs.value = response.data.data;
        })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    return { syncs, getSyncs, isLoading, storeSync, validationErrors };
}
