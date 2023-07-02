import { ref } from 'vue';
import axios from 'axios';

export default function useSyncs() {
    const syncs = ref([]);

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

    return { syncs, getSyncs };
}
