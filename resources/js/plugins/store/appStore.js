import {defineStore} from 'pinia';
import 'moment-timezone';
import moment from 'moment';

moment.tz.setDefault('America/Managua');
const fechaHoy = moment().format('YYYY-MM-DD');
const fechaMenos1Mes = moment().subtract(30, 'days').format('YYYY-MM-DD');
const fechaMenos1Anyo = moment().subtract(1, 'year').format('YYYY-MM-DD');

export const useAppStore = defineStore('appStore', {
    state: () => ({
        fechaInicio: fechaMenos1Mes,
        fechaFin: fechaHoy,
        fechaHoy: fechaHoy,
        fechaMenos1Anyo: fechaMenos1Anyo,
        arrayRowsPages: [15, 30, 50, 100],
        app: {
            name: "Lotto Manager",
            logo: '@/images/logo.png',
            anyoFooter: 2024,
            moneda: "₡",
            minCharUser: 6,
            minCharPass: 8,
        },
        mensajes: {
            placeHolderBuscarPeriodoFechas: "Seleccione el Período de Fechas y Pulse en Buscar",
            placeHolderPeriodoFechas: "Seleccione el Período de Fechas",
            placeHolderBuscador: "Escriba y pulse en el botón",
            sinRegistros: "No se encontraron registros",
            sinApuestas: "Aún no agregas Apuestas",
            sinAgregar: "Aún no agregas registros",
            loading: "Cargando...",
            anular: "ANULAR, significa que este registro solo tiene validez para AUDITORIA.",
        }
    }),
    actions: {
        // Puedes definir métodos aquí si necesitas realizar acciones con mutaciones en el estado
    },
    getters: {
        validaciones: (state) => ({
            campoRequerido: "Requerido",
            campoEmail: "Debe escribir un correo valido",
            numeroCero: "El valor debe ser mayor o igual a cero",
            numero99: "El valor debe ser de cero(0) a 99",
            numero9: "El valor debe ser de cero(0) a 9",
            numeroMayorCero: "El valor debe ser mayor a cero",
            campoUser: `El Nick debe tener mínimo ${state.app.minCharPass} caracteres`,
            campoPass: `La contraseña debe tener mínimo ${state.app.minCharPass} caracteres, con letras mayúsculas, minúsculas, símbolos y números.`,
        })
    },
});
