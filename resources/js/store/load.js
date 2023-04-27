
export default {
    namespaced: true,
    state: {
        isLoading: false,
    },
    mutations: {
        LOADED(state) {
            state.isLoading = false;
        },
        LOADING(state) {
            state.isLoading = true;
        },
    },
    actions: {
        LOADING({ commit }) {
            commit("LOADING");
        },
        LOADED({ commit }) {
            commit("LOADED");
        },
    },
};
