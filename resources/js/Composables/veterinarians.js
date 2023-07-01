import { ref } from 'vue';
import axios from 'axios';

export default function useVeterinarians() {
    const veterinarians = ref([]);

    const getVeterinarians = async (
        page = 1,
        search_id = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
             axios.get('/api/veterinarians?page=' + page +
            '&search_id=' + search_id +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)

            .then(response => {
                veterinarians.value = response.data.data;
            })
    }

    return { veterinarians, getVeterinarians };
}
