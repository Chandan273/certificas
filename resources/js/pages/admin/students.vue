<template>
    <AdminLayout>
      <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
      <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
      <DxDataGrid
        :data-source="dataSource"
        :show-borders="true"
        key-expr="id"
      >
        <DxPaging :enabled="false"/>
        <DxEditing
          :allow-updating="true"
          :allow-adding="true"
          :allow-deleting="true"
          mode="popup"
        >
          <DxPopup
            :show-title="true"
            :width="700"
            :height="600"
            title="Customer Info"
          />
          <DxForm>
            <DxItem
              :col-count="2"
              :col-span="2"
              item-type="group"
            >
              <DxItem data-field="number"/>
              <DxItem data-field="name"/>
              <DxItem data-field="contact"/>
              <DxItem data-field="organisation_number"/>
              <DxItem data-field="email"/>
              <DxItem data-field="www" />
  
            <DxItem
              :col-count="2"
              :col-span="2"
              item-type="group"
              caption="Customer Address"
            >
  
            <DxItem
                :col-span="2"
                :editor-options="{ height: 100 }"
                data-field="address"
                editor-type="dxTextArea"
              />
            </DxItem>
  
              <DxItem data-field="phone"/>
              <DxItem data-field="zip"/>
              <DxItem data-field="city"/>
              <DxItem data-field="country"/>
            </DxItem>
          </DxForm>
        </DxEditing>
  
        <DxColumn :width="70" data-field="number" />
        <DxColumn data-field="name"/>
        <DxColumn data-field="contact"/>
        <DxColumn data-field="organisation_number"/>
        <DxColumn data-field="email"/>
        <DxColumn data-field="www" caption="Website"/>
        <DxColumn :visible="false" data-field="phone" />
        <DxColumn data-field="address" />
        <DxColumn :visible="false" data-field="zip" />
        <DxColumn :visible="false" data-field="city" />
        <DxColumn :visible="false" data-field="country" />
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
    name: "students",
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
                    text: "Students",
                    disabled: false,
                    href: "/students",
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
              .get(`/api/all-customers`, { params })
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
              number: values.number,
              name: values.name,
              contact: values.contact,
              organisation_number: values.organisation_number,
              email: values.email,
              www: values.www,
              phone: values.phone,
              address: values.address,
              zip: values.zip,
              city: values.city,
              country: values.country,
            };
  
            return axios
              .post(`/api/create-customer`, payload)
              .then((data) => { })
              .catch((error) => {
                throw new Error("Data Loading Error");
              });
          },
          update: (key, values) => {
            const payload = {
              id: key.id,
              tenant_id: key.tenant_id,
              number: values.number ? values.number : key.number,
              name: values.name ? values.name : key.name,
              contact: values.contact ? values.contact : key.contact,
              organisation_number: values.organisation_number ? values.organisation_number : key.organisation_number,
              email: values.email ? values.email : key.email,
              www: values.www ? values.www : key.www,
              phone: values.phone ? values.phone : key.phone,
              address: values.address ? values.address : key.address,            
              zip: values.zip ? values.zip : key.zip,
              city: values.city ? values.city : key.city,
              country: values.country ? values.country : key.country,
            };
  
            return axios
              .post(`/api/update-customer`, payload)
              .then((data) => { })
              .catch((error) => {
                throw new Error("Data Loading Error");
              });
          },
  
          remove: (key) => {
            const payload = {
              id: key.id,
            };
            return axios
              .post(`/api/delete-customer`, payload)
              .then((data) => { })
              .catch((error) => {
                throw new Error("Data Loading Error");
            });
          },
        });
      },
    },
  };
  </script>
  