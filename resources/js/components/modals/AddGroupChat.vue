<template>
    <!-- Modal -->
    <div class="modal fade" id="add-group-chat-modal" tabindex="-1" role="dialog" aria-labelledby="add-chat-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo nhóm chát</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-4">
                            <label>Tên nhóm chát</label>
                            <input v-model="formData.name" type="text" placeholder="Điền tên nhóm chát"
                                class="form-control">
                        </div>
                        <div class="custom-file mb-3">
                            <input @change="onFileChange" type="file" accept="image/*" id="customFile"
                                class="custom-file-input" name="avatar">
                            <label id="label-customFile" class="custom-file-label" for="customFile">Chọn ảnh</label>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-5">
                                <img style="display: none;" src="" id="preview-avatar" alt=""
                                    class="img-fit avatar-1-1 rounded-circle img-fluid">
                            </div>
                        </div>
                    </form>
                    <div class="card">
                        <div class="form-group mb-4">
                            <input v-model="searchName" type="text" placeholder="Tìm kiếm theo tên" class="form-control">
                        </div>
                        <div class="media align-items-center mb-3" v-for="user in usersShow" :key="user.id">
                            <div class="chat-user-img online align-self-center mr-3 online">
                                <div v-if="user.avatar">
                                    <img :src="user.avatar" alt="" class="rounded-circle avatar-xs">
                                    <span class="user-status"></span>
                                </div>
                                <div v-else class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{
                                        user.short_name }}</span>
                                    <span class="user-status"></span>
                                </div>
                            </div>
                            <div class="media-body overflow-hidden">
                                <h5 class="text-truncate font-size-15 mb-1">{{ user.name }}</h5>
                            </div>
                            <div class="custom-control custom-switch">
                                <input :checked="formData.listUserId.includes(user.id)" v-model="formData.listUserId"
                                    :value="user.id" type="checkbox" class="custom-control-input"
                                    :id="`checkbox-${user.id}`">
                                <label class="custom-control-label" :for="`checkbox-${user.id}`"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-close-modal btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success" @click="onCreateGroupChat">Thêm</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions } from 'vuex';
export default {
    data() {
        return {
            formData: {
                name: "",
                avatar: null,
                user_id: null,
                listUserId: [],
            },
            searchName: "",
            errors: [],
        };
    },
    computed: {
        searchNameComp() {
            return this.searchName;
        },
        usersShow() {
            let data = this.users.slice(0, 5);
            if (this.searchName != "") {
                data = this.users.filter(user => user.name.toLowerCase().includes(this.searchName.toLowerCase()));
            }
            return data.filter((item) => item.id != this.$store.state.auth.user.id);
        }
    },
    props: {
        users: Array,
    },
    created() {
        this.formData.user_id = this.$store.state.auth.user.id;
    },
    methods: {
        ...mapActions({
            unAuth: "auth/not_auth",
            update_list_message_with_me: "data/update_list_message_with_me",
        }),
        onFileChange(e) {
            const file = e.target.files[0];
            const label = document.getElementById('label-customFile');
            const preview_avatar = document.getElementById('preview-avatar');
            label.innerHTML = file.name;

            const image = URL.createObjectURL(file);
            preview_avatar.src = image;
            preview_avatar.style.display = "block";

            this.formData.avatar = file;
        },
        async onCreateGroupChat() {
            try {
                const req = await axios.postForm("/api/createGroupMessage", this.formData);
                await req.response;
                this.errors = [];

                document.querySelector('#add-group-chat-modal .btn-close-modal').click();
                this.formData.name = "";
                this.formData.avatar = null;
                this.formData.listUserId = [];

                this.update_list_message_with_me(req.data);
            }
            catch (error) {
                console.log(error);
                switch (error.response.status) {
                    case 500:
                        console.log("Có lỗi xảy ra, vui lòng thử lại sau");
                        break;
                    case 422:
                        this.errors = error.response.data.errors;
                        break;
                    case 401:
                        this.unAuth();
                        console.log("Bạn chưa đăng nhập");
                        break;
                }
            }
        }
    },
};
</script>