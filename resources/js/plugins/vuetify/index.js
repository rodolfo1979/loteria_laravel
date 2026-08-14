import {createVuetify} from 'vuetify'
import {VBtn} from 'vuetify/components/VBtn'
import defaults from './defaults'
import {themes} from './theme'

// Styles
import '@core-scss/template/libs/vuetify/index.scss'
import "@mdi/font/css/materialdesignicons.css"
import 'vuetify/styles'

export default function (app) {
    const vuetify = createVuetify({
        aliases: {
            IconBtn: VBtn,
        },
        defaults,
        theme: {
            defaultTheme: 'light',
            themes,
        },
        icons: {
            iconfont: "mdi",
        },
    })

    app.use(vuetify)
}
