
export default {
    namespaced: true,
    state: {
        load_all_data: false,
        list_message_with_mes: [],
        messages: [],
        user_onlines: [],
    },
    mutations: {
        SET_LOAD_ALL_DATA(state, payload) {
            state.load_all_data = payload;
        },
        SET_LIST_MESSAGE_WITH_MES(state, payload) {
            state.list_message_with_mes = payload;
        },
        UPDATE_LIST_MESSAGE_WITH_ME(state, payload) {
            for (let i = 0; i < state.list_message_with_mes.length; i++) {
                if (state.list_message_with_mes[i].id == payload.id) {
                    state.list_message_with_mes[i] = payload;
                }
            }
        },
        SET_USER_ONLINES(state, payload) {
            state.user_onlines = payload;
        },
        SET_MESSAGES(state, payload) {
            state.messages = payload;
        },
        PUSH_MESSAGE(state, payload) {
            state.messages.push(payload);
        },
    },
    actions: {
        set_load_all_data({ commit }, payload) {
            commit('SET_LOAD_ALL_DATA', payload);
        },
        set_list_message_with_mes({ commit }, payload) {
            commit('SET_LIST_MESSAGE_WITH_MES', payload);
        },
        set_user_onlines({ commit }, payload) {
            commit('SET_USER_ONLINES', payload);
        },
        set_messages({ commit }, payload) {
            commit('SET_MESSAGES', payload);
        },
        push_message({ commit }, payload) {
            commit('PUSH_MESSAGE', payload);
        },
        update_list_message_with_me({ commit }, payload) {
            commit('UPDATE_LIST_MESSAGE_WITH_ME', payload);
        }
    },
};
