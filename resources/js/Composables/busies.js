import { ref } from 'vue';
import axios from 'axios';

export default function useBusies() {
    const busies = ref([]);

    const getBusies = async (
        page = 1,
        search_id = '',
        order_column = '',
        order_direction = 'desc'
    ) => {
             axios.get('/api/busies?page=' + page +
            '&search_id=' + search_id +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)

            .then(response => {
                busies.value = response.data.data;
            })
    }

    return { busies, getBusies };
}
