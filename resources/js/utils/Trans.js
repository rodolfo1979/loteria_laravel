import Ls from "./Ls.js"
import axios from "axios";
import Utils from "./Utils";

export default {
    async check() {
        let response = await axios.get("/auth/session_check");

        // TODO PENDIENTE APLICAR ROUTER PUSH
        if (!response.data.authenticated) {
            Ls.remove("token");
            Ls.remove("nombres");
            window.location.href = "/login";
        }

        return !!response.data.authenticated;
    },
    async checkPermisos(slug) {

        let params = {slug};
        let response = await axios.get("/permisos/check", {params});

        if (!Number(response.data.data[1])) {
            if (slug == "/") {
                Ls.remove("token");
                Ls.remove("nombres");
                window.location.href = "/login";
                alert("NO TIENES PERMISOS PARA ACCEDER")
            } else {
                window.location.href = "/";
            }
        }

        return response.data.data
    },
    async getPaginasChildrens(slug) {

        let params = {slug};
        let response = await axios.get("/permisos/childrens", {params});

        return response.data.data
    },
    getAppData(slug) {

        /*  // ADD PROPERTYS FOR STATE THAN USE IN ALL INSTANCE
          store.state.logo = slug + "/logo.png?" + Math.random();
          store.state.favicon = slug + "/favicon.png?" + Math.random();
          store.state.bgError = slug + "/bg_error.jpg?" + Math.random();
          store.state.bgLogin = slug + "/bg_login.jpg?" + Math.random();

          // SET FAVICON CUSTOM
          document.getElementById("favicon").href = store.state.favicon;
          // SET DOCUMENT TITLE
          document.title = store.state.appName;*/
    },
    print(urlPrint, params = {}) {
        if (urlPrint) {
            axios.get(urlPrint, {responseType: 'arraybuffer', params})
                .then(response => {
                    const blob = new Blob([response.data], {type: 'application/pdf'});
                    const pdfURL = window.URL.createObjectURL(blob);

                    if (Utils.isMobile()) {
                        window.open(pdfURL);
                    } else {
                        let iframe = document.createElement('iframe');
                        iframe.style.display = 'none';
                        iframe.src = pdfURL;

                        iframe.onload = function () {
                            iframe.contentWindow.print();
                        };
                        document.body.appendChild(iframe);
                    }

                }).catch(error => {
                console.error(error);
                alert("No se pudo generar");
            }).finally(() => {
                // do
            })
        } else {
            alert("No esta enviando el parametro para Imprimir");
        }
    },
    export(urlExport, params = {}, nombre = "Archivo.xlsx") {
        if (urlExport) {
            axios.get(urlExport, {responseType: 'arraybuffer', params})
                .then(response => {
                    // Crear un Blob con la respuesta del servidor
                    const blob = new Blob([response.data], {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = nombre;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                }).catch(error => {
                console.error(error);
                alert("No se pudo generar");
            }).finally(() => {
            })
        } else {
            alert("No esta enviando el parametro para Exportar");
        }
    },
    async logout() {
        await axios.post("/auth/logout",)
            .then(response => {
                if (response.data.success) {
                    // REMOVE
                    Ls.remove("token");
                    Ls.remove("nombres");
                    Ls.remove("foto");
                } else {
                    console.log(response);
                    alert(esponse.data.message);
                }
            }).catch(error => {
                console.log(error)
                alert("Error para salir.");
            })
    },

}
