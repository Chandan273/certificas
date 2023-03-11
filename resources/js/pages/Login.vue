<template>
    <div class="auth-wrapper login-page" style="height: 100vh">

        <v-sheet class="d-flex breadcrunms_and_languagechange">
            <v-sheet class="ma-2 pa-2 me-auto">
            </v-sheet>
            <v-sheet class="ma-2 pa-2 d-flex align-center  ">
                <p class="text-body-1 
                 font-weight-black  text-blue 
                    ">{{ $t("selectLanguage") }}</p>
            </v-sheet>
            <v-sheet class="ma-2 pa-2">
                <select v-model="$i18n.locale" @change="changeLanguage">
                    <option value="en">English</option>
                    <option value="nl">Dutch</option>
                </select>
            </v-sheet>
        </v-sheet>
        <div class="d-flex align-center parent justify-content my-auto py-auto" style="height:80%;">
            <v-container>

                <div class="white child-wrapper">
                    <v-row no-gutters>
                        <v-col cols="12" sm="6">
                            <div class="left-box px-10 py-12">
                                <div class="logo mb-5 text-left">
                                    <h3 class="page-title">
                                        {{ $t("login") }}
                                    </h3>
                                    <p>
                                        {{ $t("loginTitle") }}
                                    </p>
                                </div>
                                <v-form ref="form" @submit.prevent="login" class="login-form">
                                    <div class="mb-5">
                                        <label for="email">{{
                                            $t("email")
                                        }}</label>
                                        <v-text-field variant="outlined" v-model="loginForm.email" required name="email"
                                            :placeholder="$t('enterYourEmail')" hide-details="auto"
                                            prepend-icon="mdi-account" class="mt-2" />
                                        <div class="text-start">
                                            <span v-if="email_error" class="invalid-feedback text-red">{{ email_error
                                            }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label>{{ $t("password") }}</label>
                                        <v-text-field variant="outlined" type="Password" v-model="loginForm.password"
                                            required :placeholder="
                                                $t('enterYourPassword')
                                            " hide-details="auto" prepend-icon="mdi-lock" class="mt-2" />
                                        <div class="text-start">
                                            <span v-if="password_error" class="invalid-feedback text-red">{{ password_error
                                            }}</span>
                                            <span v-if="error" class="invalid-feedback text-red">{{ error }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-5 d-flex justify-end align-center flex-wrap">
                                        <router-link class="forgot-txt" to="/forgot-password">
                                            {{ $t("forgotPassword?") }}
                                        </router-link>
                                    </div>
                                    <v-btn type="submit" block class="primary-btn">{{ $t("logIn") }}</v-btn>
                                </v-form>
                            </div>
                        </v-col>
                        <v-col cols="12" sm="6" class="d-none d-sm-block">
                            <div class="right-box text-center">
                                <div class="box pa-7 d-flex justify-center align-center">
                                    <div>
                                        <div class="logo mb-4">
                                            <h2>{{ $t("welcome") }}</h2>
                                        </div>
                                        <img src="../assets/images/login.svg" />
                                    </div>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
            </v-container>
        </div>
    </div>
</template>
<script>

import { mapGetters } from "vuex";
import Auth from "../Auth.js";

export default {
    auth: false,
    name: "Login",
    layout: "UserLayout",
    computed: {
        ...mapGetters({
            getLoginInfo: "authetication/getLoginInfo",
            accessToken: "authetication/getAccessToken",
        }),
    },
    data() {
        return {
            error: "",
            email_error: "",
            password_error: "",
            loader: false,
            show1: false,
            loginForm: {
                email: "",
                password: "",
                lang: "en",
            },
            checkbox: false,
            alert: false,
            messageRxd: "",
        };
    },
    methods: {
        async login() {
            this.error = "";
            this.email_error = "";
            this.password_error = "";
            this.axios
                .post("/api/login", this.loginForm)
                .then(({ data }) => {
                    if (data.statusCode == 401) {
                        this.password_error = data.error;
                        return false;
                    }

                    localStorage.setItem("role", data.role);
                    Auth.login(data.accessToken, data.user);
                    if (data.role == "superadmin") {
                        this.$router.push("/tenants");
                    } else {
                        localStorage.setItem(
                            "tenant",
                            JSON.stringify(data.tenant)
                        );
                        this.$router.push("/courses");
                    }
                })
                .catch((error) => {
                    if (
                        error.response &&
                        error.response.data &&
                        error.response.data.status == false
                    ) {
                        this.error = error.response.data.error;
                        if (this.error && this.error.password) {
                            this.password_error = this.error.password[0];
                        }
                    }
                    if (error.response && error.response.status == 400) {
                        if (
                            error.response.data &&
                            error.response.data.error &&
                            error.response.data.error.email
                        ) {
                            this.email_error =
                                error.response.data.error.email[0];
                        }
                        if (
                            error.response.data &&
                            error.response.data.error &&
                            error.response.data.error.password
                        ) {
                            this.password_error =
                                error.response.data.error.password[0];
                        }
                    }
                });
        },
        changeLanguage(obj) {
            localStorage.setItem("language", obj.target.value);
        },
    },
    mounted() {
        this.$i18n.locale = "nl";

        const defaultLanguage = localStorage.getItem("language");
        if (defaultLanguage) {
            this.$i18n.locale = defaultLanguage;
        } else {
            localStorage.setItem("language", "nl");
        }

        let userInfo = localStorage.getItem("user");
        if (userInfo) {
            let userRole = localStorage.getItem("role");
            if (userRole == "superadmin") {
                this.$router.push("/tenants");
            } else {
                this.$router.push("/courses");
            }
        }
    },
};
</script>

<style lang="scss">
a.forgot-txt {
    color: #008cff;
    &:hover {
        text-shadow: 1px 1px 1px #008cff2e;
    }
}
</style>
