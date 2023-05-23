import { createStore } from "vuex";
import auth from "@/store/auth.js";
import load from "@/store/load.js";
import data from "@/store/data.js";
import createPersistedState  from 'vuex-plugin-persistedstate';

const store = createStore({
    plugins:[
        createPersistedState(),
    ],
    modules: {
        auth,
        load,
        data,
    },
});

export default store;
