<template>
    <AdminLayout>
      <div>
        <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
          <DataAddRemoveTable
            :tableTitle="tableTitle"
            :dataSource="dataSource"
            :dataType="dataType"
          >
            <DxPopup :show-title="true" :width="600" :height="525" title="Customers Info" />
  
            <DxForm>
              <DxItem :col-count="2" :col-span="2" item-type="group">
                <DxItem :col-count="1" :col-span="1" data-field="Number" />
                <DxItem :col-count="1" :col-span="1" data-field="Name" />
                <DxItem :col-count="1" :col-span="1" data-field="Contact" />
                <DxItem :col-count="1" :col-span="1" data-field="OrgID" />
                <DxItem :col-count="1" :col-span="1" data-field="Email" />
                <DxItem :col-count="1" :col-span="1" data-field="Website" />
                <DxItem :col-count="1" :col-span="1" data-field="Phone" />
                <DxItem
                  :col-span="2"
                  :editor-options="{ height: 100 }"
                  data-field="address"
                  editor-type="dxTextArea"
                />
              </DxItem>
            </DxForm>
          </DataAddRemoveTable>
        </div>
      </div>
    </AdminLayout>
  </template>
  <script>
  import AdminLayout from "../../layouts/adminLayout.vue";
  import DataAddRemoveTable from "../../components/table/DataAddRemoveTable.vue";
  import DxSelectBox from "devextreme-vue/select-box";
  import { DxPopup, DxForm, DxItem } from "devextreme-vue/data-grid";
  export default {
    name: "Customer",
    components: {
      DataAddRemoveTable,
      DxSelectBox,
      AdminLayout,
      DxPopup,
      DxForm,
      DxItem,
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
            text: "Customer",
            disabled: false,
            href: "/customer",
          },
        ],
        tableTitle: "Customer Details",
        dataSource: [
          {
            Number: "6545463",
            Name: "Jack",
            Contact: "Bruce",
            OrgID: "1131",
            Email: "example@yopmail.com",
            Website: "www.examp.com",
            Phone: "912115451",
          },
          {
            Number: "09876543",
            Name: "Jack",
            Contact: "912115451",
            OrgID: "1131",
            Email: "example@yopmail.com",
            Website: "www.examp.com",
            Phone: "912115451",
          },
          {
            Number: "9898765",
            Name: "Jack",
            Contact: "912115451",
            OrgID: "1131",
            Email: "example@yopmail.com",
            Website: "www.examp.com",
            Phone: "912115451",
          },
          {
            Number: "2342445",
            Name: "Jack",
            Contact: "912115451",
            OrgID: "1131",
            Email: "example@yopmail.com",
            Website: "www.examp.com",
            Phone: "912115451",
          },
          {
            Number: "876554451",
            Name: "Jack",
            Contact: "912115451",
            OrgID: "1131",
            Email: "example@yopmail.com",
            Website: "www.examp.com",
            Phone: "912115451",
          },
        ],
  
        dataType: [
          {
            key: "Number",
            type: "",
          },
          {
            key: "Name",
            type: "",
          },
          {
            key: "Contact",
            type: "",
          },
          {
            key: "OrgID",
            type: "",
            caption: "Organisation Number",
          },
          {
            key: "Email",
            type: "",
          },
          {
            key: "Website",
            type: "",
          },
          {
            key: "Phone",
            type: "",
          },
        ],
      };
    },
  };
  </script>
  