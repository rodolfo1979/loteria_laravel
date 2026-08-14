import 'moment-timezone';
import moment from 'moment';

moment.tz.setDefault('America/Managua');
export default {
    fechaESP: (fecha) => {
        let fechaFormato = "";
        if (fecha) {
            const options = {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            };
            fechaFormato = new Date(fecha.toString().replace(/-/g, '/')).toLocaleDateString('es-NI', options);
        }

        return fechaFormato;
    },
    fechaHoraESP: (fechaHora) => {

        const options = {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        return (fechaHora) ? new Date(fechaHora.toString().replace(/-/g, '/')).toLocaleDateString("es-NI", options) : "Pendiente";

    },
    horaESP: (hora) => {
        const horaMom = moment(hora, 'HH:mm');
        return horaMom.format('hh:mm A');
    },
    moneda: (numero) => {
        if (numero == null) {
            return 0;
        }

        const options = {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        };
        return Number(numero).toLocaleString('en-US', options);
    },
    numero: (numero) => {
        if (numero == null) {
            return "";
        }
        return Number(numero).toLocaleString('en-US');
    },
    monedaMiles: (numero) => {
        if (numero == null) {
            return "";
        }

        let options = {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        };
        return Number(numero / 1000).toLocaleString('en-US', options);
    },
    porcentaje: (numero) => {
        if (numero == null) {
            return "";
        }

        let options = {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        };
        return Number(numero).toLocaleString('en-US', options) + '%';

    },
    trueSi: (valor) => {
        let respuesta = "NO";
        if (valor) {
            respuesta = "SI";
        }
        return respuesta;
    },
    trueActivo: (valor) => {
        let respuesta = "A N U L A D O";
        if (valor) {
            respuesta = "ACTIVO";
        }
        return respuesta;
    },
    trueEntrada: (valor) => {
        let respuesta = "NEUTRO";
        if (Number(valor) == 1) {
            respuesta = "ENTRADA";
        } else if (parseInt(valor) == 0) {
            respuesta = "SALIDAD";
        }

        return respuesta;
    },
    trueAbierto: (valor) => {
        let respuesta = "CERRADO";
        if (valor) {
            respuesta = "ABIERTO";
        }
        return respuesta;
    },
    nullVacio: (valor) => {
        if (valor === null || valor === "" || valor === "null") {
            return "(Vacío)";
        }
        return valor;
    },
    nullPendiente: (valor) => {
        if (valor == null) {
            return "(Pendiente)";
        }
        return valor;
    },
    nullTodos: (valor) => {
        if (valor == null) {
            return "TODOS";
        }
        return valor;
    },

}
