<template>
    <li class="list_user" @click="setActive"> <!-- class="active" -->
        <a href="javascript:void(0);">
            <div class="media align-items-center">
                <div class="chat-user-img online align-self-center mr-3 online">
                    <div v-if="toUser.avatar">
                        <img :src="toUser.avatar" alt="" class="avatar-1-1 rounded-circle avatar-xs">
                        <span class="user-status"></span>
                    </div>
                    <div v-else class="avatar-xs">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{ toUser.short_name }}</span>
                        <span class="user-status"></span>
                    </div>
                </div>
                <div class="media-body overflow-hidden">
                    <h5 class="text-truncate font-size-15 mb-1"> 
                        {{ toUser.name }}
                    </h5>
                    <p v-if="read" class="chat-user-message text-truncate mb-0"> 
                        {{ (user.last_user_id_send == this.$store.state.auth.user.id ) ? "Bạn: " : ""}}
                        {{ user.last_message }}
                    </p>
                    <p v-else class="chat-user-message text-truncate mb-0 fw-bold"> 
                       {{ user.last_message }}
                    </p>
                </div>
                <div v-show="!read" class="font-size-11"><i class="fa-solid fa-circle" style="color: #045df6;"></i></div>
            </div>
        </a>
    </li>
</template>
<script>
export default {
    props: {
        user: Object,
    },
    data() {
        return {
            toUser: null,
        };
    },
    computed: {
        read() {
            if (this.user.last_user_id_send != this.$store.state.auth.user.id) {
                if (this.user.from_user_id == this.$store.state.auth.user.id) {
                    return this.user.is_from_user_read;
                }
                return this.user.is_to_user_read;
            }
            return true;
        },
    },
    created() {
        if (this.user.from_user_id == this.$store.state.auth.user.id) {
            this.toUser = this.user.to_user;
        }
        else {
            this.toUser = this.user.from_user;
        }
        if (this.user.to_group_id) {
            this.toUser = this.user.to_group;
        }
    },
    methods: {
        setActive() {
            const list_users = document.querySelectorAll('li.list_user');
            list_users.forEach((item) => {
                item.classList.remove('active');
            });
            this.$el.classList.add('active');
        },
    },
};
</script>
  