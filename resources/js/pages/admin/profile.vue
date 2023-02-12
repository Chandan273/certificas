<template>
  <AdminLayout>
    <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
    <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card profile-page">
      <v-row>
        <v-col cols="12" md="12">
          <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 personal-info">
            <h3 class="mb-2 pb-2">Personal Information</h3>
            <v-form ref="form">
              <v-row>
                <v-col cols="12" sm="3">
                  <div class="mb-4">
                    <label for="fname">User Name</label>
                    <v-text-field
                      variant="outlined"
                      v-model="user.username"
                      type="text"
                      name="fname"
                      class="mt-2"
                      placeholder="User Name"
                      white
                      autocomplete="off"
                      hide-details="auto"
                    ></v-text-field>
                  </div>
                </v-col>
                <v-col cols="12" sm="3">
                  <div class="mb-4">
                    <label>Email</label>
                    <v-text-field
                      v-model="user.email"
                      variant="outlined"
                      class="mt-2"
                      placeholder="Email Address"
                      hide-details="auto"
                    ></v-text-field>
                  </div>
                </v-col>
                <v-col sm="3" v-if="userRole == 'company'">
                  <label>Company Name</label>
                  <v-text-field
                    v-model="tenant.name"
                    variant="outlined"
                    class="mt-2"
                    placeholder="Company Name"
                    hide-details="auto"
                  ></v-text-field>
                </v-col>

                <v-col class="text-end pt-0 mb-6" sm="12">
                  <v-btn
                    type="button"
                    class="px-5 me-2"
                    color="#008cff"
                    variant="outlined"
                    elevation="0"
                  >
                    Reset
                  </v-btn>
                  <v-btn
                    type="button"
                    v-on:click="updateProfile"
                    class="primary-btn px-6"
                  >
                    Save
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
            <h3 class="mb-2 pb-2">Update Password</h3>
            <v-form ref="form">
              <v-row>
                <v-col cols="12" sm="3">
                  <div class="mb-4">
                    <label for="fname">Current Password</label>
                    <v-text-field
                      v-model="update.currentPassword"
                      type="password"
                      name="fname"
                      variant="outlined"
                      class="mt-2"
                      placeholder="Current Password"
                      white
                      autocomplete="off"
                      hide-details="auto"
                    ></v-text-field>
                  </div>
                </v-col>

                <v-col cols="12" sm="3">
                  <div class="mb-4">
                    <label>New Password</label>
                    <v-text-field
                      v-model="update.newPassword"
                      type="password"
                      variant="outlined"
                      class="mt-2"
                      placeholder="New Password"
                      hide-details="auto"
                    ></v-text-field>
                  </div>
                </v-col>
                <v-col sm="3">
                  <div>
                    <label>Confirm Password</label>
                    <v-text-field
                      v-model="update.confirmPassword"
                      variant="outlined"
                      type="password"
                      class="mt-2"
                      placeholder="Confirm Password"
                      hide-details="auto"
                    ></v-text-field>
                  </div>
                </v-col>
                <v-col class="text-end pt-0" sm="12">
                  <v-btn
                    type="button"
                    v-on:click="updatePassword"
                    class="primary-btn px-6"
                  >
                    Save
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
      breadcrumbsItems: [
        {
          text: "Admin",
          disabled: true,
          href: "dashboard",
        },
        {
          text: "Profile",
          disabled: false,
          href: "/profile",
        },
      ],
      userRole: localStorage.getItem("role"),
      user: JSON.parse(localStorage.getItem("user")),
      tenant: JSON.parse(localStorage.getItem("tenant")),
      //   tenant: {
      //     name: '',
      //   },
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
      try {
        const payload = {
          user_id: this.user.id,
          username: this.user.username,
          name: this.tenant ? this.tenant.name : "name",
          email: this.user.email,
        };
        let result = await axios.post("/api/update-profile", payload);

        if (this.tenant) {
          console.log(result);
        }

        localStorage.setItem("user", JSON.stringify(result.data.user));
      } catch (error) {
        console.log("error =>", error);
      }
    },
    async updatePassword() {
      try {
        const payload = {
          user_id: this.user.id,
          current_password: this.update.currentPassword,
          password: this.update.newPassword,
          confirm_password: this.update.confirmPassword,
        };
        let result = await axios.post("/api/profile-password", payload);
      } catch (error) {
        console.log("error =>".error);
      }
    },
  },
};
</script>

<style lang="scss">
.avatar-upload {
  max-width: 100px;
  margin: auto;
  position: relative;

  .avatar-preview {
    max-width: 100px;
    height: 100px;
    position: relative;
    border-radius: 50%;
    overflow: hidden;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  .avatar-edit {
    position: absolute;
    right: 0;
    top: 0;
    background-color: #fff;
    border-radius: 50%;
    z-index: 1;

    .edit-image-iconwrapepr {
      input {
        display: none;
      }

      input + label {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 30px;
        height: 30px;
        border-radius: 100%;
        border: 1px solid transparent;
        cursor: pointer;
        font-weight: normal;
        transition: all 0.4s ease-in-out;
      }
    }
  }
}
</style>
