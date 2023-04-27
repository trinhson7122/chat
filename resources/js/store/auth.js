
import router from "@/router";

export default {
    namespaced: true,
    state: {
        authenticated: false,
        user: {},
    },
    mutations: {
        AUTHENTICATED(state, user) {
            state.authenticated = true;
            state.user = user;
        },
        UNAUTHENTICATED(state) {
            state.user = {};
            state.authenticated = false;
        },
    },
    actions: {
        login({ commit }) {
            const req = axios.get("/api/user");
            req.then((response) => {
                commit("AUTHENTICATED", response.data);
                router.push({ name: "dashboard-route" });
            });

            req.catch(({response}) => {
                //console.log(response.data.message);
                commit("UNAUTHENTICATED");
            });
        },
        logout({ commit }) {
            console.log("logout");
            window.localStorage.removeItem("VUEX-PERSISTEDSTATE");
            axios.get("/api/auth/logout");
            commit("UNAUTHENTICATED");
            router.push({ name: "login-route" });
        },
        not_auth({commit}) {
            commit("UNAUTHENTICATED");
            router.push({ name: "login-route" });
        }
    },
};
