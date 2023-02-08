<template>
    <div class="auth-wrapper login-page">
        <div class="d-flex align-center parent" style="height: 100vh">
            <v-container>
                <div class="white child-wrapper">
                    <v-row no-gutters>
                        <v-col cols="12" sm="6">
                            <div class="left-box px-10 py-12">
                                <div class="logo mb-3 text-center">
                                    <h3 class="page-title">Forgot Password</h3>
                                </div>
                                <v-form
                                    ref="form"
                                    @submit.prevent="forgotPassword"
                                    class="login-form"
                                >
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
                                        <label for="email">Email</label>
                                        <v-text-field
                                            variant="outlined"
                                            v-model="email"
                                            :rules="emailRules"
                                            class="mt-2"
                                            name="email"
                                            placeholder="Enter your email address"
                                            hide-details="auto"
                                            prepend-icon="mdi-account"
                                        />
                                        <div class="text-start">
                                            <span
                                                v-if="errors"
                                                class="invalid-feedback text-red"
                                                >{{ errors }}</span
                                            >
                                        </div>
                                    </div>
                                    <v-btn
                                        type="submit"
                                        block
                                        class="primary-red mt-2"
                                        elevation="0"
                                    >
                                        <v-progress-circular
                                            v-if="loader"
                                            size="20"
                                            width="2"
                                            indeterminate
                                            color="light"
                                            class="mr-2"
                                        ></v-progress-circular>
                                        Send Reset Password Link
                                    </v-btn>
                                    <div class="text-start mt-4">
                                        <span
                                            v-if="success"
                                            class="invalid-feedback text-green"
                                            ><v-icon
                                                icon="mdi-check"
                                                class="mr-2"
                                            ></v-icon
                                            >{{ success }}</span
                                        >
                                    </div>
                                </v-form>
                                <v-btn
                                    variant="text"
                                    class="mt-12 mx-auto d-table"
                                    ><v-icon
                                        icon="mdi-chevron-left"
                                        class="mr-2"
                                    ></v-icon>
                                    <router-link class="forgot-txt" to="/login"
                                        >Back</router-link
                                    ></v-btn
                                >
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
import axios from "axios";

export default {
    auth: false,
    name: "Login",
    layout: "UserLayout",
    data() {
        return {
            errors: "",
            success: "",
            loader: false,
            email: "",
            checkbox: false,
            validationMsg: [],
            alert: false,
            messageRxd: "",
        };
    },
    methods: {
        async forgotPassword() {
            this.loader = true;
            this.errors = "";
            this.success = "";
            axios
                .post("/api/forgot-password", { email: this.email })
                .then(({ data }) => {
                    this.success = data.message;
                    this.loader = false;
                })
                .catch((error) => {
                    if (error.response.data.success == false) {
                        this.errors = error.response.data.message;
                    }
                });
        },
    },
};
</script>
