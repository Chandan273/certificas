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
                <v-form ref="form" @submit.prevent="login">
                  <v-alert v-for="(item, index) in validationMsg" :key="index" v-model="alert" type="error"
                    class="alert-msg-error">{{ item }}</v-alert>
                  <div class="mb-4">
                    <label for="email"><strong>Email</strong></label>
                    <v-text-field v-model="loginForm.email" required :rules="emailRules" outlined class="pt-2"
                      name="email" placeholder="Enter your email address" hide-details="auto" rounded color="#ff0000" />
                  </div>
                  <div class="mb-4">
                    <label><strong>Password</strong></label>
                    <v-text-field :type="show1 ? 'text' : 'password'" v-model="loginForm.password" required
                      :rules="passwordRules" outlined class="pt-2" placeholder="Enter your password"
                      :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" @click:append="show1 = !show1"
                      hide-details="auto" rounded color="#ff0000" />
                  </div>

                  <div class="mb-4 d-flex justify-space-between align-center flex-wrap">
                    <v-checkbox v-model="checkbox" class="mt-0 pt-0" hide-details color="#ff0000">
                      <template #label>
                        <div>Remember</div>
                      </template>
                    </v-checkbox>
                    <router-link to="/forgot-password">
                      Forgot Password?
                    </router-link>
                  </div>
                  <v-btn type="submit" block rounded class="primary-red mt-2">
                    Log In
                  </v-btn>
                  <v-divider class="my-4"></v-divider>
                  <div class="register d-flex justify-center align-center flex-wrap">
                    <p class="mb-0 me-3">Dont have an account?</p>
                    <router-link to="/register">
                      Sign-up
                    </router-link>
                  </div>
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
                      Welcome back to the best. <br />
                      we're always here, waiting for you.
                    </p>
                    <div>
                      <p class="mb-0 caption">Not have an account?</p>
                      <v-btn type="submit" rounded class="primary-red mt-2 white" @click="$router.push('/register')">
                        Sign Up
                      </v-btn>
                    </div>

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
export default {
  middleware: ["guest"],
  name: "ExampleComponent",
  computed: {
    ...mapGetters({
      getLoginInfo: "authetication/getLoginInfo",
      accessToken: "authetication/getAccessToken",
    }),
  },
  data() {
    return {
      loader: false,
      show1: false,
      loginForm: {
        email: "",
        password: "",
      },
      checkbox: false,
      emailRules: [
        (v) => !!v || "E-mail is required.",
        (v) => /.+@.+\..+/.test(v) || "E-mail must be valid.",
      ],
      passwordRules: [(v) => !!v || "Password is required."],
      validationMsg: [],
      alert: false,
      messageRxd: "",
    };
  },
  methods: {
    async login() {
      try {
        if (!this.$refs.form.validate()) {
          return;
        }
        console.log("this.loginForm => ", this.loginForm);
        this.loader = true;
        await this.$auth.loginWith("local", {
          data: this.loginForm,
        });
        this.loader = false;
        this.$router.push(`/admin/dashboard`);
      } catch ({ message, response }) {
        this.loader = false;
        this.alert = true;
        if (Array.isArray(response.data.message)) {
          this.validationMsg = response.data.message;
        } else {
          this.validationMsg = [response.data.message];
        }
      }
    },
  },
};
</script>
<style lang="scss">
.auth-wrapper {
    background: #fff2f2d1;


    .parent {
      max-width: 800px;
      margin: auto;

      .child-wrapper {
        border-radius: 20px;
        overflow: hidden;
        border-bottom: 1px solid #e0e3e8;
      }
    }

    .right-box {
      background: url(../images/barbers-bg.png);
      color: #fff;
      height: 100%;

      .box {
        height: 100%;
        background-color: rgba(255, 0, 0, 0.93);
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;

        .v-btn.primary-red {
          color: var(--main-primary);
        }

        p {
          color: #fff;
        }
      }
    }

    .left-box {
      background-color: #fff;
      border-top-left-radius: 20px;
      border-bottom-left-radius: 20px;

      .logo>h3.page-title {
        color: var(--main-primary);
        font-size: 1.8rem;
      }


    }
  }

  .v-form {
    .v-input__control {
      .v-text-field__details {
        margin-bottom: 0;
      }

      .v-input__slot {
        min-height: 36px;

        .v-input__append-inner {
          margin-top: 0;
          align-self: center;
        }
      }
    }

    .v-btn {
      &.primary-red {
        background-color: var(--main-primary);
        color: #fff;
      }

      &.secondary-dark {
        background-color: #303030;
        color: #fff;
      }
    }

    .input-country-group {
      position: relative;

      .v-input>.v-input__control>.v-input__slot {
        padding-left: 80px;
      }

      .vue-country-select {
        &.country-code {
          position: absolute;
          z-index: 9;
          border: none;
        }

        &:focus-within {
          box-shadow: none;
          border: none;
        }

        .dropdown {

          &.open,
          &:hover {
            background-color: transparent;
          }
        }
      }
    }
  }
</style>
