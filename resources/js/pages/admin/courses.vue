<template>
    <AdminLayout>
        <v-snackbar
            v-model="snackbar"
            :value="true"
            absolute
            right
            top
            location="top right"
            :color="color"
            timeout="3000"
        >
        <v-icon icon="mdi-check-circle"> </v-icon> {{ message }}
        </v-snackbar>
        <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 course-card widget-card">
            <v-btn
                class="primary-btn add-btn"
                elevation="0"
                @click="
                    courseData = {};
                    courseDialog = true;
                "
            >
                <v-icon icon="mdi-plus"></v-icon> Add Course
            </v-btn>
            <v-dialog v-model="courseDialog" persistent max-width="700px">
                <AddCourses
                    @close="closeModal"
                    @data-passed="refreshGrid"
                    :courseData="courseData"
                ></AddCourses>
            </v-dialog>
            <DxDataGrid
                :ref="dataGridRefKey"
                class="tenants-table"
                :data-source="dataSource"
                :show-borders="true"
                key-expr="id"
                :remote-operations="true"
            >
                <DxSearchPanel :visible="true" />
                <DxEditing
                    :allow-updating="true"
                    :allow-adding="true"
                    :allow-deleting="true"
                    :use-icons="true"
                >
                </DxEditing>
                <DxPaging :page-size="10" />
                <DxPager
                    :show-page-size-selector="true"
                    :allowed-page-sizes="[10, 25, 50, 100]"
                />
                <DxColumn data-field="code" />
                <DxColumn data-field="name" />
                <DxColumn data-field="description" />
                <DxColumn data-field="date_from" data-type="date" />
                <DxColumn data-field="date_untill" data-type="date" />
                <DxColumn data-field="Action" type="buttons" alignment="left">
                    <DxButton
                        name="edit"
                        hint="Edit"
                        icon="edit"
                        @click="editCourse"
                    />
                    <DxButton name="delete" />
                </DxColumn>
            </DxDataGrid>
        </div>
    </AdminLayout>
</template>
<script>
const dataGridRefKey = "my-data-grid";

import axios from "axios";
import AdminLayout from "../../layouts/adminLayout.vue";
import {
    DxDataGrid,
    DxColumn,
    DxPaging,
    DxPager,
    DxSearchPanel,
    DxEditing,
    DxPopup,
    DxLookup,
    DxForm,
    DxButton,
} from "devextreme-vue/data-grid";
import { DxTextArea } from "devextreme-vue/text-area";
import { DxItem } from "devextreme-vue/form";
import CustomStore from "devextreme/data/custom_store";
import notify from "devextreme/ui/notify";
import AddCourses from "../../components/modals/AddCourse.vue";

function isNotEmpty(value) {
    return value !== undefined && value !== null && value !== "";
}

export default {
    name: "courses",
    components: {
        AdminLayout,
        DxDataGrid,
        DxColumn,
        DxPaging,
        DxPager,
        DxSearchPanel,
        DxEditing,
        DxPopup,
        DxLookup,
        DxForm,
        DxItem,
        DxTextArea,
        DxButton,
        AddCourses,
    },
    data() {
        return {
            courseDialog: false,
            courseData: {},
            dataGridRefKey,
            snackbar: false,
            message: "",
            color: "success",
            breadcrumbsItems: [
                {
                    text: "Admin",
                    disabled: true,
                    href: "dashboard",
                },
                {
                    text: "Courses",
                    disabled: false,
                    href: "/courses",
                },
            ],
        };
    },
    methods: {
        closeModal() {
            this.courseDialog = false;
        },
        refreshGrid(data) {
            this.snackbar = data.snackbar;
            this.color = data.color;
            this.message = data.message;
            this.dataGrid.refresh();
        },
        editCourse(params) {
            this.courseDialog = true;
            this.courseData = params.row.data;
        },
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
                        .get(`/api/all-courses`, { params })
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
                        code: values.code,
                        name: values.name,
                        date_from: values.date_from,
                        date_untill: values.date_untill,
                        description: values.description,
                    };
                    return axios
                        .post(`/api/create-course`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message:
                                        "Course has been added successfully!!",
                                    width: 300,
                                    shading: true,
                                },
                                "success",
                                2000
                            );
                            return data;
                        })
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
                update: (key, values) => {
                    const payload = {
                        id: key.id,
                        tenant_id: key.tenant_id,
                        certificate_layout_id: key.certificate_layout_id,
                        code: values.code ? values.code : key.code,
                        name: values.name ? values.name : key.name,
                        description: values.description
                            ? values.description
                            : key.description,
                        date_from: values.date_from
                            ? values.date_from
                            : key.date_from,
                        date_untill: values.date_untill
                            ? values.date_untill
                            : key.date_untill,
                    };
                    return axios
                        .post(`/api/update-course`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message: "Course Updated Successfully!!",
                                    width: 300,
                                    shading: true,
                                },
                                "success",
                                3000
                            );
                            return data;
                        })
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
                remove: (key) => {
                    const payload = {
                        id: key.id,
                    };
                    return axios
                        .post(`/api/delete-course`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message: "Course Deleted Successfully!!",
                                    width: 300,
                                    shading: true,
                                },
                                "success",
                                3000
                            );
                            return data;
                        })
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
            });
        },
        dataGrid: function () {
            return this.$refs[dataGridRefKey].instance;
        },
    },
};
</script>
