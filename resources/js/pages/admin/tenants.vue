<template>
  <AdminLayout>
    <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
    <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
      <DxDataGrid
        id="gridContainer"
        :allow-column-reordering="true"
        :word-wrap-enabled="true"
        :key-expr="keyExpr"
        :data-source="dataSource"
        :show-borders="true"
        :remote-operations="true"
        :hover-state-enabled="true"
      >
        <DxColumn
          data-field="username"
          data-type="string"
          data-sort="false"
          caption="User Name"
        />
        <DxColumn data-field="tenant.name" data-type="string" caption="Tenant Name" />
        <DxColumn data-field="email" data-type="string" caption="Email" />
        <DxColumn data-field="tenant.paid_untill" data-type="date" caption="Paid Untill" />
        <DxEditing
          :allow-updating="true"
          :allow-deleting="true"
          :allow-adding="true"
          :use-icons="true"
          mode="popup"
        >
          <DxPopup :show-title="true" :width="600" :height="300" title="Tenant Info" />
        </DxEditing>
        <DxPaging :page-size="10" />
        <DxPager
          :show-page-size-selector="true"
          :allowed-page-sizes="[10, 25, 50, 100]"
        />
      </DxDataGrid>
    </div>
  </AdminLayout>
</template>
<script>
import axios from "axios";

import AdminLayout from "../../layouts/adminLayout.vue";
import { DxTextArea } from "devextreme-vue/text-area";
import {
  DxPopup,
  DxForm,
  DxItem,
  DxButton,
  DxPosition,
  DxToolbarItem,
} from "devextreme-vue/data-grid";
import {
  DxDataGrid,
  DxColumn,
  DxPaging,
  DxPager,
  DxEditing,
} from "devextreme-vue/data-grid";
import CustomStore from "devextreme/data/custom_store";
function isNotEmpty(value) {
  return value !== undefined && value !== null && value !== "";
}

export default {
  name: "tenants",
  components: {
    AdminLayout,
    DxPopup,
    DxForm,
    DxItem,
    DxTextArea,
    DxButton,
    DxPosition,
    DxToolbarItem,
    DxDataGrid,
    DxColumn,
    DxPager,
    DxPaging,
    DxEditing,
  },

  props: {
    showFilterRow: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      breadcrumbsItems: [
        {
          text: "Admin",
          disabled: true,
          href: "dashboard",
        },
        {
          text: "Tenants",
          disabled: false,
          href: "/tenants",
        },
      ],
    };
  },
  computed: {
    dataSource: () => {
      return new CustomStore({
        load: (loadOptions) => {
          let params = {};
          [
            "skip",
            "take",
            "requireTotalCount",
            "requireGroupCount",
            "sort",
            "filter",
          ].forEach((i) => {
            if (i in loadOptions && isNotEmpty(loadOptions[i])) {
              params[i] = `${JSON.stringify(loadOptions[i])}`;
            }
          });

          return axios
            .get(`/api/all-tenants`, { params })
            .then(({ data }) => ({
              data: data.data,
              totalCount: data.totalCount,
            }))
            .catch((error) => {
              throw new Error("Data Loading Error");
            });
        },
        insert: (values) => {
          const payload = {
            name: values.tenant.name,
            email: values.email,
            username: values.username,
            paid_untill: values.tenant.paid_untill,
          };

          return axios
            .post(`/api/create-tenant`, payload)
            .then((data) => {})
            .catch((error) => {
              throw new Error("Data Loading Error");
            });
        },
        update: (key, values) => {
          console.log(key, values);
          const payload = {
            id: key.id,
            name: values.tenant && values.tenant.name ? values.tenant.name : key.tenant.name,
            email: values.email ? values.email : key.email,
            username: values.username ? values.username: key.username,
            paid_untill: values.tenant && values.tenant.paid_untill ? values.tenant.paid_untill : key.tenant.paid_untill,    
          };

          return axios
            .post(`/api/update-tenant`, payload)
            .then((data) => {})
            .catch((error) => {
              throw new Error("Data Loading Error");
            });
        },

        remove: (key) => {
          const payload = {
            id: key.id,
          };
          return axios
            .post(`/api/delete-tenant`, payload)
            .then((data) => {})
            .catch((error) => {
              throw new Error("Data Loading Error");
            });
        },
      });
    },
  },
};
</script>
