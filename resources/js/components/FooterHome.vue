<template>
    <div class="side-menu flex-lg-column mr-lg-1">
        <!-- <div class="navbar-brand-box"><a href="/" aria-current="page"
                class="logo logo-dark router-link-exact-active router-link-active"><span class="logo-sm"><img
                        src="/img/logo.de6401ef.svg" alt="" height="30"></span></a><a href="/" aria-current="page"
                class="logo logo-light router-link-exact-active router-link-active"><span class="logo-sm"><img
                        src="/img/logo.de6401ef.svg" alt="" height="30"></span></a>
        </div> -->
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
                <li>
                    <div @click="onLogout">logout</div>
                </li>
                <div class="dropdown b-dropdown nav-item profile-user-dropdown d-inline-block d-lg-none dropup btn-group"
                    id="__BVID__37">
                    <button data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button"
                        class="btn dropdown-toggle btn-white nav-link justify-content-center align-items-center d-flex"
                        id="dd1">
                        <img v-if="user.avatar" :src="user.avatar" alt="" class="profile-user rounded-circle">
                        <div v-else class="avatar-xs text-center">
                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{ user.short_name
                            }}</span>
                        </div>
                    </button>
                    <ul role="menu" tabindex="-1" class="dropdown-menu" aria-labelledby="dd1" style="">
                        <li role="presentation"><a role="menuitem" href="javascript:void(0);" target="_self"
                                class="dropdown-item"> Profile <i class="ri-profile-line float-right text-muted"></i></a>
                        </li>
                        <li role="presentation"><a role="menuitem" href="javascript:void(0);" target="_self"
                                class="dropdown-item"> Settinga <i
                                    class="ri-settings-3-line float-right text-muted"></i></a>
                        </li>
                        <li role="presentation">
                            <hr role="separator" aria-orientation="horizontal" class="dropdown-divider">
                        </li>
                        <li role="presentation"><a role="menuitem" href="/logout" target="_self" class="dropdown-item"> Log
                                out <i class="ri-logout-circle-r-line float-right text-muted"></i></a></li>
                    </ul>
                </div>
            </ul>
        </div>
        <div class="flex-lg-column d-none d-lg-block">
            <ul class="nav side-menu-nav justify-content-center">
                <div class="dropdown nav-item btn-group dropup profile-user-dropdown btn-group">
                    <button data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button"
                        class="btn dropdown-toggle btn-white" id="dropdownMenuButton1">
                        <img v-if="user.avatar != null" :src="user.avatar" alt="" class="profile-user rounded-circle">
                        <div v-else class="avatar-xs text-center">
                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{ user.short_name
                            }}</span>
                        </div>
                    </button>
                    <ul role="menu" tabindex="" class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li role="presentation">
                            <a role="menuitem" href="javascript:void(0);" target="_self" class="dropdown-item"> Profile
                                <i class="ri-profile-line float-right text-muted"></i>
                            </a>
                        </li>
                        <li role="presentation">
                            <a role="menuitem" href="javascript:void(0);" target="_self" class="dropdown-item"> Setting
                                <i class="ri-settings-3-line float-right text-muted"></i>
                            </a>
                        </li>
                        <li role="presentation">
                            <a role="menuitem" href="javascript:void(0);" target="_self" class="dropdown-item">
                                Logout <i class="ri-logout-circle-r-line float-right text-muted"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
    </div>
</template>
<script>

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
        onLogout() {
            this.$store.dispatch('auth/logout');
        }
    }
};
</script>