<template>
    <div class="auth-wrapper login-page">
        <div class="d-flex align-center parent" style="height: 100vh">
            <v-container>
                <div class="white child-wrapper">
                    <v-row no-gutters>
                        <v-col cols="12" sm="6">
                            <div class="left-box px-10 py-12">
                                <div class="logo mb-5 text-left">
                                    <h3 class="page-title">
                                        {{ $t("forgotPassword") }}
                                    </h3>
                                    <p>
                                        {{ $t("sendResetLink") }}
                                    </p>
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
                                    <div class="mb-8">
                                        <label for="email">{{
                                            $t("email")
                                        }}</label>
                                        <v-text-field
                                            variant="outlined"
                                            v-model="email"
                                            class="mt-2"
                                            name="email"
                                            :placeholder="
                                                $t('enterConfirmPassword')
                                            "
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
                                        class="primary-btn"
                                    >
                                        <v-progress-circular
                                            v-if="loader"
                                            size="20"
                                            width="2"
                                            indeterminate
                                            color="light"
                                            class="mr-2"
                                        ></v-progress-circular>
                                        {{ $t("sendResetLink") }}
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
                                <router-link
                                    class="forgot-txt mt-12 mx-auto d-table"
                                    to="/login"
                                    ><v-icon icon="mdi-chevron-left"></v-icon>
                                    {{ $t("back") }}</router-link
                                >
                            </div>
                        </v-col>
                        <v-col cols="12" sm="6" class="d-none d-sm-block">
                            <div class="right-box text-center">
                                <div
                                    class="box pa-7 pt-14 d-flex justify-center align-center"
                                >
                                    <div>
                                        <div class="logo mb-4">
                                            <h2>{{ $t("certificates") }}</h2>
                                        </div>
                                        <img
                                            src="../assets/images/forgot.png"
                                        />
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
            this.axios
                .post("/api/forgot-password", { email: this.email })
                .then(({ data }) => {
                    if (data.statusCode == 200) {
                        this.success = data.message;
                        this.loader = false;
                    }

                    if (data.statusCode == "401") {
                        this.errors = data.message;
                        this.loader = false;
                    }
                })
                .catch((error) => {
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.email
                    ) {
                        this.errors = error.response.data.error.email[0];
                        this.loader = false;
                    }
                });
        },
    },
};
</script>
