<template>
  <AdminLayout>
    <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
    <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
      <DxDataGrid :data-source="dataSource" :show-borders="true" key-expr="id">

        <DxPaging :enabled="false" />

        <DxToolbar>
          <div class="dx-toolbar-section">
            <DxButton icon="upload" @click="openFileDialog">
              Select File
            </DxButton>
          </div>
        </DxToolbar>

        <DxEditing :allow-updating="true" :allow-adding="true" :allow-deleting="true" mode="popup">
          <DxPopup :show-title="true" :width="700" :height="380" title="Student Info" />
          <DxForm>
            <DxItem :col-count="2" :col-span="2" item-type="group">
              <DxItem data-field="course_id" />
            </DxItem>
            <DxItem :col-count="2" :col-span="2" item-type="group">
              <DxItem data-field="name" />
              <DxItem data-field="email" />
              <DxItem data-field="birth_date" />
              <DxItem data-field="birth_place" />
            </DxItem>
          </DxForm>
        </DxEditing>

        <DxColumn :visible="false" data-field="course_id" caption="Course">
          <DxLookup :data-source="courses" value-expr="id" display-expr="name" />
        </DxColumn>
        <DxColumn data-field="name" />
        <DxColumn data-field="email" />
        <DxColumn data-field="birth_date" data-type="date" />
        <DxColumn data-field="birth_place" />
      </DxDataGrid>
    </div>
  </AdminLayout>
</template>
<script>
import axios from "axios";

import AdminLayout from "../../layouts/adminLayout.vue";
import DxToolbar from 'devextreme-vue/toolbar';
import { DxTextArea } from "devextreme-vue/text-area";
import {
  DxPopup,
  DxForm,
  DxItem,
  DxButton,
  DxPosition,
  DxToolbarItem,
  DxDataGrid,
  DxColumn,
  DxPaging,
  DxPager,
  DxEditing,
  DxLookup,
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
    DxLookup,
    DxPager,
    DxPaging,
    DxEditing,
    DxToolbar,
  },

  props: {
    showFilterRow: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      courses: {},
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
            .get(`/api/all-students`, { params })
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
            course_id: values.course_id,
            name: values.name,
            email: values.email,
            birth_date: values.birth_date,
            birth_place: values.birth_place,
          };

          return axios
            .post(`/api/create-student`, payload)
            .then((data) => { })
            .catch((error) => {
              throw new Error("Data Loading Error");
            });
        },
        update: (key, values) => {
          const payload = {
            id: key.id,
            course_id: values.course_id ? values.course_id : key.course_id,
            name: values.name ? values.name : key.name,
            email: values.email ? values.email : key.email,
            birth_date: values.birth_date ? values.birth_date : key.birth_date,
            birth_place: values.birth_place ? values.birth_place : key.birth_place,
          };

          return axios
            .post(`/api/update-student`, payload)
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
            .post(`/api/delete-student`, payload)
            .then((data) => {})
            .catch((error) => {
              throw new Error("Data Loading Error");
            });
        },
      });
    },
  },
  methods: {
    async getData() {
      try {
        const { data } = await axios.get(`/api/all-courses`);
        this.courses = data.data;
      } catch (err) {
        console.log(err)
      }
    },
    openFileDialog() {
      // Add code to open the file dialog and select a file
    }
  },
  mounted() {
    this.getData();
  }
};
</script>
