<template>
    <ul class="list-unstyled mb-0" newmessages="">
        <li :class="{ 'right': isMe }">
            <div class="conversation-list message-highlight align-items-center">
                <!--  -->
                <div v-if="$store.state.auth.user.avatar && chat.from_user_id == $store.state.auth.user.id"
                    class="chat-avatar">
                    <img :src="$store.state.auth.user.avatar" alt="" />
                </div>
                <div v-else-if="chat.from_user_id == $store.state.auth.user.id" class="avatar-xs chat-avatar">
                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                        {{ $store.state.auth.user.short_name }}
                    </span>
                </div>
                <div v-else-if="toUser.avatar && chat.from_user_id != $store.state.auth.user.id" class="chat-avatar">
                    <img :src="toUser.avatar" alt="" />
                </div>
                <div v-else-if="chat.from_user_id != $store.state.auth.user.id" class="avatar-xs chat-avatar">
                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                        {{ toUser.short_name }}</span>
                </div>
                <div class="user-chat-content">
                    <div v-if="chat.is_removed" class="ctext-wrap">
                        <div class="ctext-wrap-content removed-message">
                            <div class="">
                                <div v-if="chat.from_user_id != $store.state.auth.user.id">
                                    <span target="_blank" class="">
                                        {{ toUser.name }} đã thu hồi một tin nhắn
                                    </span>
                                </div>
                                <div v-else>
                                    <span target="_blank" class="">
                                        Bạn thu hồi một tin nhắn
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="ctext-wrap">
                        <div class="ctext-wrap-content" :class="{ 'chat-image': chat.is_image }">
                            <div v-if="chat.is_file" class="">
                                <div class="card p-2 mb-2">
                                    <div class="media align-items-center">
                                        <div class="avatar-sm mr-3">
                                            <div class="avatar-title bg-soft-primary text-primary rounded font-size-20">
                                                <i class="fa-solid fa-file"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <div class="text-left">
                                                <h5 class="font-size-14 mb-1">{{ chat.message.split('|')[0] }}</h5>
                                                <p class="text-muted font-size-13 mb-0">{{ chat.message.split('|')[1] }}</p>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <ul class="list-inline mb-0 font-size-20">
                                                <li class="list-inline-item">
                                                    <a target="_blank"
                                                        :href="`api/download/${chat.list_message_id}/${chat.message.split('|')[0]}`"
                                                        class="text-muted">
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="chat.is_image">
                                <div class="">
                                    <div>
                                        <!-- <span target="_blank" class="">
                                            {{ chat.message }}
                                        </span> -->
                                        <img :src="chat.message" alt="" class="img-fluid img-thumbnail">
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="">
                                    <div>
                                        <span target="_blank" class="">
                                            {{ chat.message }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="chat.from_user_id == $store.state.auth.user.id"
                            class="dropdown align-self-start btn-group mr-1">
                            <i class="fas fa-ellipsis-h dropdown-toggle" data-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">
                                <li>
                                    <a @click="onRemoved(chat)" href="#" target="_self" class="dropdown-item">Thu hồi tin
                                        nhắn</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div v-show="!chat.is_removed" class="conversation-name text-secondary">
                        <i class="far fa-clock align-middle mr-1"></i>
                        {{ chat.created_at_for_humans }}
                    </div>
                </div>
            </div>
        </li>
    </ul>
</template>
<script>
import { mapActions } from 'vuex';
import { remove_message } from "../api.js";
export default {
    props: {
        chat: Object,
        toUser: Object,
        isMe: Boolean,
        processSendFile: Boolean,
    },
    methods: {
        ...mapActions({
            update_message: "data/update_message",
            unAuth: "auth/not_auth",
        }),
        async onRemoved(chat) {
            try {
                const req = await axios.post(remove_message, {
                    message_id: chat.id,
                });
                await req.response;

                chat.is_removed = 1;
                this.update_message(chat);
            }
            catch (error) {
                console.log(error);
                switch (error.response.status) {
                    case 401:
                        this.unAuth();
                        console.log("Bạn chưa đăng nhập");
                        break;
                    case 500:
                        console.log("Có lỗi xảy ra, vui lòng thử lại sau");
                        break;
                }
            }
        },
    },
};
</script>
