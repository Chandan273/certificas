import {createApp} from 'vue';
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";
import SecureLS from "secure-ls";
const ls = new SecureLS({ isCompression: false });
Vue.use(Vuex)
import certificas from './modules/certificas';

const persistedstatePlugin = createPersistedState({
    storage: {
      getItem: key => ls.get(key),
      setItem: (key, value) => ls.set(key, value),
      removeItem: key => ls.remove(key)
    }
  })
const store = new Vuex.Store({
  state:{
  },
  modules:{
    certificas
  },
  plugins: [
  ],
})

export default store;
