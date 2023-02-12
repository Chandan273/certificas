<template>
  <div class="auth-wrapper login-page">
    <div class="d-flex align-center parent" style="height: 100vh">
      <v-container>
        <div class="white child-wrapper">
          <v-progress-linear
            v-if="pageLoader"
            indeterminate
            color="blue"
          ></v-progress-linear>
          <v-row no-gutters>
            <v-col cols="12" sm="6">
              <div class="left-box px-10 py-12">
                <v-alert
                  v-if="success"
                  class="px-0 py-2 mb-3"
                  type="success"
                  text="Password updated successfully!!"
                ></v-alert>
                <div class="logo mb-5 text-left">
                  <h3 class="page-title">Reset Password</h3>
                  <p>Set the new password for your account to login</p>
                </div>
                <v-form ref="form" @submit.prevent="login" class="login-form">
                  <div class="text-start">
                    <span v-if="errors" class="invalid-feedback text-red">{{
                      errors
                    }}</span>
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
                    <label>New Password</label>
                    <v-text-field
                      variant="outlined"
                      type="Password"
                      v-model="password"
                      required
                      :rules="passwordRules"
                      class="mt-2"
                      placeholder="Enter your password"
                      hide-details="auto"
                      prepend-icon="mdi-lock"
                    />
                  </div>
                  <div class="mb-8">
                    <label>Confirm Password</label>
                    <v-text-field
                      variant="outlined"
                      type="Password"
                      v-model="confirmPassword"
                      required
                      :rules="confirmPasswordRules"
                      class="mt-2"
                      placeholder="Enter confirm password"
                      hide-details="auto"
                      prepend-icon="mdi-lock"
                    />
                  </div>

                  <v-btn type="submit" block class="primary-btn"> Reset </v-btn>
                </v-form>
              </div>
            </v-col>
            <v-col cols="12" sm="6" class="d-none d-sm-block">
              <div class="right-box text-center">
                <div class="box pa-7 pt-14 d-flex justify-center align-center">
                  <div>
                    <div class="logo">
                      <h2>Certificas</h2>
                    </div>
                    <img src="../assets/images/forgot.png" />
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
      loader: false,
      success: false,
      pageLoader: false,
      password: "",
      confirmPassword: "",
      checkbox: false,
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
        email: this.$route.query.email,
      };
      axios
        .post("/api/reset-password", payload)
        .then(({ data }) => {
          if (data.success == true) {
            this.success = true;
            this.pageLoader = true;
            setTimeout(() => this.$router.push({ path: "/login" }), 3000);
          }
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
