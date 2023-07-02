import { ref } from 'vue';
import axios from 'axios';

export default function useSchedules() {
    const schedules = ref([]);

    const getSchedules = async (
        page = 1,
        search_id = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
             axios.get('/api/schedules?page=' + page +
            '&search_id=' + search_id +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)

            .then(response => {
                schedules.value = response.data.data;
            })
    }

    return { schedules, getSchedules };
}
