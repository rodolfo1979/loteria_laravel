import {createApp} from 'vue'
import App from '@/App.vue'
import axios from 'axios';
import ToastContainer from 'vue3-toastify';

// Styles
import 'vue3-toastify/dist/index.css';
import 'vue3-perfect-scrollbar/style.css';
import '@core-scss/template/index.scss'
import '@layouts/styles/index.scss'
import '@styles/styles.scss'

// LOCAL FILES
import {registerPlugins} from '@core/utils/plugins'
import filters from './utils/Filters';
import Ls from '@/utils/Ls';

// Create vue app
const app = createApp(App)

// Usar el plugin
app.use(ToastContainer, {
    autoClose: 3000,
    theme: "colored",
    position: 'top-right',
    hideProgressBar: true,
    pauseOnFocusLoss: false,
});

// Configurar URLs
const baseURL = window.location.origin;
app.config.globalProperties.baseURLImg = `${baseURL}/images`;
app.config.globalProperties.baseURLApi = `${baseURL}/api/v1/lotto`;

// Configuración de axios
axios.defaults.baseURL = app.config.globalProperties.baseURLApi;
axios.defaults.headers['X-Requested-With'] = 'XMLHttpRequest';

axios.interceptors.request.use((config) => {
    const token = Ls.get('token');
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
});

window.axios = axios;

// Register plugins
registerPlugins(app)

// Registrar funciones globalmente de los filtros
app.config.globalProperties.$filters = filters;

// Mount vue app
app.mount('#app')
