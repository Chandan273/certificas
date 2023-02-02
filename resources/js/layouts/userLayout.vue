<template>
  <v-app dark>
    <v-main>
      <Header />
      <slot></slot>
      <Footer />
    </v-main>

  </v-app>
</template>
<script>
import Header from "../components/common/Header.vue";
import Footer from "../components/common/Footer.vue";
export default {
  name: "UserLayout",
  components: { Header, Footer },
  data() {
    return {
      clipped: false,
      drawer: false,
      fixed: false,
      miniVariant: false,
      right: true,
      rightDrawer: false,
      title: "Dashboard",
    };
  },
};
</script>

<style>
.home-main {
  padding-top: 82px;
}
</style>
