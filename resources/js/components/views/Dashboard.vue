<template>
    <Loading v-if="load" />
    <DefaultLayout v-else>
        <div class="chat-leftsidebar mr-lg-1">
            <div class="tab-content">
                <div id="pills-chat" role="tabpanel" aria-labelledby="pills-chat-tab"
                    class="tab-pane fade show active active">
                    <div ismobile="true">
                        <div class="px-4 pt-4">
                            <h4 class="mb-4">Tin nhắn</h4>
                            <div class="search-box chat-search-box">
                                <div class="input-group mb-3 bg-light input-group-lg rounded-lg">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-link text-muted pr-1 text-decoration-none">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    </div>
                                    <input type="text" placeholder="Search messages or users" class="form-control bg-light">
                                    <button title="Create Chat" type="button"
                                        class="btn text-decoration-none text-muted font-size-18 py-0 btn-link"
                                        data-bs-toggle="modal" data-bs-target="#add-chat-modal">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div dir="ltr" class="px-4 pb-4">
                            <Carousel :items-to-show="2.5" :wrap-around="false" :transition="500">
                                <Slide v-for="user in users" :key="user.id">
                                    <div class="carousel__item">
                                        <UserOnline :user="user" />
                                    </div>
                                </Slide>
                            </Carousel>
                        </div>
                        <div class="px-2">
                            <h5 class="mb-3 px-3 font-size-16"> Danh sách tin nhắn </h5>
                            <div id="chat-list" class="chat-message-list" data-simplebar="init">
                                <div class="simplebar-wrapper" style="margin: 0px;">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer"></div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                            <div class="simplebar-content-wrapper"
                                                style="height: 100%; overflow: hidden scroll;">
                                                <div class="simplebar-content" style="padding: 0px;">
                                                    <ul class="list-unstyled chat-list chat-user-list">
                                                        <SelectChat @click="showChatComp(item)"
                                                            v-for="item in listMessageWithMe" :key="item.id" :user="item" />
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="simplebar-placeholder" style="width: auto; height: 888px;"></div>
                                </div>
                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                </div>
                                <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                    <div class="simplebar-scrollbar"
                                        style="height: 135px; transform: translate3d(0px, 0px, 0px); display: block;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <AddChatModal :id="'exampleModal'" />
    </DefaultLayout>
    <ChatVue @onCloseChat="showChat = false" :listMessage="listMessage" :toUser="toUser"
        :class="{ 'user-chat-show': showChat }" />
</template>
<script>
import { mapActions } from "vuex";
import UserOnline from "@/components/UserOnline.vue";
import Loading from "@/components/Loading.vue";
import SelectChat from "@/components/SelectChat.vue";
import DefaultLayout from "@/components/layouts/Default.vue";
import AddChatModal from "@/components/modals/AddChat.vue";
import ChatVue from "@/components/views/Chat.vue";
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide } from 'vue3-carousel';
export default {
    components: {
        UserOnline,
        SelectChat,
        DefaultLayout,
        AddChatModal,
        ChatVue,
        Carousel,
        Slide,
        Loading,
    },
    data() {
        return {
            listMessage: null,
            loading: false,
            showChat: false,
            toUser: null,
        };
    },
    created() {
        if (!this.$store.state.data.load_all_data) {
            this.fetchListMessageWithMe();
            this.fectUserOnline();
            this.set_load_all_data(true);
        }
        this.listenEvent();
    },
    computed: {
        load() {
            return this.loading;
        },
        listMessageWithMe() {
            return this.$store.state.data.list_message_with_mes;
        },
        users() {
            return this.$store.state.data.user_onlines;
        },
    },
    methods: {
        ...mapActions({
            unAuth: "auth/not_auth",
            set_load_all_data: "data/set_load_all_data",
            set_list_message_with_mes: "data/set_list_message_with_mes",
            set_user_onlines: "data/set_user_onlines",
            update_list_message_with_me: "data/update_list_message_with_me",
        }),
        async fetchListMessageWithMe() {
            this.loading = true;
            try {
                const req = await axios.get('api/list-message-with-me');
                await req.response;
                this.set_list_message_with_mes(req.data);

                const item = req.data[0];
                this.listMessage = item;
                if (item.from_user_id == this.$store.state.auth.user.id) {
                    this.toUser = item.to_user;
                }
                else {
                    this.toUser = item.from_user;
                }
            }
            catch (error) {
                if (error.response.status === 401) {
                    this.unAuth();
                    console.log("Bạn chưa đăng nhập");
                }
            }
            this.loading = false;
        },
        async fectUserOnline() {
            this.loading = true;
            try {
                const req = await axios.get('api/user/all');
                await req.response;
                this.set_user_onlines(req.data);
            }
            catch (error) {
                if (error.response.status === 401) {
                    this.unAuth();
                    console.log("Bạn chưa đăng nhập");
                }
            }
            this.loading = false;
        },
        showChatComp(item) {
            this.listMessage = item;
            if (item.from_user_id == this.$store.state.auth.user.id) {
                this.toUser = item.to_user;
            }
            else {
                this.toUser = item.from_user;
            }
            this.showChat = true;
        },
        listenEvent() {
            const func1 = this.update_list_message_with_me;
            Echo.channel('user-channel-' + this.$store.state.auth.user.id).listen('.update-list-message', function (data) {
                //this.push_message(data.message);
                func1(data.list_message);
            });
        },
    },
};
</script>