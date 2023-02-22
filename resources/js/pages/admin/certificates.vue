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
                    certificateData = {};
                    certificateDialog = true;
                "
            >
                <v-icon icon="mdi-plus"></v-icon>{{ $t("addCertificate") }}
            </v-btn>
            <v-dialog v-model="certificateDialog" persistent max-width="700px">
                <AddCertificate
                    @close="closeModal"
                    @data-passed="refreshGrid"
                    :certificateData="certificateData"
                    :students="students"
                ></AddCertificate>
            </v-dialog>
            <DxDataGrid
                :ref="dataGridRefKey"
                class="tenants-table"
                :data-source="dataSource"
                :show-borders="true"
            >
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
                </DxEditing>
                <DxPaging :page-size="10" />
                <DxPager
                    :show-page-size-selector="true"
                    :allowed-page-sizes="[10, 25, 50, 100]"
                />
                <DxColumn
                    data-field="student.name"
                    :caption="$t('studentName')"
                />
                <DxColumn
                    data-field="description"
                    :caption="$t('description')"
                />
                <DxColumn
                    data-field="valid_from"
                    data-type="datetime"
                    :caption="$t('validFrom')"
                />
                <DxColumn
                    data-field="valid_untill"
                    data-type="datetime"
                    :caption="$t('validUntill')"
                />
                <DxColumn :visible="false" data-field="info" />
                <DxColumn
                    data-field="Action"
                    type="buttons"
                    :caption="$t('action')"
                >
                    <DxButton
                        name="edit"
                        hint="Edit"
                        icon="edit"
                        @click="editCertificate"
                    />
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
import AddCertificate from "../../components/modals/AddCertificate.vue";
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
        DxPager,
        DxSearchPanel,
        DxEditing,
        DxPopup,
        DxLookup,
        DxForm,
        DxButton,
        DxItem,
        DxTextArea,
        AddCertificate,
    },
    data() {
        return {
            certificateDialog: false,
            students: {},
            certificateData: {},
            dataGridRefKey,
            snackbar: false,
            message: "",
            color: "success",
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
        breadcrumbsItems() {
            return [
                {
                    text: this.$t("admin"),
                    disabled: true,
                    href: "dashboard",
                },
                {
                    text: this.$t("certificates"),
                    disabled: false,
                    href: "/certificates",
                },
            ];
        },
        dataGrid: function () {
            return this.$refs[dataGridRefKey].instance;
        },
    },
    methods: {
        closeModal() {
            this.certificateDialog = false;
        },
        refreshGrid(data) {
            this.snackbar = data.snackbar;
            this.color = data.color;
            this.message = data.message;
            this.dataGrid.refresh();
        },
        async getStudents() {
            try {
                const { data } = await axios.get(`/api/all-students`);
                this.students = data.data;
            } catch (error) {}
        },
        editCertificate(params) {
            this.certificateDialog = true;
            this.certificateData = params.row.data;
        },
        downloadPdf(params) {
            let student_id = params.row.data.student_id;
            let link = document.createElement("a");
            let url = window.location.origin;
            link.href = `${url}/api/generate-pdf?student_id=${student_id}`;
            link.click();
        },
    },
    mounted() {
        this.getStudents();
    },
};
</script>
