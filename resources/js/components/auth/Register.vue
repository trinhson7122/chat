<template>
    <div class="account-pages my-5 pt-sm-5 w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center mb-4">
                        <a href="/" class="auth-logo mb-5 d-block router-link-active">
                            <h3 class="logo logo-dark">ChatApp</h3>
                        </a>
                        <h4>Đăng ký</h4>
                        <p class="text-muted mb-4">
                            Nhận tài khoản ChatApp của bạn ngay bây giờ.
                        </p>
                    </div>
                    <div class="card">
                        <div class="card-body p-4">
                            <div v-if="alert.show" role="alert" aria-live="polite" aria-atomic="true"
                                class="alert mt-3 alert-dismissible" :class="alert.type"><button type="button" aria-label="Close"
                                    class="close">×</button>{{ alert.message }}</div>
                            <!---->
                            <div class="p-3">
                                <form>
                                    <div class="form-group">
                                        <label>Họ và tên</label>
                                        <div class="input-group mb-3 bg-soft-light input-group-lg rounded-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text border-light text-muted">
                                                    <i class="fa-solid fa-user"></i>
                                                </span>
                                            </div>
                                            <input v-model="data.name" name="name" type="text" placeholder="Enter your name"
                                                class="form-control bg-soft-light border-light"
                                                :class="{ 'is-invalid': validationErrors.name }" /><!---->
                                            <div v-if="validationErrors.name" class="invalid-feedback">{{
                                                validationErrors.name[0] }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <div class="input-group mb-3 bg-soft-light input-group-lg rounded-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text border-light text-muted">
                                                    <i class="fa-solid fa-envelope"></i>
                                                </span>
                                            </div>
                                            <input v-model="data.email" name="email" type="email"
                                                placeholder="Enter your email"
                                                class="form-control bg-soft-light border-light"
                                                :class="{ 'is-invalid': validationErrors.email }" /><!---->
                                            <div v-if="validationErrors.email" class="invalid-feedback">{{
                                                validationErrors.email[0] }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label>Mật khẩu</label>
                                        <div class="input-group mb-3 bg-soft-light input-group-lg rounded-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text border-light text-muted">
                                                    <i class="fa-solid fa-lock"></i>
                                                </span>
                                            </div>
                                            <input v-model="data.password" name="password" type="password"
                                                placeholder="Enter your password"
                                                class="form-control bg-soft-light border-light"
                                                :class="{ 'is-invalid': validationErrors.password }" /><!---->
                                            <div v-if="validationErrors.password" class="invalid-feedback">{{
                                                validationErrors.password[0] }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" @click="onRegister" class="btn btn-primary btn-block">
                                            Đăng ký
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>
                            Đã có tài khoản ?
                            <router-link class="font-weight-medium text-primary" :to="{ name: 'login-route' }">Đăng
                                nhập</router-link>
                        </p>
                        <p> © 2023 ChatApp. Created by TXSon </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            data: {
                name: '',
                email: '',
                password: '',
            },
            validationErrors: {},
            alert: {
                type: 'alert-success',
                message: '',
                show: false,
            },
            processing: false,
        };
    },
    methods: {
        onRegister() {
            this.processing = true;
            axios.get('/sanctum/csrf-cookie');
            const req = axios.post('api/auth/register', this.data);
            req.then((response) => {
                console.log(response);
                this.resetData();
                this.setAlert('alert-success', response.data.message, true);
            });

            req.catch(({ response }) => {
                if (response.status === 422) {
                    this.validationErrors = response.data.errors;
                }
                else {
                    console.log(response.data.message);
                    this.setAlert('alert-danger', response.data.message, true);
                }
            });

            req.finally(() => {
                this.processing = false
            });
        },
        hideAlert() {
            this.alert.show = false;
        },
        setAlert(type, message, show) {
            this.alert.type = type;
            this.alert.message = message;
            this.alert.show = show;
        },
        resetData() {
            this.data = {
                name: '',
                email: '',
                password: '',
            };
        },
    },
};
</script>