<template>
    <!-- Modal -->
    <div class="modal fade" id="add-chat-modal" tabindex="-1" role="dialog" aria-labelledby="add-chat-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm đoạn chat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-4">
                            <label>Tên người nhận</label>
                            <input v-model="searchName" type="text" placeholder="Điền tên người nhận" class="form-control">
                        </div>
                    </form>
                    <div>
                        <div @click="addChat(user)" class="media align-items-center mb-3" v-for="user in usersShow" :key="user.id">
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-close-modal btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { store_list_message } from '../../api.js';
import { mapActions } from 'vuex';
export default {
    data() {
        return {
            searchName: "",
        };
    },
    props: {
        users: Array,
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
            return data;
        }
    },
    methods: {
        ...mapActions({
            unAuth: "auth/not_auth",
            update_list_message_with_me: "data/update_list_message_with_me",
        }),
        async addChat(user) {
            document.querySelector('#add-chat-modal .btn-close-modal').click();
            try {
                const req = await axios.post(store_list_message, {
                    to_user_id: user.id,
                    from_user_id: this.$store.state.auth.user.id,
                });
                await req.response;
                this.update_list_message_with_me(req.data);
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
    }
};
</script>