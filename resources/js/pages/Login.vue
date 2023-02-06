<template>
  <div class="auth-wrapper">
    <div class="d-flex align-center parent" style="height: 100vh">
      <v-container>
        <div class="white child-wrapper">
          <v-row no-gutters>
            <v-col cols="12" sm="6">
              <div class="left-box pa-7">
                <div class="logo mb-3 text-center">
                  <h3 class="page-title">Login</h3>
                </div>
                <div class="text-center">
                  <span v-if="error" class="invalid-feedback text-red">{{
                    error
                  }}</span>
                </div>
                <v-form ref="form" @submit.prevent="login">
                  <!-- <v-alert
                    v-for="(item, index) in validationMsg"
                    :key="index"
                    v-model="alert"
                    type="error"
                    class="alert-msg-error"
                  >
                    {{ item }}
                  </v-alert> -->
                  <div class="mb-4">
                    <label for="email"><strong>Email</strong></label>
                    <v-text-field variant="outlined" v-model="loginForm.email" required :rules="emailRules" class="pt-2"
                      name="email" placeholder="Enter your email address" hide-details="auto" />
                    <div class="text-center">
                      <span v-if="email_error" class="invalid-feedback text-red">{{
                        email_error
                      }}</span>
                    </div>
                  </div>
                  <div class="mb-5">
                    <label><strong>Password</strong></label>
                    <v-text-field variant="outlined" type="Password" v-model="loginForm.password" required
                      :rules="passwordRules" class="pt-2" placeholder="Enter your password" hide-details="auto" />
                    <div class="text-center">
                      <span v-if="password_error" class="invalid-feedback text-red">{{
                        password_error
                      }}</span>
                    </div>
                  </div>
                  <div class="mb-4 d-flex justify-space-between align-center flex-wrap">
                    <router-link class="forgot-txt" to="/forgot-password">
                      Forgot Password?
                    </router-link>
                  </div>
                  <v-btn type="submit" block class="primary-red mt-2">Log In</v-btn>
                </v-form>
              </div>
            </v-col>
            <v-col cols="12" sm="6" class="d-none d-sm-block">
              <div class="right-box text-center">
                <div class="box pa-7 d-flex justify-center align-center">
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
import Auth from "../Auth.js";
import axios from "axios";

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
      },
      checkbox: false,
      //   emailRules: [
      //     (v) => !!v || "E-mail is required.",
      //     (v) => /.+@.+\..+/.test(v) || "E-mail must be valid.",
      //   ],
      //   passwordRules: [(v) => !!v || "Password is required."],
      //   validationMsg: [],
      alert: false,
      messageRxd: "",
    };
  },
  methods: {
    async login() {
      this.error = "";
      this.email_error = "";
      this.password_error = "";
      axios
        .post("/api/login", this.loginForm)
        .then(({ data }) => {
          localStorage.setItem("role", data.role);
          Auth.login(data.access_token, data.user);
          if (data.role == "superadmin") {
            this.$router.push("/tenants");
          } else {
            localStorage.setItem("tenant", JSON.stringify(data.tenant));
            this.$router.push("/courses");
          }
        })
        .catch((error) => {
          if (error.response.data.status == false) {
            this.error = error.response.data.error;
          }
          if (error.response.status == 400) {
            this.email_error = error.response.data.error.email[0];
            this.password_error = error.response.data.error.password[0];
          }

        });
    },
  },
  mounted() {
    let userInfo = localStorage.getItem("user");
    if (userInfo) {
      this.$router.push("/tenants");
    }
  },
};
</script>

<style>
a.forgot-txt {
  color: #008cff;
}
</style>
