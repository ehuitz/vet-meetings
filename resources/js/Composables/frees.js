import { ref } from 'vue';
import axios from 'axios';

export default function useFrees() {
  const frees = ref([]);
  const isLoading = ref(false);
  const validationErrors = ref({});

  const getFrees = async (page = 1, date = '2020-04-29', interval = '15') => {
    try {
      const response = await axios.get('/api/frees', {
        params: { page, date, interval },
      });
      frees.value = response.data.slots;
    } catch (error) {
      console.error(error);
    }
  };

  const searchFree = async (free) => {
    if (isLoading.value) return;

    isLoading.value = true;
    validationErrors.value = {};

    try {
      const response = await axios.get('/api/frees', {
        params: free,
      });
      frees.value = response.data.slots;
    } catch (error) {
      if (error.response?.data) {
        validationErrors.value = error.response.data.errors;
      }
    } finally {
      isLoading.value = false;
    }
  };

  return { frees, getFrees, searchFree, isLoading, validationErrors };
}
