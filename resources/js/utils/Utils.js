export default {
    colores() {
        return ['#91B321', '#138f8d', '#F2711F', '#D61355', '#F94A29', '#F9D923', '#379237', '#060047', '#FF0303', '#5D3891', '#84bd00']
    },
    mesesShort() {
        return [
            "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"
        ];
    },
    mesesNormal() {
        return [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];
    },
    diasShort() {
        return [
            "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"
        ];
    },
    diasNormal() {
        return [
            "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"
        ];
    },
    customRangesPicker() {
        let hoy = new Date();
        return {
            "Hoy": [hoy, hoy],
            "En 7 días": [hoy, new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate() + 7)],
            "En 30 días": [hoy, new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate() + 30)],
            "Este mes": [new Date(hoy.getFullYear(), hoy.getMonth(), 1), new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0)],
            "Este año": [new Date(hoy.getFullYear(), 0, 1), new Date(hoy.getFullYear(), 11, 31, 11, 59, 59, 999)],
        }
    },
    initialRangePicker() {
        // llamar al custom ranges
        let range = this.customRangesPicker()["En 7 días"];
        return {
            startDate: range[0],
            endDate: range[1]
        }
    },
    limpiarArrayDuplicados(array, key) {
        let validador = {};
        return array.filter(obj => validador[obj[key]] ? false : validador[obj[key]] = true);
    },
    exportarHTMLtoExcel: function (contenedorReporte, nombre) {
        let hoy = new Date().toLocaleDateString();

        let uri = 'data:application/vnd.ms-excel;charset=UTF-8;base64,',
            template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta charset="UTF-8"></head><body><table>{table}</table></body></html>',
            base64 = function (s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            },
            format = function (s, c) {
                return s.replace(/{(\w+)}/g, function (m, p) {
                    return c[p];
                })
            };

        let ctx = {
            worksheet: nombre,
            table: contenedorReporte
        };

        let link = document.createElement("a");
        link.download = `${nombre}_${hoy}.xls`;
        link.href = uri + base64(format(template, ctx));
        link.dispatchEvent(new MouseEvent('click'));
    },
    espaciosEntreMayusculas(texto) {
        return texto.replace(/([A-Z])/g, ' $1').trim();
    },
    obtenerTextoAntesDelCaracter(texto, char) {
        const indice = texto.indexOf(char);
        if (indice !== -1) {
            return texto.substring(0, indice).trim();
        }
        return '';
    },
    round(number, decimals = 2) {
        return Number(number).toFixed(decimals);
    },
    fechaESP(fecha) {
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
    isMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    },
    calcUtilidadPorcentaje(costo, precio, decimal = 2) {
        return (((Number(precio) - Number(costo)) / Number(precio)) * 100).toFixed(decimal);
    },
    calcularPrecioVenta(costo, utilidadPorc, decimal = 0) {
        let realUtilidad = Number(1) -  (Number(utilidadPorc) / 100);
        return (Number(costo) / realUtilidad).toFixed(decimal);
    },
    /// PROCESSING DATA
    calcularDescuento(precio, tipoDescuentoValor, catTipoDescuentoId) {
        let descuentoCalculado = 0;
        let descuentoPorcentaje = 0;
        if (Number(tipoDescuentoValor) > 0) {
            ////si el descuentoPorcentaje es mayor
            if (Number(catTipoDescuentoId) === 8) {
                ///si es porcentaje calculamos el monetario
                descuentoPorcentaje = tipoDescuentoValor;
                descuentoCalculado = (Number(precio) * Number(tipoDescuentoValor / 100)).toFixed(2);
            } else if (Number(catTipoDescuentoId) === 7) {
                //// si es monetario calculamos el porcentaje
                descuentoCalculado = tipoDescuentoValor;
                // La siguiente linea debemos de saber como saber hacerlo
                descuentoPorcentaje = ((Number(tipoDescuentoValor) / Number(precio)) * 100).toFixed(2);
            }
        }
        return [descuentoCalculado, descuentoPorcentaje];
    },
    calcularSubTotal(cantidad, precio, descuento_calculado) {
        return Number(Number(cantidad) * (Number(precio) - Number(descuento_calculado))).toFixed(2);
    },
    getUrlPart(url) {
        // Dividir la URL en segmentos usando el caracter '/'
        const string = url.split('/');

        // Devolver el primer segmento junto con el '/'
        return '/' + string[1];
    },
}
