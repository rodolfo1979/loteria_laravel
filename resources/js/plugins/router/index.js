import {createRouter, createWebHistory} from 'vue-router'
import {routes} from './routes'
import Trans from "@/utils/Trans.js";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
})

// VALIDAR QUE SIMEPRE TENGA LOGIN
router.beforeEach((to, from, next) => {
    //  If the next route is requires user to be Logged IN
    if (to.matched.some(m => m.meta.requiresAuth)) {
        return Trans.check().then(authenticated => {

            if (!authenticated && (to.name !== "Login")) {
                // manda a login principal
                return next({name: "Login"})
            }

            return next();
        })
    }
    return next();
});

export default function (app) {
    app.use(router)
}
export {router}
