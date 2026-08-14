const LayoutInside = () => import('@/layouts/inside.vue');
const LayoutOutside = () => import('@/layouts/outside.vue');
const Login = () => import('@/pages/login/default.vue');
const Registro = () => import('@/pages/registro/default.vue');
const Error = () => import('@/pages/error/default.vue');

const Dashboard = () => import('@/pages/dashboard/default.vue');
const MiCuenta = () => import('@/pages/personas/mi_cuenta.vue');

// RESULTADOS DE LOS SORTEOS
const VerResultados = () => import('@/pages/ver_resultados/default.vue');

// CLIENTES
const Clientes = () => import('@/pages/clientes/default.vue');
const ClientesJugar = () => import('@/pages/clientes/default.vue');
const ClientesMisSorteos = () => import('@/pages/clientes/mis_sorteos/default.vue');
const ClientesMisPremios = () => import('@/pages/clientes/mis_premios/default.vue');

// DEL ADMINISTRADOR

// DEL VENDEDOR
const Vendedores = () => import('@/pages/vendedores/default.vue');
const VendedoresVentas = () => import('@/pages/vendedores/ventas/default.vue');
const VendedoresVender = () => import('@/pages/vendedores/vender/default.vue');

// ADMINISTRACION

// ENTIDADES


/// CONFIGURACION
const Configuracion = () => import('@/pages/configuracion/default.vue');
// LOTERIAS
const ConfiguracionLoterias = () => import('@/pages/configuracion/loterias/default.vue');
// JUEGOS
const ConfiguracionJuegos = () => import('@/pages/configuracion/juegos/default.vue');
const ConfiguracionSorteos = () => import('@/pages/vendedores/ventas/default.vue');

// AYUDA
const Ayuda = () => import('@/pages/ayuda/default.vue');

export const routes = [
    {
        path: '/login',
        name: "Login",
        component: Login
    },
    {
        path: '/',
        name: "Layout",
        component: LayoutInside,
        meta: {requiresAuth: true},
        children: [
            {
                path: '/',
                name: "Dashboard",
                component: Dashboard
            },
            {
                path: 'mi_cuenta',
                name: 'MiCuenta',
                component: MiCuenta,
                meta: {
                    breadcrumb: [
                        {name: "Mi Cuenta"},
                    ]
                }
            },
            {
                path: 'ver_resultados',
                name: 'VerResultados',
                component: VerResultados,
                meta: {
                    breadcrumb: [
                        {name: "Ver Resultados"},
                    ]
                }
            },
            // CLIENTES
            {
                path: '/mi_lotto',
                name: 'Clientes',
                component: Clientes,
                meta: {
                    breadcrumb: [
                        {name: "Mi Lotto"},
                    ]
                }
            },
            {
                path: '/mi_lotto/jugar',
                name: 'ClientesJugar',
                component: ClientesJugar,
                meta: {
                    breadcrumb: [
                        {name: "Mi Lotto"},
                        {name: "Jugar"},
                    ]
                }
            },
            {
                path: '/mi_lotto/mis_sorteos',
                name: 'ClientesMisSorteos',
                component: ClientesMisSorteos,
                meta: {
                    breadcrumb: [
                        {name: "Mi Lotto"},
                        {name: "Mis Sorteos"},
                    ]
                }
            },
            {
                path: 'mi_lotto/mis_premios',
                name: 'ClientesMisPremios',
                component: ClientesMisPremios,
                meta: {
                    breadcrumb: [
                        {name: "Mi Lotto"},
                        {name: "Mis Premios"},
                    ]
                }
            },
            // VENDEDORES
            {
                path: '/vendedores/ventas',
                name: 'VendedoresVentas',
                component: VendedoresVentas,
                meta: {
                    breadcrumb: [
                        {name: "Vendedores"},
                        {name: "Mis Ventas"},
                    ]
                }
            },
            {
                path: '/vendedores/vender',
                name: 'VendedoresVender',
                component: VendedoresVender,
                meta: {
                    breadcrumb: [
                        {name: "Vendedores"},
                        {name: "Vender"},
                    ]
                }
            },
            // CONFIGURACION
            {
                path: 'configuracion',
                name: 'Configuracion',
                component: Configuracion,
                meta: {
                    breadcrumb: [
                        {name: "Configuración"},
                    ]
                }
            },
            {
                path: '/config/loterias',
                name: 'ConfiguracionLoterias',
                component: ConfiguracionLoterias,
                meta: {
                    breadcrumb: [
                        {name: "Configuración"},
                        {name: "Loterias"},
                    ]
                }
            },
            {
                path: '/config/juegos',
                name: 'ConfiguracionJuegos',
                component: ConfiguracionJuegos,
                meta: {
                    breadcrumb: [
                        {name: "Configuración"},
                        {name: "Juegos de Lotería"},
                    ]
                }
            },
            {
                path: '/config/sorteos',
                name: 'ConfiguracionSorteos',
                component: ConfiguracionSorteos,
                meta: {
                    breadcrumb: [
                        {name: "Configuración"},
                        {name: "Sorteos de Loteria"},
                    ]
                }
            },
            // AYUDA
            {
                path: 'ayuda',
                name: 'Ayuda',
                component: Ayuda,
            },
        ],
    },
    {
        path: '/',
        component: LayoutOutside,
        children: [
            {
                path: 'registro',
                component: Registro,
                name: "Registro",
            },
            {
                path: '/:pathMatch(.*)*',
                component: Error,
                name: "Error",
            },
        ],
    },
]
