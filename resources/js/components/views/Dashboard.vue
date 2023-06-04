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
                                    <input v-model="searchName" type="text" placeholder="Tìm kiếm người đã nhắn"
                                        class="form-control bg-light">
                                    <button title="Tạo đoạn chat cá nhân" type="button"
                                        class="btn text-decoration-none text-muted font-size-18 py-0 btn-link"
                                        data-toggle="modal" data-target="#add-chat-modal">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </button>
                                    <button title="Tạo đoạn chat nhóm" type="button"
                                        class="btn text-decoration-none text-muted font-size-18 py-0 btn-link"
                                        data-toggle="modal" data-target="#add-group-chat-modal">
                                        <i class="fa-solid fa-users"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div dir="ltr" class="px-4 pb-4">
                            <Carousel :items-to-show="2.5" :wrap-around="false" :transition="500">
                                <Slide v-for="user in users" :key="user.id">
                                    <div class="carousel__item w-100">
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
        <AddChatModal :users="allUserComp" />
        <AddGroupChatModal :users="allUserComp" />
    </DefaultLayout>
    <ChatVue @onCloseChat="showChat = false" :listMessage="listMessage" :toUser="toUser"
        :class="{ 'user-chat-show': showChat }" />
</template>
<script>
import { get_list_message_with_me, get_user_online, fetch_user } from "../../api.js";
import { mapActions } from "vuex";
import UserOnline from "@/components/UserOnline.vue";
import Loading from "@/components/Loading.vue";
import SelectChat from "@/components/SelectChat.vue";
import DefaultLayout from "@/components/layouts/Default.vue";
import AddChatModal from "@/components/modals/AddChat.vue";
import AddGroupChatModal from "@/components/modals/AddGroupChat.vue";
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
        AddGroupChatModal,
    },
    data() {
        return {
            listMessage: null,
            loading: false,
            showChat: false,
            searchName: "",
            allUser: [],
            fromUser: null,
        };
    },
    created() {
        if (!this.$store.state.data.load_all_data) {
            this.fetchListMessageWithMe();
            this.fectUserOnline();
            this.set_load_all_data(true);
        }
        if (this.listMessageWithMe.length > 0) {
            this.listMessage = this.listMessageWithMe[0];
        }
        this.listenEvent();
        this.fetchUser();
    },
    computed: {
        load() {
            return this.loading;
        },
        listMessageWithMe() {
            if (this.$store.state.data.list_message_with_mes.length <= 0) return [];

            let data = this.$store.state.data.list_message_with_mes.sort((a, b) => {
                return new Date(b.updated_at) - new Date(a.updated_at);
            });

            if (this.searchName != "") {

                data = data.filter((item) => {
                    if (item.to_user_id) {
                        if (item.to_user.name.toLowerCase().includes(this.searchName.toLowerCase())) {
                            return 1;
                        }
                    }
                    if (item.to_group_id) {
                        if (item.to_group.name.toLowerCase().includes(this.searchName.toLowerCase())) {
                            return 1;
                        }
                    }

                    if (item.from_user.name.toLowerCase().includes(this.searchName.toLowerCase())) {
                        return 1;
                    }
                    return 0;
                });
            }
            return data;
        },
        users() {
            return this.$store.state.data.user_onlines.sort((a, b) => {
                return new Date(b.last_online_at) - new Date(a.last_online_at);
            });
        },
        toUser() {
            if (!this.listMessage) return null;
            if (this.listMessage.to_group) {
                return this.listMessage.to_group;
            }
            if (this.listMessage.from_user_id == this.$store.state.auth.user.id) {
                return this.listMessage.to_user;
            }
            else {
                return this.listMessage.from_user;
            }
        },
        searchNameComp() {
            //this.listMessageWithMe();
            return this.searchName;
        },
        allUserComp() {
            return this.allUser;
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
                const req = await axios.get(get_list_message_with_me);
                await req.response;
                this.set_list_message_with_mes(req.data);

                this.listMessage = req.data[0];
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
                const req = await axios.get(get_user_online);
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
            this.showChat = true;

            if (item.last_user_id_send != this.$store.state.auth.user.id) {
                if (item.from_user_id == this.$store.state.auth.user.id) {
                    item.is_from_user_read = 1;
                }
                else {
                    item.is_to_user_read = 1;
                }
                this.update_list_message_with_me(item);
                //goi api
            }
        },
        listenEvent() {
            const func1 = this.update_list_message_with_me;
            Echo.channel('user-channel-' + this.$store.state.auth.user.id).listen('.update-list-message', function (data) {
                //this.push_message(data.message);
                func1(data.list_message);
            });
        },
        async fetchUser() {
            try {
                const req = await axios.get(fetch_user);
                await req.response;
                this.allUser = req.data;
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