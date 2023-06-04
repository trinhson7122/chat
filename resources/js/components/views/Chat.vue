<template>
    <div v-if="!toUser" class="text-center w-100 overflow-hidden user-chat">
        <img src="../../../images/empty.jpg" class="img-fit">
    </div>
    <div v-else class="user-chat w-100 overflow-hidden" showroomslist="true">
        <Loading v-if="!loaded" />
        <div @click="hideEmojiPicker($event)" v-else class="d-lg-flex">
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
                                            {{ user.short_name }}</span>
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
                                            class="btn dropdown-toggle btn-white nav-btn" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul role="menu" tabindex="-1" class="dropdown-menu dropdown-menu-right">
                                            <li role="presentation" class="d-block user-profile-show">
                                                <a @click="showProfile = !showProfile" role="menuitem"
                                                    href="javascript:void(0);" class="dropdown-item">
                                                    Xem thông tin
                                                </a>
                                            </li>
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

                                        <Message v-for="chat in chats" :key="chat.id" :chat="chat" :toUser="toUser"
                                            :isMe="chat.from_user_id == $store.state.auth.user.id" />
                                        <WaitSendFileMessage :percent="percentSendFile" v-if="processSendFile" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simplebar-placeholder" style="width: auto; height: 248px;"></div>
                    </div>
                </div>
                <div class="room-footer"><!---->
                    <div class="align-items-center box-footer chat-input-section p-3 p-lg-4 border-top mb-0">
                        <!---->
                        <div v-show="fileName" class="file-container bg-light">
                            <div class="icon-file">
                                <i class="fa-solid fa-file-arrow-up"></i>
                            </div>
                            <div class="file-message">{{ fileName }}</div>
                            <div class="svg-button icon-remove">
                                <i @click="onRemoveFile" class="fa-solid fa-x"></i>
                            </div>
                        </div>
                        <textarea @keypress.enter.prevent="onSendMessage" v-model="message" placeholder="Type message"
                            rows="1" class="bg-light border-light rounded" :class="{ 'disable': fileName }">
                        </textarea>
                        <div class="icon-textarea"><!---->
                            <div>
                                <div>
                                    <!-- btn emoji -->
                                    <div class="svg-button">
                                        <button @click="showIconPicker = !showIconPicker" data-toggle="tooltip"
                                            data-placement="top" title="" data-original-title="Emoji" param=""
                                            class="emoji-btn text-primary">
                                            <i class="fa-regular fa-face-smile"></i>
                                        </button>
                                    </div> <!---->
                                    <EmojiPicker :display-recent="true" :native="true" @select="onSelectEmoji"
                                        v-show="showIconPicker" />
                                </div>
                            </div>
                            <!-- btn dinh kem file -->
                            <div class="svg-button mr-2">
                                <button @click="openFile" class="btn btn-link text-decoration-none font-size-16">
                                    <i class="fa-solid fa-paperclip"></i>
                                </button>
                            </div>
                            <form action="" id="form-file">
                                <input @change="onFileChange" type="file" id="message-file" name="file" style="display: none;">
                            </form>
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
            <div v-show="showProfile" id="profile-show show" class="user-profile-sidebar" style="display: block;">
                <div class="px-3 px-lg-4 pt-3 pt-lg-4">
                    <div class="user-chat-nav text-right">
                        <button @click="showProfile = false" type="button" id="user-profile-hide" class="btn nav-btn"><i
                                class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                <div class="text-center p-4 border-bottom">
                    <div class="mb-4 profile-user">
                        <div class="avatar-lg" v-if="!toUser.avatar">
                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{ toUser.short_name
                            }}</span>
                        </div>
                        <img v-else :src="toUser.avatar" alt="" class="img-cover rounded-circle avatar-lg img-thumbnail">
                    </div>
                    <h5 class="font-size-16 mb-1 text-truncate"> {{ toUser.name }} </h5>
                </div>
                <div data-simplebar="init" class="p-4 user-profile-desc">
                    <div class="simplebar-wrapper">
                        <div class="simplebar-height-auto-observer-wrapper">
                            <div class="simplebar-height-auto-observer"></div>
                        </div>
                        <div class="simplebar-mask">
                            <div class="simplebar-offset">
                                <div class="simplebar-content-wrapper">
                                    <div class="simplebar-content" style="padding: 24px;">
                                        <div id="profile-user-accordion" class="custom-accordion">
                                            <div class="card border custom-accordion"><!----><!---->
                                                <div class="card-header">
                                                    <a href="javascript: void(0);" class="not-collapsed"
                                                        aria-expanded="true" aria-controls="profileaccordion-1">
                                                        <h5 class="font-size-14 m-0 align-items-center">
                                                            <i
                                                                class="fa-solid fa-circle-info mr-2 align-middle d-inline-block"></i>
                                                            Thông tin
                                                            <i
                                                                class="fa-solid fa-angle-right float-right accor-plus-icon"></i>
                                                        </h5>
                                                    </a>
                                                </div>
                                                <div id="profileaccordion-1" class="collapse show">
                                                    <div class="card-body"><!----><!---->
                                                        <div>
                                                            <p class="text-muted mb-1"> Tên </p>
                                                            <h5 class="font-size-14"> {{ toUser.name }} </h5>
                                                        </div>
                                                        <div class="mt-4">
                                                            <p class="text-muted mb-1"> Email </p>
                                                            <h5 class="font-size-14"> {{ toUser.email }} </h5>
                                                        </div>
                                                    </div>
                                                </div><!----><!---->
                                            </div>
                                            <div class="card border custom-accordion"><!----><!---->
                                                <div class="card-header"><a href="javascript: void(0);" class="collapsed"
                                                        aria-expanded="false" data-toggle="collapse"
                                                        data-target="#profileaccordion-2"
                                                        aria-controls="profileaccordion-2">
                                                        <h5 class="font-size-14 m-0">
                                                            <i
                                                                class="fa-solid fa-paperclip mr-2 align-middle d-inline-block"></i>
                                                            <i
                                                                class="ri-attachment-line mr-2 align-middle d-inline-block"></i>
                                                            File đính kèm
                                                            <i
                                                                class="fa-solid fa-angle-right float-right accor-plus-icon"></i>
                                                        </h5>
                                                    </a></div>
                                                <div id="profileaccordion-2" class="collapse">
                                                    <div class="card-body"><!----><!---->
                                                        <div class="card p-2 border mb-2" v-for="chat_file in chat_files"
                                                            :key="chat_file.id">
                                                            <div class="media align-items-center">
                                                                <div class="avatar-sm mr-3">
                                                                    <div
                                                                        class="avatar-title bg-soft-primary text-primary rounded font-size-20">
                                                                        <i class="fa-solid fa-file"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="media-body">
                                                                    <div class="text-left">
                                                                        <h5 class="font-size-14 mb-1">
                                                                            {{ chat_file.message.split('|')[0] }}
                                                                        </h5>
                                                                        <p class="text-muted font-size-13 mb-0">
                                                                            {{ chat_file.message.split('|')[1] }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="ml-4">
                                                                    <ul class="list-inline mb-0 font-size-18">
                                                                        <li class="list-inline-item">
                                                                            <a target="_blank"
                                                                                :href="`api/download/${chat_file.list_message_id}/${chat_file.message.split('|')[0]}`"
                                                                                class="text-muted px-1">
                                                                                <i class="fa-solid fa-download"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!----><!---->
                                            </div>
                                            <div class="card border custom-accordion"><!----><!---->
                                                <div class="card-header"><a href="javascript: void(0);" class="collapsed"
                                                        data-toggle="collapse" data-target="#profileaccordion-3"
                                                        aria-expanded="false" aria-controls="profileaccordion-3">
                                                        <h5 class="font-size-14 m-0">
                                                            <i
                                                                class="fa-solid fa-paperclip mr-2 align-middle d-inline-block"></i>
                                                            <i
                                                                class="ri-attachment-line mr-2 align-middle d-inline-block"></i>
                                                            Hình ảnh
                                                            <i
                                                                class="fa-solid fa-angle-right float-right accor-plus-icon"></i>
                                                        </h5>
                                                    </a></div>
                                                <div id="profileaccordion-3" class="collapse">
                                                    <div class="card-body"><!----><!---->
                                                        <div class="card p-2 border mb-2" v-for="chat_image in chat_images"
                                                            :key="chat_image.id">
                                                            <div class="media align-items-center">
                                                                <div class="media-body">
                                                                    <div class="text-left">
                                                                        <img :src="chat_image.message" alt=""
                                                                            class="img-fluid">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!----><!---->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simplebar-placeholder" style="width: auto; height: 562px;"></div>
                    </div>
                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                    </div>
                    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                        <div class="simplebar-scrollbar"
                            style="height: 209px; display: block; transform: translate3d(0px, 0px, 0px);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { get_message, post_message, send_file } from "../../api.js";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";
import { mapActions } from "vuex";
import Loading from "@/components/Loading.vue";
import Message from "@/components/Message.vue";
import WaitSendFileMessage from "@/components/WaitSendFileMessage.vue";
export default {
    components: {
        Loading,
        EmojiPicker,
        Message,
        WaitSendFileMessage,
    },
    props: {
        toUser: Object,
        listMessage: Object,
    },
    data() {
        return {
            message: '',
            arr: [],
            loaded: true,
            showIconPicker: false,
            showProfile: false,
            fileName: "",
            processSendFile: false,
            percentSendFile: 0,
        };
    },
    computed: {
        user() {
            this.loaded = false;
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
        chat_files() {
            return this.$store.state.data.messages.filter((item) => {
                return item.is_file;
            }).sort((a, b) => {
                return new Date(b.created_at) - new Date(a.created_at);
            });
        },
        chat_images() {
            return this.$store.state.data.messages.filter((item) => {
                return item.is_image;
            }).sort((a, b) => {
                return new Date(b.created_at) - new Date(a.created_at);
            });
        },
    },
    updated() {
        console.log('updated');
        this.scrollBottom();
    },
    methods: {
        ...mapActions({
            unAuth: "auth/not_auth",
            set_messages: "data/set_messages",
            push_message: "data/push_message",
            update_list_message_with_me: "data/update_list_message_with_me",
            update_message: "data/update_message",
        }),
        onCloseChat() {
            this.$emit('onCloseChat');
        },
        async fetchMessages() {
            const param = {
                from_user_id: this.$store.state.auth.user.id,
            };
            if (this.listMessage.to_user_id) {
                param.to_user_id = this.toUser.id;
            }
            else {
                param.to_group_id = this.toUser.id;
            }
            try {
                const req = await axios.get(get_message, {
                    params: param,
                });
                await req.response;
                this.set_messages(req.data);
                this.loaded = true;
            }
            catch (error) {
                if (error.response.status === 401) {
                    this.unAuth();
                    console.log("Bạn chưa đăng nhập");
                }
            }
        },
        openFile() {
            const btn = document.querySelector('input#message-file');
            btn.click();
        },
        async onSendMessage() {
            if (document.querySelector('input[name="file"]').files.length != 0) {
                this.sendFile();
            }

            this.message = this.message.trim();
            const text = this.message;
            this.message = "";

            if (text == "") return;
            //alert(this.message);

            const form_data = {
                message: text,
                from_user_id: this.$store.state.auth.user.id,
                list_message_id: this.listMessage.id,
            };
            if (this.listMessage.to_user_id) {
                form_data.to_user_id = this.toUser.id;
            }
            else {
                form_data.to_group_id = this.toUser.id;
            }

            try {
                const req = await axios.post(post_message, form_data);
                await req.response;
                //update list message
                this.listMessage.last_message = text;
                this.listMessage.last_user_id_send = this.$store.state.auth.user.id;
                this.listMessage.updated_at = new Date().toISOString();
                this.update_list_message_with_me(this.listMessage);
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
        listenEvent() {
            if (this.arr.includes('my-channel-' + this.listMessage.id)) return;
            const self = this;
            Echo.channel('my-channel-' + this.listMessage.id).listen('.send-message', function (data) {
                console.log('push-message');
                self.push_message(data.message);
            });
            //
            Echo.channel('my-channel-' + this.listMessage.id).listen('.removed-message', function (data) {
                console.log('removed-message');
                self.update_message(data.message);
            });
            this.arr.push('my-channel-' + this.listMessage.id);
        },
        scrollBottom() {
            const last_e = this.$el.querySelectorAll('.list-unstyled');
            if (last_e.length > 0) {
                last_e[last_e.length - 1].scrollIntoView();
            }
        },
        hideEmojiPicker(e) {
            if (e.target.closest('.emoji-btn')) return;

            if (!this.showIconPicker) return;

            if (!e.target.closest('.v3-emoji-picker')) {
                this.showIconPicker = false;
            }
        },
        onSelectEmoji(emoji) {
            this.message += emoji.i;
        },
        async sendFile() {
            //this.processSendFile = true;
            const file = document.querySelector('input[name="file"]');
            if (!file.value) return;

            if (Math.round(file.files[0].size / 1024) > 30000) {
                alert("File không được lớn hơn 30MB");
                return;
            }
            const file_data = file.files[0];

            this.onRemoveFile();

            const config = {
                onUploadProgress: progressEvent => {
                    this.percentSendFile = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                    if (this.percentSendFile < 100) {
                        this.processSendFile = true;
                    }
                    else {
                        //this.processSendFile = false;
                        //this.percentSendFile = 0;
                    }
                }
            }

            try {
                const req = await axios.postForm(send_file, {
                    to_user_id: this.toUser.id,
                    from_user_id: this.$store.state.auth.user.id,
                    list_message_id: this.listMessage.id,
                    file: file_data,
                }, config);
                await req.response;
            }
            catch (error) {
                console.log(error);
                switch (error.response.status) {
                    case 401:
                        this.unAuth();
                        console.log("Bạn chưa đăng nhập");
                        break;
                    case 422:
                        console.log("Có lỗi xảy ra, vui lòng thử lại sau");
                        alert(error.response.data.message);
                        break;
                    case 500:
                        console.log("Có lỗi xảy ra, vui lòng thử lại sau");
                        break;
                }
            }
            this.processSendFile = false;
        },
        onFileChange() {
            const max_length = 10;
            const inputFile = document.querySelector('input[name="file"]');
            console.log(inputFile.files[0].type.split('/')[0]);
            if (inputFile.files[0].type.split('/')[0] == 'image') {
                this.fileName = 'Hình ảnh';
                return;
            };
            const arr = inputFile.files[0].name.split('.');
            const extension = arr[arr.length - 1];
            arr.pop();
            let file_name = arr.join('.');
            if (file_name.length > max_length) {
                file_name = file_name.substring(0, max_length) + '...';
            }
            this.fileName = file_name + '.' + extension;
        },
        onRemoveFile() {
            const inputFile = document.querySelector('input[name="file"]');
            this.fileName = "";
            inputFile.value = "";
        }
    },
};
</script>