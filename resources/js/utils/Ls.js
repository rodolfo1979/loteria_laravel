export default {
    get(key) {
        return localStorage.getItem(key) ? localStorage.getItem(key) : null
    },
    set(key, val) {
        localStorage.setItem(key, val)
    },
    remove(key) {
        localStorage.removeItem(key)
    },
    setTienda(tienda) {
        this.set("tienda.tienda_id", tienda.tienda_id);
        this.set("tienda.logo", tienda.logo);
        this.set("tienda.color_distintivo", tienda.color_distintivo);
        this.set("tienda.nombre_comercial", tienda.nombre_comercial);
    },
    resetTienda() {
        this.remove("tienda.tienda_id");
        this.remove("tienda.logo");
        this.remove("tienda.color_distintivo");
        this.remove("tienda.nombre_comercial");
    },
}
