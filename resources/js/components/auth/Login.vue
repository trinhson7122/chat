<template>
    <div class="account-pages my-5 pt-sm-5 w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center mb-4">
                        <a href="/" class="auth-logo mb-5 d-block router-link-active">
                            <h3 class="logo logo-dark">ChatApp</h3>
                        </a>
                        <h4>Đăng nhập</h4>
                        <p class="text-muted mb-4">
                            Đăng nhập để tiếp tục đoạn chat.
                        </p>
                    </div>
                    <div class="card">
                        <div class="card-body p-4">
                            <!---->
                            <div class="p-3">
                                <form>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <div class="input-group mb-3 bg-soft-light input-group-lg rounded-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text border-light text-muted">
                                                    <i class="fa-solid fa-envelope"></i>
                                                </span>
                                            </div>
                                            <input @keypress.enter="onLogin" v-model="auth.email" type="email" placeholder="Enter your email"
                                                class="form-control bg-soft-light border-light" /><!---->
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <div class="float-right">
                                            <a href="/forgot-password" class="text-muted font-size-13">Quên mật khẩu ?</a>
                                        </div>
                                        <label>Mật khẩu</label>
                                        <div class="input-group mb-3 bg-soft-light input-group-lg rounded-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text border-light text-muted">
                                                    <i class="fa-solid fa-lock"></i>
                                                </span>
                                            </div>
                                            <input @keypress.enter="onLogin" v-model="auth.password" type="password" placeholder="Enter your password"
                                                class="form-control bg-soft-light border-light" /><!---->
                                        </div>
                                    </div>
                                    <div>
                                        <div v-if="error.show" role="alert" aria-live="polite" aria-atomic="true"
                                            class="alert mt-3 alert-dismissible alert-danger"><button type="button"
                                                aria-label="Close" class="close">×</button>{{ error.message }}
                                        </div>
                                        <button @click="onLogin" type="button" class="btn btn-primary btn-block">
                                            {{ processing ? "Xin chờ..." : "Đăng nhập" }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>
                            Chưa có tài khoản ?
                            <router-link class="font-weight-medium text-primary" :to="{ name: 'register-route' }">Đăng kí
                                ngay</router-link>
                        </p>
                        <p> © 2023 ChatApp. Created by TXSon </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions } from "vuex";
import { login, csrf_cookie } from "../../api.js";
export default {
    data() {
        return {
            auth: {
                email: "",
                password: "",
            },
            validationErrors: {},
            error: {
                message: "",
                show: false,
            },
            processing: false,
        };
    },
    methods: {
        ...mapActions({
            signIn: "auth/login",
        }),
        async onLogin() {
            this.processing = true;
            try {
                await axios.get(csrf_cookie);
                const req = await axios.post(login, this.auth);
                await req.response;
                localStorage.setItem("token", req.data.string);
                window.axios.defaults.headers.common["Authorization"] =
                    localStorage.getItem("token") || "";
                this.signIn();
            }
            catch (error) {
                if (error.response.status === 422) {
                    this.validationErrors = error.response.data.errors;
                } else {
                    this.error.show = true;
                    this.error.message = error.response.data.message;
                    console.log(error.response.data.message);
                }
                console.log(error);
            }
            this.processing = false;
        },
    },
};
</script>
