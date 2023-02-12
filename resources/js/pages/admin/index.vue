<template>
  <AdminLayout>
    <v-breadcrumbs class="ps-0 pt-0" :items="breadcrumbsItems"></v-breadcrumbs>
    <div>
      <DashboardGrid />
    </div>

    <v-row>
      <v-col cols="12" md="4">
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
          <DoughnutChart title="Customers" />
        </div>
      </v-col>
      <v-col cols="12" md="8">
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
          <Order />
        </div>
      </v-col>
      <v-col cols="12" lg="6">
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
          <FullStack />
        </div>
      </v-col>
     
    </v-row>
  </AdminLayout>
</template>

<script>
import DoughnutChart from '../../components/charts/DoughnutChart.vue'
import FullStack from '../../components/charts/FullStack.vue'
import DashboardGrid from '../../components/grid/DashboardGrid.vue'
import Order from '../../components/charts/Order.vue'
import AdminLayout from '../../layouts/adminLayout.vue'
export default {
  name: 'Dashboard',
  components: {
    DashboardGrid,
    DoughnutChart,
    FullStack,
    Order,
    OrderTable,
    AdminLayout
  },

  data() {
    return {
      pieChartData: [12, 52, 23, 54, 54654],
      breadcrumbsItems: [
        {
          text: 'Admin',
          disabled: true,
          href: 'dashboard',
        },
        {
          text: 'Dashboard',
          disabled: false,
          href: '/dashboard',
        },
      ],
    }
  },
}
</script>
<style scoped>

</style>
