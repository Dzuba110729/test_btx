import axios from 'axios';

axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('auth_token')}`;

export default axios;
