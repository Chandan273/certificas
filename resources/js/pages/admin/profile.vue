<template>
    <AdminLayout>
        <v-breadcrumbs class="ps-0" :items="translatedItems"></v-breadcrumbs>
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card profile-page">
            <v-snackbar
                v-model="snackbar"
                :value="true"
                absolute
                right
                top
                location="top right"
                :color="color"
                timeout="3000"
            >
                <v-icon icon="mdi-check-circle"> </v-icon> {{ message }}
            </v-snackbar>
            <v-row>
                <v-col cols="12" md="12">
                    <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 personal-info">
                        <h3 class="mb-2 pb-2">
                            {{ $t("personalInformation") }}
                        </h3>
                        <v-form ref="myForm">
                            <v-row>
                                <v-col cols="12" sm="3">
                                    <div class="mb-4">
                                        <label for="fname">{{
                                            $t("userName")
                                        }}</label>
                                        <v-text-field
                                            variant="outlined"
                                            v-model="user.username"
                                            type="text"
                                            name="fname"
                                            class="mt-2"
                                            :placeholder="$t('userName')"
                                            white
                                            autocomplete="off"
                                            hide-details="auto"
                                        ></v-text-field>
                                        <div class="text-start">
                                            <span
                                                v-if="usernameErr"
                                                class="invalid-feedback text-red"
                                                >{{ usernameErr }}</span
                                            >
                                        </div>
                                    </div>
                                </v-col>
                                <v-col cols="12" sm="3">
                                    <div class="mb-4">
                                        <label>{{ $t("email") }}</label>
                                        <v-text-field
                                            v-model="user.email"
                                            variant="outlined"
                                            class="mt-2"
                                            :placeholder="$t('email')"
                                            hide-details="auto"
                                        ></v-text-field>
                                        <div class="text-start">
                                            <span
                                                v-if="emailErr"
                                                class="invalid-feedback text-red"
                                                >{{ emailErr }}</span
                                            >
                                        </div>
                                    </div>
                                </v-col>
                                <v-col sm="3" v-if="userRole == 'company'">
                                    <label>{{ $t("companyName") }}</label>
                                    <v-text-field
                                        v-model="tenant.name"
                                        variant="outlined"
                                        class="mt-2"
                                        :placeholder="$t('companyName')"
                                        hide-details="auto"
                                    ></v-text-field>
                                    <div class="text-start">
                                        <span
                                            v-if="nameErr"
                                            class="invalid-feedback text-red"
                                            >{{ nameErr }}</span
                                        >
                                    </div>
                                </v-col>

                                <v-col class="text-end pt-0 mb-6" sm="12">
                                    <v-btn
                                        type="button"
                                        @click.prevent="resetForm"
                                        class="px-5 me-2"
                                        color="#008cff"
                                        variant="outlined"
                                        elevation="0"
                                    >
                                        {{ $t("reset") }}
                                    </v-btn>
                                    <v-btn
                                        type="button"
                                        v-on:click="updateProfile"
                                        class="primary-btn px-6"
                                    >
                                        {{ $t("save") }}
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </div>
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col cols="12" md="12" class="pt-0">
                    <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 pt-lg-0">
                        <h3 class="mb-2 pb-2">{{ $t("updatePassword") }}</h3>
                        <v-form ref="passwordForm">
                            <v-row>
                                <v-col cols="12" sm="3">
                                    <div class="mb-4">
                                        <label for="fname">{{
                                            $t("currentPassword")
                                        }}</label>
                                        <v-text-field
                                            v-model="update.currentPassword"
                                            type="password"
                                            name="fname"
                                            variant="outlined"
                                            class="mt-2"
                                            :placeholder="$t('currentPassword')"
                                            white
                                            autocomplete="off"
                                            hide-details="auto"
                                        ></v-text-field>
                                        <div class="text-start">
                                            <span
                                                v-if="current_password_err"
                                                class="invalid-feedback text-red"
                                                >{{
                                                    current_password_err
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </v-col>

                                <v-col cols="12" sm="3">
                                    <div class="mb-4">
                                        <label>{{ $t("newPassword") }}</label>
                                        <v-text-field
                                            v-model="update.newPassword"
                                            type="password"
                                            variant="outlined"
                                            class="mt-2"
                                            :placeholder="$t('newPassword')"
                                            hide-details="auto"
                                        ></v-text-field>
                                        <div class="text-start">
                                            <span
                                                v-if="password_err"
                                                class="invalid-feedback text-red"
                                                >{{ password_err }}</span
                                            >
                                        </div>
                                    </div>
                                </v-col>
                                <v-col sm="3">
                                    <div>
                                        <label>{{
                                            $t("confirmPassword")
                                        }}</label>
                                        <v-text-field
                                            v-model="update.confirmPassword"
                                            variant="outlined"
                                            type="password"
                                            class="mt-2"
                                            :placeholder="$t('confirmPassword')"
                                            hide-details="auto"
                                        ></v-text-field>
                                    </div>
                                    <div class="text-start">
                                        <span
                                            v-if="confirm_password_err"
                                            class="invalid-feedback text-red"
                                            >{{ confirm_password_err }}</span
                                        >
                                    </div>
                                </v-col>
                                <v-col class="text-end pt-0" sm="12">
                                    <v-btn
                                        type="button"
                                        v-on:click="updatePassword"
                                        class="primary-btn px-6"
                                    >
                                        {{ $t("save") }}
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </div>
                </v-col>
            </v-row>
        </div>
    </AdminLayout>
</template>

<script>
import axios from "axios";
import AdminLayout from "../../layouts/adminLayout.vue";

export default {
    name: "profile",
    data() {
        return {
            emailErr: "",
            usernameErr: "",
            nameErr: "",
            confirm_password_err: "",
            current_password_err: "",
            password_err: "",
            userRole: localStorage.getItem("role"),
            user: JSON.parse(localStorage.getItem("user")),
            tenant: JSON.parse(localStorage.getItem("tenant")),
            snackbar: false,
            message: "",
            color: "success",
            update: {
                currentPassword: null,
                newPassword: null,
                confirmPassword: null,
            },
        };
    },
    components: {
        AdminLayout,
    },
    methods: {
        async updateProfile() {
            this.emailErr = "";
            this.usernameErr = "";
            this.nameErr = "";

            try {
                const payload = {
                    user_id: this.user.id,
                    username: this.user.username,
                    name: this.tenant ? this.tenant.name : "name",
                    email: this.user.email,
                };
                let result = await axios.post("/api/update-profile", payload);

                if (result.data.success == true) {
                    if (this.userRole == "company") {
                        localStorage.setItem(
                            "user",
                            JSON.stringify(result.data.user)
                        );
                        localStorage.setItem(
                            "tenant",
                            JSON.stringify(result.data.tenant)
                        );

                        this.snackbar = true;
                        this.message = result.data.message;
                    } else {
                        localStorage.setItem(
                            "user",
                            JSON.stringify(result.data.user)
                        );

                        this.snackbar = true;
                        this.message = result.data.message;
                    }
                }
            } catch (error) {
                if (
                    error.response.data &&
                    error.response.data.errors &&
                    error.response.data.errors.email
                ) {
                    this.emailErr = error.response.data.errors.email[0];
                }
                if (
                    error.response.data &&
                    error.response.data.errors &&
                    error.response.data.errors.username
                ) {
                    this.usernameErr = error.response.data.errors.username[0];
                }
                if (
                    error.response.data &&
                    error.response.data.errors &&
                    error.response.data.errors.name
                ) {
                    this.nameErr = error.response.data.errors.name[0];
                }
            }
        },
        resetForm() {
            this.$refs.myForm.reset();
        },
        async updatePassword() {
            this.confirm_password_err = "";
            this.current_password_err = "";
            this.password_err = "";
            try {
                const payload = {
                    user_id: this.user.id,
                    current_password: this.update.currentPassword,
                    password: this.update.newPassword,
                    confirm_password: this.update.confirmPassword,
                };
                let result = await axios.post("/api/profile-password", payload);
                if (result.data.success == true) {
                    this.$refs.passwordForm.reset();

                    this.snackbar = true;
                    this.message = result.data.message;
                }
                if (result.data.success == false) {
                    this.snackbar = true;
                    this.color = "error";
                    this.message = result.data.message;
                }
            } catch (error) {
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.confirm_password
                ) {
                    this.confirm_password_err =
                        error.response.data.error.confirm_password[0];
                }
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.current_password
                ) {
                    this.current_password_err =
                        error.response.data.error.current_password[0];
                }
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.password
                ) {
                    this.password_err = error.response.data.error.password[0];
                }
            }
        },
    },
    computed: {
        translatedItems() {
            return [
                {
                    text: this.$t("admin"),
                    disabled: true,
                    href: "dashboard",
                },
                {
                    text: this.$t("profile"),
                    disabled: false,
                    href: "/profile",
                },
            ];
        },
    },
};
</script>
