<template>
    <DefaultLayout>
        <div class="chat-leftsidebar mr-lg-1">
            <div class="tab-content"><!----><!----><!----><!---->
                <div id="pills-setting" role="tabpanel" aria-labelledby="pills-setting-tab" class="tab-pane active">
                    <div>
                        <div class="px-4 pt-4">
                            <h4 class="mb-0">Cá nhân</h4>
                        </div>
                        <div class="text-center border-bottom p-4">
                            <div class="mb-4 profile-user">
                                <div class="avatar-lg" v-if="!user.avatar">
                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{
                                        user.short_name }}</span>
                                </div>
                                <img v-else :src="user.avatar" alt=""
                                    class="img-cover rounded-circle avatar-lg img-thumbnail">
                                <button @click="onSelectAvatar" type="button"
                                    class="btn bg-light avatar-xs p-0 rounded-circle profile-photo-edit">
                                    <i class="fa-solid fa-pen"></i>
                                    <input @change="onFileChange" accept="image/*" type="file" id="select-avatar" style="display: none">
                                </button>
                            </div><!---->
                            <h5 class="font-size-16 mb-1 text-truncate">{{ user.name }}</h5>
                        </div>
                        <div class="p-4 user-profile-desc" data-simplebar="init">
                            <div class="simplebar-wrapper" style="margin: -24px;">
                                <div class="simplebar-height-auto-observer-wrapper">
                                    <div class="simplebar-height-auto-observer"></div>
                                </div>
                                <div class="simplebar-mask">
                                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                        <div class="simplebar-content-wrapper"
                                            style="height: 100%; overflow: hidden scroll;">
                                            <div class="simplebar-content" style="padding: 24px;">
                                                <div class="card custom-accordion border"><!----><!---->
                                                    <div role="tab" class="card-header">
                                                        <a href="javascript: void(0);" class="text-dark not-collapsed"
                                                            aria-expanded="true" data-bs-toggle="collapse"
                                                            data-bs-target="#accordion-1" aria-controls="accordion-1"
                                                            style="overflow-anchor: none;">
                                                            <h5 class="font-size-14 m-0"> Thông tin cá nhân
                                                                <i
                                                                    class="fa-solid fa-angle-right float-right accor-plus-icon"></i>
                                                            </h5>
                                                        </a>
                                                    </div>
                                                    <div id="accordion-1" class="collapse show" style="">
                                                        <div class="card-body"><!----><!---->
                                                            <div class="float-right">
                                                                <button data-toggle="modal"
                                                                    data-target="#edit-profile-modal" type="button"
                                                                    class="btn btn-light btn-sm">
                                                                    <i class="fa-solid fa-pen-to-square"></i> Sửa
                                                                </button>
                                                            </div>
                                                            <div>
                                                                <p class="text-muted mb-1"> Họ và tên </p>
                                                                <h5 class="font-size-14">{{ user.name }}</h5>
                                                            </div>
                                                            <div class="mt-4">
                                                                <p class="text-muted mb-1"> Email </p>
                                                                <h5 class="font-size-14">{{ user.email }}</h5>
                                                            </div>
                                                        </div>
                                                    </div><!----><!---->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="simplebar-placeholder" style="width: auto; height: 608px;"></div>
                            </div>
                            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                            </div>
                            <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                <div class="simplebar-scrollbar"
                                    style="height: 281px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="processUpload" class="container">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" :style="`width: ${perCent}%`" :aria-valuenow="perCent"
                            aria-valuemin="0" aria-valuemax="100">{{ perCent }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="edit-profile-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Sửa thông tin cá nhân</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="">Họ và tên</label>
                                <input type="text" class="form-control" v-model="dataModel.name">
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" class="form-control" v-model="dataModel.email">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-close-modal btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button @click="updateUser" type="button" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
  
<script>
import DefaultLayout from "@/components/layouts/Default.vue";
import { put_user, upload_avatar } from "../../api.js"
import { mapActions } from "vuex";
export default {
    components: {
        DefaultLayout,
    },
    data() {
        return {
            dataModel: {
                name: "",
                email: "",
            },
            processUpload: false,
            perCent: 0.
        };
    },
    created() {
        this.dataModel.name = this.user.name;
        this.dataModel.email = this.user.email;
    },
    computed: {
        user() {
            return this.$store.state.auth.user;
        },
    },
    methods: {
        ...mapActions({
            unAuth: "auth/not_auth",
        }),
        async updateUser() {
            try {
                const req = await axios.put(put_user + this.user.id, this.dataModel);
                await req.response;
                this.$store.state.auth.user = req.data;
                document.querySelector('#edit-profile-modal .btn-close-modal').click();
            }
            catch (error) {
                if (error.response.status === 401) {
                    this.unAuth();
                }
                console.log(error.response.data);
            }
        },
        onSelectAvatar() {
            document.querySelector('#select-avatar').click();
        },
        async uploadAvatar() {
            const file = document.querySelector('#select-avatar');
            const config = {
                onUploadProgress: progressEvent => {
                    this.perCent = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                    if (this.perCent < 100) {
                        this.processUpload = true;
                    }
                    else {
                        this.processUpload = false;
                        this.perCent = 0;
                    }
                }
            }
            try {
                const req = await axios.postForm(upload_avatar, {
                    avatar: file.files[0],
                    user_id: this.$store.state.auth.user.id,
                }, config);
                await req.response;
                this.$store.state.auth.user.avatar = req.data.avatar;
            }
            catch (error) {
                if (error.response.status === 401) {
                    this.unAuth();
                }
                console.log(error.response.data);
                alert(error.response.data.message);
            }
        },
        onFileChange() {
            this.uploadAvatar();
        }
    },
};
</script>