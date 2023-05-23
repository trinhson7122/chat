<template>
    <div class="side-menu flex-lg-column mr-lg-1">
        <div class="flex-lg-column my-auto">
            <ul role="tablist" class="nav nav-pills side-menu-nav justify-content-center">
                <li data-placement="top" title="Profile" class="nav-item">
                    <router-link :to="{ name: 'dashboard-route' }" data-toggle="tooltip" role="tab" class="nav-link">
                        <i class="fa-solid fa-comment"></i>
                    </router-link>
                </li>
                <li data-placement="top" title="Profile" class="nav-item">
                    <router-link :to="{ name: 'info-route' }" data-toggle="tooltip" role="tab" class="nav-link">
                        <i class="fa-solid fa-user"></i>
                    </router-link>
                </li>
                <div class="dropdown b-dropdown nav-item profile-user-dropdown d-inline-block d-lg-none dropup btn-group">
                    <button data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button"
                        class="btn dropdown-toggle btn-white nav-link justify-content-center align-items-center d-flex"
                        id="dropdown-user-menu1">
                        <img v-if="user.avatar" :src="user.avatar" alt="" class="profile-user rounded-circle">
                        <div v-else class="avatar-xs text-center">
                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{ user.short_name
                            }}</span>
                        </div>
                    </button>
                    <ul role="menu" tabindex="" class="dropdown-menu" aria-labelledby="dropdown-user-menu1">
                        <li role="presentation">
                            <a @click="onLogout" role="menuitem" href="javascript:void(0);" target="_self" class="dropdown-item align-items-center d-flex">
                                Đăng xuất <i class="fa-solid fa-right-from-bracket float-right text-muted ml-3"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
        <div class="flex-lg-column d-none d-lg-block">
            <ul class="nav side-menu-nav justify-content-center">
                <div class="dropdown nav-item btn-group dropup profile-user-dropdown btn-group">
                    <button data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button"
                        class="btn dropdown-toggle btn-white" id="dropdown-user-menu">
                        <img v-if="user.avatar != null" :src="user.avatar" alt="" class="profile-user rounded-circle">
                        <div v-else class="avatar-xs text-center">
                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{ user.short_name
                            }}</span>
                        </div>
                    </button>
                    <ul role="menu" tabindex="" class="dropdown-menu" aria-labelledby="dropdown-user-menu">
                        <li role="presentation">
                            <a @click="onLogout" role="menuitem" href="javascript:void(0);" target="_self" class="dropdown-item align-items-center d-flex">
                                Đăng xuất <i class="fa-solid fa-right-from-bracket float-right text-muted ml-3"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
    </div>
</template>
<script>
import { mapActions } from "vuex";
export default {
    data() {
        return {
            user: null,
        };
    },
    created() {
        this.user = this.$store.state.auth.user;
    },
    methods: {
        ...mapActions({
            logout: "auth/logout",
        }),
        onLogout() {
            this.logout();
        }
    }
};
</script>