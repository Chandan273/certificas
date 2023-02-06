<template>
    <div class="auth-wrapper">
        <div class="d-flex align-center parent" style="height: 100vh">
            <v-container>
                <div class="white child-wrapper">
                    <v-row no-gutters>
                        <v-col cols="12" sm="6">
                            <div class="left-box pa-7">
                                <div class="logo mb-3 text-center">
                                    <h3 class="page-title">Reset Password</h3>
                                </div>
                                <v-form ref="form" @submit.prevent="login">
                                    <div class="text-center">
                                        <span
                                            v-if="errors"
                                            class="invalid-feedback text-red"
                                            >{{ errors }}</span
                                        >
                                    </div>
                                    <v-alert
                                        v-for="(item, index) in validationMsg"
                                        :key="index"
                                        v-model="alert"
                                        type="error"
                                        class="alert-msg-error"
                                    >
                                        {{ item }}
                                    </v-alert>
                                    <div class="mb-5">
                                        <label><strong>Password</strong></label>
                                        <v-text-field
                                            variant="outlined"
                                            type="Password"
                                            v-model="password"
                                            required
                                            :rules="passwordRules"
                                            class="pt-2"
                                            placeholder="Enter your password"
                                            hide-details="auto"
                                        />
                                    </div>
                                    <div class="mb-5">
                                        <label
                                            ><strong
                                                >Confirm Password</strong
                                            ></label
                                        >
                                        <v-text-field
                                            variant="outlined"
                                            type="Password"
                                            v-model="confirmPassword"
                                            required
                                            :rules="confirmPasswordRules"
                                            class="pt-2"
                                            placeholder="Enter confirm password"
                                            hide-details="auto"
                                        />
                                    </div>

                                    <v-btn
                                        type="submit"
                                        block
                                        class="primary-red mt-2"
                                    >
                                        Reset
                                    </v-btn>
                                </v-form>
                            </div>
                        </v-col>
                        <v-col cols="12" sm="6" class="d-none d-sm-block">
                            <div class="right-box text-center">
                                <div
                                    class="box pa-7 d-flex justify-center align-center"
                                >
                                    <div>
                                        <div class="logo">
                                            <h2>Certificas</h2>
                                        </div>
                                        <h1 class="mb-3 mt-2">Welcome Back!</h1>
                                        <p>
                                            Welcome back to the best.
                                            <br />
                                            we're always here, waiting for you.
                                        </p>
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
import axios from "axios";

export default {
    auth: false,
    name: "Login",
    layout: "UserLayout",
    data() {
        return {
            errors: "",
            loader: false,
            password: "",
            confirmPassword: "",
            checkbox: false,
            // passwordRules: [(v) => !!v || "Password is required."],
            // confirmPasswordRules: [(v) => !!v || "Password is required."],
            // validationMsg: [],
            alert: false,
            messageRxd: "",
        };
    },
    methods: {
        async login() {
            this.errors = "";
            const payload = {
                password: this.password,
                confirm_password: this.confirmPassword,
                email: this.$route.query.email
            };
            axios
                .post("/api/reset-password", payload)
                .then(({ data }) => {
                    this.errors = data.message;
                    this.$router.push("/login");
                })
                .catch((error) => {
                    if (error.response.data.success == false) {
                        this.errors = error.response.data.error.password[0];
                    }
                });
        },
    },
};
</script>
