<template>
    <AdminLayout>
        <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
            <DxDataGrid
                :data-source="dataSource"
                :show-borders="true"
                key-expr="id"
                :remote-operations="true"
            >
                <DxPaging :enabled="false" />
                <DxSearchPanel :visible="true" />
                <DxEditing
                    :allow-updating="true"
                    :allow-adding="true"
                    :allow-deleting="true"
                    :use-icons="true"
                    mode="popup"
                >
                    <DxPopup
                        :show-title="true"
                        :width="700"
                        :height="400"
                        title="Course Info"
                    />
                    <DxForm>
                        <DxItem :col-count="2" :col-span="2" item-type="group">
                            <DxItem
                                data-field="code"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Course Code is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="name"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Course Name is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="date_from"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Start date is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="date_untill"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'End date is required',
                                    },
                                ]"
                            />
                            <DxItem
                                :col-span="2"
                                :editor-options="{ height: 100 }"
                                data-field="description"
                                editor-type="dxTextArea"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message:
                                            'Course Description is required',
                                    },
                                ]"
                            />
                        </DxItem>
                    </DxForm>
                </DxEditing>
                <DxColumn data-field="code" />
                <DxColumn data-field="name" />
                <DxColumn data-field="description" />
                <DxColumn data-field="date_from" data-type="date" />
                <DxColumn data-field="date_untill" data-type="date" />
                <DxColumn data-field="Action" type="buttons">
                    <DxButton name="edit" />
                    <DxButton name="delete" />
                </DxColumn>
            </DxDataGrid>
        </div>
    </AdminLayout>
</template>
<script>
import axios from "axios";
import AdminLayout from "../../layouts/adminLayout.vue";
import {
    DxDataGrid,
    DxColumn,
    DxPaging,
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
        DxSearchPanel,
        DxEditing,
        DxPopup,
        DxLookup,
        DxForm,
        DxItem,
        DxTextArea,
        DxButton,
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
                    text: "Courses",
                    disabled: false,
                    href: "/courses",
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
    },
};
</script>
