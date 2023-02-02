
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { aliases, fa } from 'vuetify/iconsets/fa'
import { mdi } from 'vuetify/iconsets/mdi'
import '@mdi/font/css/materialdesignicons.css'
import colors from 'vuetify/lib/util/colors'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

export default createVuetify({
  ssr: true,
  components,
  directives,
  colors,
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      fa,
      mdi,
    }
  },
  theme: {
    dark: false,
    themes: {
      light: {
        primary: colors.grey.darken3,
        accent: colors.red.darken1,
        secondary: colors.amber.darken3,
        info: colors.teal.lighten1,
        warning: colors.amber.base,
        error: colors.deepOrange.accent4,
        success: colors.green.accent3
      },
      dark: {
        primary: colors.red.darken1,
        accent: colors.grey.darken3,
        secondary: colors.amber.darken3,
        info: colors.teal.lighten1,
        warning: colors.amber.base,
        error: colors.deepOrange.accent4,
        success: colors.green.accent3
      }
    }
  }
})