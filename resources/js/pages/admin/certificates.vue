<template>
    <AdminLayout>
        <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
            <DxDataGrid
                :data-source="dataSource"
                :show-borders="true"
                key-expr="id"
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
                        :height="375"
                        title="Certificate Info"
                    />
                    <DxForm>
                        <DxItem :col-count="2" :col-span="2" item-type="group">
                            <DxItem data-field="student_id" />
                        </DxItem>
                        <DxItem :col-count="2" :col-span="2" item-type="group">
                            <DxItem
                                :col-span="2"
                                :editor-options="{ height: 100 }"
                                data-field="description"
                                editor-type="dxTextArea"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Description is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="valid_from"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Valid From Date is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="valid_untill"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message:
                                            'Valid Untill Date is required',
                                    },
                                ]"
                            />
                        </DxItem>
                        <DxItem :visible="false" data-field="info" />
                    </DxForm>
                </DxEditing>

                <DxColumn
                    :width="125"
                    data-field="student_id"
                    caption="Student"
                >
                    <DxLookup
                        :data-source="students"
                        value-expr="id"
                        display-expr="name"
                    />
                </DxColumn>
                <DxColumn data-field="description" />
                <DxColumn data-field="valid_from" data-type="datetime" />
                <DxColumn data-field="valid_untill" data-type="datetime" />
                <DxColumn :visible="false" data-field="info" />
                <DxColumn data-field="Action" type="buttons">
                    <DxButton name="edit" />
                    <DxButton name="delete" />
                    <DxButton
                        hint="Download Certificate"
                        icon="download"
                        @click="downloadPdf"
                    />
                </DxColumn>
                <template #download>
                    <DxButton
                        icon="import"
                        @click="importCSV"
                        title="Import CSV file"
                    />
                </template>
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
    name: "Certificates",
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
        DxButton,
        DxItem,
        DxTextArea,
    },
    data() {
        return {
            students: {},
            breadcrumbsItems: [
                {
                    text: "Admin",
                    disabled: true,
                    href: "dashboard",
                },
                {
                    text: "Certificates",
                    disabled: false,
                    href: "/certificates",
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
                        .get(`/api/all-certificates`, { params })
                        .then(({ data }) => ({
                            data: data.data,
                            totalCount: data.totalCount,
                        }))
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
                insert: (values) => {
                    console.log(values);
                    const payload = {
                        student_id: values.student_id,
                        description: values.description,
                        valid_from: values.valid_from,
                        valid_untill: values.valid_untill,
                    };
                    return axios
                        .post(`/api/create-certificate`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message: "Certificate added successfully!!",
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
                        student_id: values.student_id
                            ? values.student_id
                            : key.student_id,
                        course_id: values.course_id
                            ? values.course_id
                            : key.course_id,
                        description: values.description
                            ? values.description
                            : key.description,
                        valid_from: values.valid_from
                            ? values.valid_from
                            : key.valid_from,
                        valid_untill: values.valid_untill
                            ? values.valid_untill
                            : key.valid_untill,
                        info: values.info ? values.info : key.info,
                    };
                    return axios
                        .post(`/api/update-certificate`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message:
                                        "Certificate updated successfully!!",
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
                        .post(`/api/delete-certificate`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message:
                                        "Certificate deleted successfully!!",
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
    methods: {
        async getStudents() {
            try {
                const { data } = await axios.get(`/api/all-students`);
                this.students = data.data;
            } catch (error) {}
        },
        downloadPdf(params) {
            let student_id = params.row.data.student_id;
            let link = document.createElement("a");
            link.href = `http://127.0.0.1:8000/api/generate-pdf?student_id=${student_id}`;
            link.click();
        },
    },
    mounted() {
        this.getStudents();
    },
};
</script>
