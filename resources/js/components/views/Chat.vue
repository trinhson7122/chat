<template>
    <div v-if="!toUser" class="text-center w-100">
        <img src="../../../images/empty.jpg" class="img-fit">
    </div>
    <div v-else class="user-chat w-100 overflow-hidden" showroomslist="true">
        <div class="d-lg-flex">
            <div class="w-100 overflow-hidden position-relative">
                <div class="p-3 p-lg-4 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-sm-4 col-8">
                            <div class="media align-items-center">
                                <div class="d-block d-lg-none mr-2">
                                    <a @click="onCloseChat" href="javascript: void(0);"
                                        class="user-chat-remove text-muted font-size-16 p-2">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </a>
                                </div>
                                <div class="mr-3">
                                    <img v-if="user.avatar != null" :src="user.avatar" alt=""
                                        class="rounded-circle avatar-xs">
                                    <div v-else class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                            TD</span>
                                    </div>
                                </div>
                                <div class="media-body overflow-hidden">
                                    <h5 class="font-size-16 mb-0 text-truncate">
                                        <a href="javascript:void(0);" class="text-reset user-profile-show">
                                            {{ user.name }}
                                        </a>
                                        <i class="fa-solid fa-circle font-size-10 text-success d-inline-block ml-1"></i>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-4">
                            <ul class="list-inline user-chat-nav text-right mb-0">
                                <!-- <li class="list-inline-item d-none d-lg-inline-block">
                                    <button type="button" class="btn nav-btn">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </li> -->
                                <li class="list-inline-item">
                                    <div class="dropdown b-dropdown btn-group">
                                        <button aria-haspopup="true" aria-expanded="false" type="button"
                                            class="btn dropdown-toggle btn-white nav-btn">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul role="menu" tabindex="-1" class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="__BVID__45__BV_toggle_">
                                            <li role="presentation" class="d-block d-lg-none user-profile-show"><a
                                                    role="menuitem" href="javascript:void(0);" target="_self"
                                                    class="dropdown-item"> View
                                                    profile <i class="ri-user-2-line float-right text-muted"></i></a></li>
                                            <li role="presentation"><a role="menuitem" href="javascript:void(0);"
                                                    target="_self" class="dropdown-item"> Archive
                                                    <i class="ri-archive-line float-right text-muted"></i></a></li>
                                            <li role="presentation"><a role="menuitem" href="javascript:void(0);"
                                                    target="_self" class="dropdown-item"> Muted
                                                    <i class="ri-volume-mute-line float-right text-muted"></i></a></li>
                                            <li role="presentation"><a role="menuitem" href="javascript:void(0);"
                                                    target="_self" class="dropdown-item"> Delete
                                                    <i class="ri-delete-bin-line float-right text-muted"></i></a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="containerElement" class="chat-conversation p-3 p-lg-4" data-simplebar="init">
                    <div class="simplebar-wrapper" style="margin: -24px;">
                        <div class="simplebar-height-auto-observer-wrapper">
                            <div class="simplebar-height-auto-observer"></div>
                        </div>
                        <div class="simplebar-mask">
                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden;">
                                    <div class="simplebar-content" id="can-scroll" style="padding: 24px;">
                                        <div v-for="chat in chats" :key="chat.id">
                                            <ul class="list-unstyled mb-0" newmessages="">
                                                <li :class="{ 'right': chat.from_user_id == $store.state.auth.user.id }">
                                                    <div v-if="chat.from_user_id == $store.state.auth.user.id"
                                                        class="conversation-list message-highlight">
                                                        <div v-if="$store.state.auth.user.avatar" class="chat-avatar">
                                                            <img :src="$store.state.auth.user.avatar" alt="">
                                                        </div>
                                                        <div v-else class="avatar-xs chat-avatar">
                                                            <span
                                                                class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                TD</span>
                                                        </div>
                                                        <div class="user-chat-content">
                                                            <div class="ctext-wrap">
                                                                <div class="ctext-wrap-content">
                                                                    <div>
                                                                        <div class="">
                                                                            <div>
                                                                                <span target="_blank" class="">
                                                                                    {{ chat.message }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p class="chat-time mb-0 d-flex align-items-center">
                                                                        <i class="far fa-clock align-middle mr-1"></i>
                                                                        <span class="align-middle">10:00</span>
                                                                    </p>
                                                                </div>
                                                                <!-- <div class="text-timestamp text-secondary chat-time">
                                                                    <i class="far fa-clock align-middle mr-1"></i>
                                                                    <span class="align-middle">10:00</span>
                                                                </div> -->
                                                                <div class="dropdown b-dropdown align-self-start btn-group">
                                                                    <button aria-haspopup="true" aria-expanded="false"
                                                                        type="button" class="btn dropdown-toggle btn-white">
                                                                        <i class="fas fa-ellipsis-h"></i>
                                                                    </button>
                                                                    <ul role="menu" tabindex="-1" class="dropdown-menu"
                                                                        aria-labelledby="__BVID__57__BV_toggle_">
                                                                        <li role="presentation"><a role="menuitem" href="#"
                                                                                target="_self"
                                                                                class="dropdown-item">Reply</a></li>
                                                                        <li role="presentation"><a role="menuitem" href="#"
                                                                                target="_self"
                                                                                class="dropdown-item">Edit</a></li>
                                                                        <li role="presentation"><a role="menuitem" href="#"
                                                                                target="_self"
                                                                                class="dropdown-item">Delete</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="conversation-name">Doris Brown
                                                            </div> -->
                                                        </div>
                                                    </div><!---->
                                                    <div v-else class="conversation-list message-highlight">
                                                        <div v-if="toUser.avatar" class="chat-avatar">
                                                            <img :src="toUser.avatar" alt="">
                                                        </div>
                                                        <div v-else class="avatar-xs chat-avatar">
                                                            <span
                                                                class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                TD</span>
                                                        </div>
                                                        <div class="user-chat-content">
                                                            <div class="ctext-wrap">
                                                                <div class="ctext-wrap-content">
                                                                    <div>
                                                                        <div class="">
                                                                            <div>
                                                                                <span target="_blank" class="">
                                                                                    {{ chat.message }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p class="chat-time mb-0 d-flex align-items-center">
                                                                        <i class="far fa-clock align-middle mr-1"></i>
                                                                        <span class="align-middle">10:00</span>
                                                                    </p>
                                                                </div>
                                                                <!-- <div class="text-timestamp text-secondary chat-time">
                                                                    <i class="far fa-clock align-middle mr-1"></i>
                                                                    <span class="align-middle">10:00</span>
                                                                </div> -->
                                                                <div class="dropdown b-dropdown align-self-start btn-group">
                                                                    <button aria-haspopup="true" aria-expanded="false"
                                                                        type="button" class="btn dropdown-toggle btn-white">
                                                                        <i class="fas fa-ellipsis-h"></i>
                                                                    </button>
                                                                    <ul role="menu" tabindex="-1" class="dropdown-menu"
                                                                        aria-labelledby="__BVID__57__BV_toggle_">
                                                                        <li role="presentation"><a role="menuitem" href="#"
                                                                                target="_self"
                                                                                class="dropdown-item">Reply</a></li>
                                                                        <li role="presentation"><a role="menuitem" href="#"
                                                                                target="_self"
                                                                                class="dropdown-item">Edit</a></li>
                                                                        <li role="presentation"><a role="menuitem" href="#"
                                                                                target="_self"
                                                                                class="dropdown-item">Delete</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="conversation-name">Doris Brown
                                                            </div> -->
                                                        </div>
                                                    </div><!---->
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simplebar-placeholder" style="width: auto; height: 248px;"></div>
                    </div>
                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                    </div>
                    <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                        <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                    </div>
                </div>
                <div class="room-footer"><!---->
                    <div class="align-items-center box-footer chat-input-section p-3 p-lg-4 border-top mb-0">
                        <!---->
                        <textarea @keypress.enter="onSendMessage" v-model="message" placeholder="Type message" rows="1"
                            class="bg-light border-light rounded" style="min-height: 20px; padding-left: 12px;"></textarea>
                        <div class="icon-textarea"><!---->
                            <div>
                                <div>
                                    <!-- btn emoji -->
                                    <div class="svg-button">
                                        <button data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="Emoji" param="" class="emoji-btn text-primary">
                                            <i class="fa-regular fa-face-smile"></i>
                                        </button>
                                    </div> <!---->
                                </div>
                            </div>
                            <!-- btn dinh kem file -->
                            <div class="svg-button mr-2">
                                <button @click="openFile" class="btn btn-link text-decoration-none font-size-16">
                                    <i class="fa-solid fa-paperclip"></i>
                                </button>
                            </div>
                            <input type="file" style="display: none;">
                            <div>
                                <button @click="onSendMessage" type="button"
                                    class="btn btn-primary font-size-16 btn-lg chat-send">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions } from "vuex";
export default {
    props: {
        toUser: Object,
        listMessage: Object,
    },
    data() {
        return {
            message: '',
            arr: [],
        };
    },
    computed: {
        user() {
            this.fetchMessages();
            this.listenEvent();
            return this.toUser;
        },
        listMessageComp() {
            return this.listMessage;
        },
        chats() {
            return this.$store.state.data.messages;
        },
    },
    updated() {
        this.scrollBottom();
    },
    methods: {
        ...mapActions({
            unAuth: "auth/not_auth",
            set_messages: "data/set_messages",
            push_message: "data/push_message",
            update_list_message_with_me: "data/update_list_message_with_me",
        }),
        onCloseChat() {
            this.$emit('onCloseChat');
        },
        async fetchMessages() {
            const from_user_id = this.$store.state.auth.user.id;
            const to_user_id = this.toUser.id;
            try {
                const req = await axios.get('/api/fetchMessages', {
                    params: {
                        from_user_id: from_user_id,
                        to_user_id: to_user_id,
                    },
                });
                await req.response;
                this.set_messages(req.data);
            }
            catch (error) {
                if (error.response.status === 401) {
                    this.unAuth();
                    console.log("Bạn chưa đăng nhập");
                }
            }
        },
        openFile() {
            const btn = document.querySelector('input[type="file"]');
            btn.click();
        },
        async onSendMessage() {
            this.message = this.message.trim();
            if (this.message == "") return;
            //alert(this.message);
            try {
                const req = await axios.post('api/sendMessage', {
                    message: this.message,
                    to_user_id: this.toUser.id,
                    from_user_id: this.$store.state.auth.user.id,
                    list_message_id: this.listMessage.id,
                });
                await req.response;
                this.listMessage.last_message = this.message;
                this.listMessage.last_user_id_send = this.$store.state.auth.user.id;
                this.listMessage.updated_at = new Date().toISOString();
                this.update_list_message_with_me(this.listMessage);
            }
            catch (error) {
                console.log(error);
                if (error.response.status === 401) {
                    this.unAuth();
                    console.log("Bạn chưa đăng nhập");
                }
            }
            this.message = "";
        },
        listenEvent() {
             if (this.arr.includes('my-channel-' + this.listMessage.id)) return;
            const func = this.push_message;
            Echo.channel('my-channel-' + this.listMessage.id).listen('.send-message', function (data) {
                //this.push_message(data.message);
                console.log('push-message');
                func(data.message);
                //this.$store.dispatch('data/push_message', data.message);
            });
            this.arr.push('my-channel-' + this.listMessage.id);
        },
        scrollBottom() {
            const last_e = this.$el.querySelectorAll('.list-unstyled');
            if (last_e.length > 0) {
                last_e[last_e.length - 1].scrollIntoView();
            }
        }
    },
};
</script>