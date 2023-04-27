<template>
    <li> <!-- class="active" -->
        <a href="javascript:void(0);">
            <div class="media">
                <div class="chat-user-img online align-self-center mr-3 online">
                    <div v-if="toUser.avatar">
                        <img :src="toUser.avatar" alt="" class="rounded-circle avatar-xs">
                        <span class="user-status"></span>
                    </div>
                    <div v-else class="avatar-xs">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary">TS</span>
                        <span class="user-status"></span>
                    </div>
                </div>
                <div class="media-body overflow-hidden">
                    <h5 class="text-truncate font-size-15 mb-1"> 
                        {{ toUser.name }}
                    </h5>
                    <p class="chat-user-message text-truncate mb-0"> 
                        {{ user.last_message }}
                    </p>
                </div>
                <!-- <div class="font-size-11">05 min</div> -->
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
    created() {
        if (this.user.from_user_id == this.$store.state.auth.user.id) {
            this.toUser = this.user.to_user;
        }
        else {
            this.toUser = this.user.from_user;
        }
    },
};
</script>
  