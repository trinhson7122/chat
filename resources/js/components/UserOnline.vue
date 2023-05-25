<template>
    <div @click="addChat" tabindex="-1" aria-hidden="true" role="tabpanel" class="VueCarousel-slide owl-item">
        <div class="item">
            <a href="javascript:void(0);" class="user-status-box">
                <div v-if="user.avatar" class="avatar-xs mx-auto d-block chat-user-img online">
                    <img :src="user.avatar" alt="user-img" class="avatar-1-1 img-fit img-fluid rounded-circle">
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
import { store_list_message } from "../api.js";
import { mapActions } from "vuex";
export default {
    props: {
        user: Object,
    },
    methods: {
        ...mapActions({
            unAuth: "auth/not_auth",
            update_list_message_with_me: "data/update_list_message_with_me",
        }),
        async addChat() {
            try {
                const req = await axios.post(store_list_message, {
                    to_user_id: this.user.id,
                    from_user_id: this.$store.state.auth.user.id,
                });
                await req.response;

                this.update_list_message_with_me(req.data);
            }
            catch (error) {
                if (error.response.status === 401) {
                    this.unAuth();
                }
                console.log(error.response.data);
            }
        },
    },
};
</script>
  