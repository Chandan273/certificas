<template>
    <div class="auth-wrapper">
        <div class="d-flex align-center parent" style="height: 100vh">
            <v-container>
                <div class="white child-wrapper">
                    <v-row no-gutters>
                        <v-col cols="12" sm="6">
                            <div class="left-box pa-7">
                                <div class="logo mb-3 text-center">
                                    <h3 class="page-title">Forgot Password</h3>
                                </div>
                                <v-form
                                    ref="form"
                                    @submit.prevent="forgotPassword"
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
                                    <div class="mb-4">
                                        <label for="email"
                                            ><strong>Email</strong></label
                                        >
                                        <v-text-field
                                            variant="outlined"
                                            v-model="email"
                                            required
                                            :rules="emailRules"
                                            class="pt-2"
                                            name="email"
                                            placeholder="Enter your email address"
                                            hide-details="auto"
                                        />
                                        <div class="text-center">
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
                                    >
                                        Send Reset Password Link
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
      email: "",
      checkbox: false,
      emailRules: [
        (v) => !!v || "E-mail is required.",
        (v) => /.+@.+\..+/.test(v) || "E-mail must be valid.",
      ],
      validationMsg: [],
      alert: false,
      messageRxd: "",
    };
  },
  methods: {
    async forgotPassword() {
      this.errors = "";
      axios
        .post("/api/forgot-password", {email: this.email})
        .then(({ data }) => {
          this.errors = data.message;
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
