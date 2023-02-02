<template>
    <AdminLayout>
      <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
      <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
        <DataAddRemoveTable
          :tableTitle="tableTitle"
          :dataSource="userData"
          :dataType="dataType"
        >
          <DxPopup
            :show-title="true"
            :width="500"
            :height="550"
            :dragEnabled="false"
            :dragAndResizeArea="true"
            title="Company Info"
          >
            <DxPosition
              at="center"
              my="center"
              v-model:of="positionOf"
              collision="fit"
            />
            <DxToolbarItem
              widget="dxButton"
              toolbar="bottom"
              location="before"
              :options="saveButtonOptions"
            />
            <DxToolbarItem
              widget="dxButton"
              toolbar="bottom"
              location="after"
              :options="closeButtonOptions"
            />
          </DxPopup>
  
          <DxForm :form-data="formData">
            <DxItem :col-count="1" :col-span="2" item-type="group">
              <DxItem v-model="formData.companyname" data-field="Company Name" />
            </DxItem>
  
            <DxItem
              :col-count="1"
              :col-span="2"
              item-type="group"
              caption="Admin Detail"
            >
              <DxItem v-model="formData.name" data-field="name" />
              <DxItem v-model="formData.username" data-field="username" />
              <DxItem v-model="formData.email" data-field="email" />
              <DxItem v-model="formData.password" data-field="password" />
              <DxItem
                :col-span="2"
                :editor-options="{ height: 100 }"
                v-model="formData.address"
                data-field="address"
                editor-type="dxTextArea"
              />
            </DxItem>
          </DxForm>
        </DataAddRemoveTable>
      </div>
    </AdminLayout>
  </template>
  <script>
  import axios from 'axios'
  import DataAddRemoveTable from '../../components/table/DataAddRemoveTable.vue'
  import AdminLayout from '../../layouts/adminLayout.vue'
  import { DxTextArea } from 'devextreme-vue/text-area'
  import {
    DxPopup,
    DxForm,
    DxItem,
    DxButton,
    DxPosition,
    DxToolbarItem,
  } from 'devextreme-vue/data-grid'
  export default {
    name: 'Users',
    components: {
      DataAddRemoveTable,
      AdminLayout,
      DxPopup,
      DxForm,
      DxItem,
      DxTextArea,
      DxButton,
      DxPosition,
      DxToolbarItem,
    },
    props: {
      showFilterRow: {
        type: Boolean,
        default: true,
      },
    },
    data() {
      return {
        formData: {
          companyname: '',
          name: '',
          username: '',
          email: '',
          password: '',
          address: '',
        },
        saveButtonOptions: {
          text: 'Save',
          type: 'success',
  
          onClick: function (e) {
            console.log(this.formData)
          }.bind(this),
        },
        closeButtonOptions: {
          text: 'Close',
          onClick: () => {
            this.popupVisible = false
          },
        },
  
        breadcrumbsItems: [
          {
            text: 'Admin',
            disabled: true,
            href: 'dashboard',
          },
          {
            text: 'Users',
            disabled: false,
            href: '/users',
          },
        ],
        tableTitle: 'User Details',
        userData: [],
        dataType: [
          {
            key: 'name',
            type: 'string',
            caption: 'Company Name',
          },
          // {
          //   key: 'name',
          //   type: 'string',
          //   caption: "xyz"
          // },
          // {
          //   key: 'Password',
          //   type: 'string',
          // },
          {
            key: 'username',
            type: 'string',
            caption: 'User Name',
          },
          {
            key: 'email',
            type: 'string',
            caption: 'Email',
          },
        ],
      }
    },
    methods: {
      async tenantUsers() {
        try {
          const { data } = await axios.get('/api/alltenants')
  
          if (data.success == true) {
            this.userData = data.users
          }
        } catch (error) {
          console.log(error)
        }
      },
    },
    mounted() {
      this.tenantUsers()
    },
  }
  </script>
  