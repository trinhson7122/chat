<template>
    <div>
        <div class="layout-wrapper d-lg-flex">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import { mapActions } from "vuex";
export default {
    created() {
        console.log('created app');
        this.set_load_all_data(false);
        this.listenEvent();
    },
    mounted() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },
    methods: {
        ...mapActions({
            set_load_all_data: "data/set_load_all_data",
            update_user_online: "data/update_user_online",
        }),
        listenEvent() {
            const func = this.update_user_online;
            Echo.channel('my-channel').listen('.update-user-online', function (data) {
                func(data.user);
            });
        },
    },
};
</script>