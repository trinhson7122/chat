<template>
    <div @click="addChat" tabindex="-1" aria-hidden="true" role="tabpanel" class="VueCarousel-slide owl-item">
        <div class="item">
            <a href="javascript:void(0);" class="user-status-box">
                <div v-if="user.avatar" class="avatar-xs mx-auto d-block chat-user-img online">
                    <img :src="user.avatar" alt="user-img" class="img-fluid rounded-circle">
                    <span class="user-status"></span>
                </div>
                <div v-else class="avatar-xs mx-auto chat-user-img online">
                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{ user.short_name }}</span>
                    <span class="user-status"></span>
                </div>
                <h5 class="font-size-13 text-truncate mt-3 mb-1"> {{ user.name }}
                </h5>
            </a>
        </div>
    </div>
</template>
  
<script>

export default {
    props: {
        user: Object,
    },
    methods: {
        addChat() {
            const req = window.axios.post('api/list-message', {
                to_user_id: this.user.id,
                from_user_id: this.$store.state.auth.user.id,
            });
        },
    },
};
</script>
  