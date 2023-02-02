<template>
  <v-app-bar class="hidden-xs-and-down main-toolbar" fixed>
    <v-container d-flex align-center class="main-container">
      <v-toolbar-items class="nav-items d-flex align-center justify-space-between">
        <router-link to="">Certificas</router-link>
        <v-list class="d-flex align-center">
          <v-list-item-group active-class="text-deep-blue text-accent-4" class="d-flex align-center">
            <v-list-item to="/" dense>
              <v-list-item-title>Home</v-list-item-title>
            </v-list-item>
            <v-list-item to="/about" dense>
              <v-list-item-title>About us</v-list-item-title>
            </v-list-item>
            <v-list-item to="/service" dense>
              <v-list-item-title>Services</v-list-item-title>
            </v-list-item>
            <v-list-item to="/blog" dense>
              <v-list-item-title>Blog</v-list-item-title>
            </v-list-item>
            <v-list-item to="/contact" dense>
              <v-list-item-title>Contact Us</v-list-item-title>
            </v-list-item>
            <v-list-item @click="$router.push('/login')">
              Log In
            </v-list-item>

          </v-list-item-group>
        </v-list>
      </v-toolbar-items>
    </v-container>
  </v-app-bar>
</template>
<script>
export default {
  name: "HeaderVue",
  middleware: "auth",
  data() {
    return {
      isActive: false,
      dialog: false,
      nav: [],
      currentRole: null,
    };
  },
  computed: {

  },
  methods: {

  },
  mounted() {

  },
};
</script>
<style lang="scss" scoped>
.fixed-bar {
  position: sticky;
  position: -webkit-sticky;
  top: 0em;
  z-index: 2;
  background: #fff;
  border-bottom: 1px solid var(--main-primary);
}

.home-logo {
  color: #fff;
  background: #ff0000;
  padding: 5px 15px;
  font-weight: 600;
  border-radius: 4px;
  clip-path: polygon(0% 0%, 90% 0%, 100% 20%, 100% 100%, 0% 100%);
}

.main-toolbar.theme--light.v-app-bar.v-toolbar.v-sheet {
  background-color: #fff;
  height: 82px !important;

  .nav-items {
    .v-list-item {
      padding: 0;
      margin: 0px 4px;

      &:before {
        opacity: 0;
        max-width: 82%;
        margin: auto;
        border-bottom: 2px solid var(--main-primary);
        background-color: transparent;
      }

      .v-list-item__title {
        color: #000;
        font-size: 15px;
        font-weight: 500;
        text-transform: uppercase;
        padding: 0 10px;
        margin-left: 0px;
        margin-right: 0px;
        min-height: 40px;
        display: flex;
        align-items: center;
      }
    }

    .v-list-item--active.v-list-item {
      .v-list-item__title {
        color: #ff0000;
      }

      &::before {
        opacity: 1;
      }
    }
  }

  .login-btns.v-btn {
    background-color: var(--main-primary);
    color: #fff;
    overflow: hidden;
  }
}

.border-btn.v-btn {
  background: transparent;
  border: 1px solid var(--main-primary);
  color: var(--main-primary);
}

@media (max-width: 1200px) {
  .main-toolbar {
    .v-toolbar__title {
      img {
        width: 170px;
      }
    }

    .nav-items {
      .v-list-item {
        padding: 0 12px;
        min-height: 36px;

        .v-list-item__title {
          font-size: 13px;
          margin-left: 0;
          margin-right: 0;
          padding: 0;
        }
      }
    }

    .border-btn.v-btn,
    .login-btns.v-btn {
      padding: 0 8px;
    }
  }
}

@media (min-width: 1025px) {
  .mobile-list {
    padding-top: 10px;
  }
}

@media (max-width: 1024px) {
  header.v-toolbar {
    background-color: #fff !important;
    position: fixed;
    z-index: 2;
    left: 0;
    width: 100%;
    top: 0;
  }

  .main-toolbar {
    .v-toolbar__title {
      img {
        width: 170px;
      }
    }

    .nav-items {
      .v-list-item {
        padding: 0 12px;
        min-height: 36px;

        .v-list-item__title {
          font-size: 13px;
          margin-left: 0;
          margin-right: 0;
        }
      }
    }

    .border-btn.v-btn,
    .login-btns.v-btn {
      padding: 0 8px;
    }
  }
}

@media (max-width: 991px) {
  .main-toolbar {
    display: none;
  }
}
</style>
