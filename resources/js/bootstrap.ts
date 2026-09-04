// Bootstrap file for axios and client utilities if needed
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

declare global {
    interface Window {
        axios: typeof axios;
    }
}
