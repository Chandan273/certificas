<template>
    <v-app-bar title="Certificas" class="Certificas">
        <v-app-bar-nav-icon
            variant="text"
            @click.stop="drawer = !drawer"
            icon="mdi-format-align-left"
        ></v-app-bar-nav-icon>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" :mini-variant="miniVariant" permanent>
        <v-list density="compact" nav>
            <template v-for="(items, i) in adminItems" :key="i">
                <v-list-item
                    :prepend-icon="items.icon"
                    :title="items.title"
                    :class="{ active: isActive }"
                    :value="items.title"
                    :to="items.to"
                ></v-list-item>
            </template>
        </v-list>

        <v-list-item
            link
            prepend-icon="mdi mdi-logout"
            title="Logout"
            @click="logout()"
        ></v-list-item>
    </v-navigation-drawer>
</template>

<script>
import Auth from "../Auth.js";
import axios from "axios";

export default {
    name: "Navigation",
    data() {
        return {
            drawer: true,
            miniVariant: true,
            rail: true,
            group: null,
            isActive: true,
            adminItems: [
                // { title: 'Dashboard', icon: 'mdi-view-dashboard', to: '/Dashboard' },
                { title: "Users", icon: "mdi-account", to: "/users" },
                {
                    title: "Customers",
                    icon: "mdi-account-multiple",
                    to: "/customers",
                },
                { title: "Course", icon: "mdi-account", to: "/course" },
                {
                    title: "Students",
                    icon: "mdi-book-open-variant",
                    to: "/students",
                },
                {
                    title: "Certificates",
                    icon: "mdi-file-document",
                    to: "/certificates",
                },
            ],
        };
    },
    methods: {
        async logout() {
            axios
                .post("/api/logout")
                .then(({ data }) => {
                    if (data) {
                        Auth.logout(); //reset local storage
                        this.$router.push("/login");
                    }
                })
                .catch((error) => {});
        },
    },
};
</script>
