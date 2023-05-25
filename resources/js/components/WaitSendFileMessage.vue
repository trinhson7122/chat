<template>
    <ul class="list-unstyled mb-0">
        <li class="right">
            <div class="conversation-list message-highlight align-items-center">
                <!--  -->
                <div v-if="$store.state.auth.user.avatar" class="chat-avatar">
                    <img :src="$store.state.auth.user.avatar" alt="" />
                </div>
                <div v-else class="avatar-xs chat-avatar">
                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                        {{ $store.state.auth.user.short_name }}
                    </span>
                </div>

                <div class="user-chat-content">

                    <div class="ctext-wrap">
                        <div class="ctext-wrap-content">
                            <div>
                                <div class="">
                                    <div>
                                        <span target="_blank" class="">
                                            Đang tải file lên...
                                        </span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" :style="`width: ${percentComputed}%`"
                                            :aria-valuenow="percentComputed" aria-valuemin="0" aria-valuemax="100">{{ percentComputed }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </li>
    </ul>
</template>
<script>
export default {
    props: {
        percent: Number
    },
    computed: {
        percentComputed() {
            return this.percent;
        },
    },
};
</script>