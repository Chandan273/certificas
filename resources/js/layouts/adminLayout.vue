<template>
    <v-app>
        <v-navigation-drawer
    
            v-model="drawer"
            :rail="rail"
            permanent
            class="left-sidebar"
     
        >
            <v-list class="sidebar-logo  pt-2 pb-2">
                <v-list-item class="justify-center">
                    <router-link to="/" class="text-center mainPrimary">
                        <h1 v-if="rail == true">C</h1>
                        <h1 v-if="rail == false" class="sidebar-text">
                            {{ $t("certificates") }}
                        </h1>
                    </router-link>
                </v-list-item>
            </v-list>
            <v-list density="compact" nav v-if="userRole == 'superadmin'">
                <template v-for="(items, i) in adminItems" :key="i">
                    <v-list-item
                        :prepend-icon="items.icon"
                        :title="$t(items.title)"
                        :class="{ active: isActive }"
                        :value="items.title"
                        :to="items.to"
                    ></v-list-item>
                </template>
            </v-list>
            <v-list density="compact" nav v-else>
                <template v-for="(items, i) in companyItems" :key="i">
                    <v-list-item
                        :prepend-icon="items.icon"
                        :title="$t(items.title)"
                        :class="{ active: isActive }"
                        :value="items.title"
                        :to="items.to"
                    ></v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar class="Certificas page-header" elevation="0">
            <v-app-bar-nav-icon
                variant="text"
                @click="rail = !rail"
                icon="mdi-format-align-left"
            ></v-app-bar-nav-icon>
            <v-spacer />
            <!-- profile dropdown  -->
            <v-menu class="profile-dropdown-menu">
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" class="header_button px-0 px-1" :ripple="false">
                        <div class="nav-profile d-flex">
                            <v-icon size="30" class="mr-1">
                                mdi-account-circle
                            </v-icon>
                        </div>
                        &nbsp;
                        <span class="text-capitalize">{{ user.username }}</span>
                        <v-icon>mdi-chevron-down</v-icon>
                    </v-btn>
                </template>
                <v-list class="profile-dropdown">
                    <v-list-item router to="/profile">
                        <v-list-item-title>
                            <v-icon class="mr-2">mdi-account</v-icon>
                            {{ $t("profile") }}
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="logout()">
                        <v-list-item-title>
                            <v-icon class="mr-2">mdi-logout</v-icon>
                            {{ $t("logout") }}
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <v-main>
            <v-container class="px-8">
                <slot></slot>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
import Auth from "../Auth.js";
import axios from "axios";

export default {
    name: "adminLayout",
    components: {},
    data: () => ({
        user: JSON.parse(localStorage.getItem("user")),
        userRole: localStorage.getItem("role"),
        drawer: true,
        miniVariant: false,
        rail: false,
        group: null,
        isActive: true,
        closeOnContentClick: true,
        adminItems: [{ title: "tenants", icon: "mdi-account", to: "/tenants" }],
        companyItems: [
            { title: "courses", icon: "mdi-school", to: "/courses" },
            {
                title: "customers",
                color:"#FFF",
                icon: "mdi-account-multiple",
                to: "/customers",
            },
            { title: "students", icon: "mdi-library", to: "/students" },
            {
                title: "certificates",
                icon: "mdi-file-document",
                to: "/certificates",
            },
        ],
    }),
    methods: {
        async logout() {
            axios
                .post("/api/logout")
                .then(({ data }) => {
                    if (data) {
                        Auth.logout(); //reset local storage
                        localStorage.clear();
                        this.$router.push("/login");
                    }
                })
                .catch((error) => {
                    if ((error.response.status = "401")) {
                        Auth.logout(); //reset local storage
                        localStorage.clear();
                        this.$router.push("/login");
                    }
                });
        },
    },
    mounted() {
        const defaultLanguage = localStorage.getItem("language");
        if (defaultLanguage) {
            this.$i18n.locale = defaultLanguage;
        }
    },
    watch: {
        group() {
            this.drawer = false;
        },
    },
};
</script>


