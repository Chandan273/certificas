<template>
  <v-row>
    <v-col cols="12" sm="6" md="3">
      <div class="grid-widget">
        <h4 class="mb-2">{{ items.order }}</h4>
        <div class="d-flex justify-space-between align-center flex-wrap">
          <div class="box">
            <h3>{{ items.price }}</h3>
            <span class="mb-0 last-week">Compared to last week</span>
          </div>
          <div class="d-flex align-center range">
            <v-icon class="value-up">mdi-arrow-up-bold-circle-outline</v-icon>
            <p class="ma-0 ms-2 value-up">+23.09%</p>
          </div>
        </div>
      </div>
    </v-col>
    <v-col cols="12" sm="6" md="3">
      <div class="grid-widget">
        <h4 class="mb-2">{{ items.services }}</h4>
        <div class="d-flex justify-space-between align-center flex-wrap">
          <div class="box">
            <h3>{{ items.servicesCount }}</h3>
            <span class="mb-0 last-week">Compared to last week</span>
          </div>
          <div class="d-flex align-center range">
            <v-icon class="value-up">mdi-arrow-up-bold-circle-outline</v-icon>
            <p class="ma-0 ms-2 value-up">+15.09%</p>
          </div>
        </div>
      </div>
    </v-col>
    <v-col cols="12" sm="6" md="3">
      <div class="grid-widget">
        <h4 class="mb-2">{{ items.customers }}</h4>
        <div class="d-flex justify-space-between align-center flex-wrap">
          <div class="box">
            <h3>{{ items.price }}</h3>
            <span class="mb-0 last-week">Compared to last week</span>
          </div>
          <div class="d-flex align-center range">
            <v-icon class="value-up">mdi-arrow-up-bold-circle-outline</v-icon>
            <p class="ma-0 ms-2 value-up">+12%</p>
          </div>
        </div>
      </div>
    </v-col>

    <v-col cols="12" sm="6" md="3">
      <div class="grid-widget">
        <h4 class="mb-2">{{ items.earnings }}</h4>
        <div class="d-flex justify-space-between align-center flex-wrap">
          <div class="box">
            <h3>$ 5000</h3>
            <span class="mb-0 last-week">Compared to last week</span>
          </div>
          <div class="d-flex align-center range">
            <v-icon class="value-down"
              >mdi-arrow-down-bold-circle-outline</v-icon
            >
            <p class="ma-0 ms-2 value-down">-03.09%</p>
          </div>
        </div>
      </div>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "DashboardGrid",
  data() {
    return {
      items: {
        order: "TOTAL ORDERS",
        earnings: "TOTAL  EARNINGS",
        customers: "TOTAL CUSTOMERS ",
        services: "TOTAL SERVICES ",
        price: 1000,
        servicesCount: 213,
      },
    };
  },
};
</script>
<style lang="scss">
.grid-widget {
  background: #fff;
  border-radius: 10px;
  border-bottom: 1px solid #e0e3e8;
  padding: 1rem;

  h4 {
    font-weight: 400;
  }

  .box {
    span.last-week {
      font-size: 14px;
      font-weight: 500;
    }

    h3 {
      color: var(--main-primary);
    }
  }

  .range {
    .value-up {
      color: #008d00;
    }
    .value-down {
      color: var(--main-primary);
    }
  }
}
</style>
