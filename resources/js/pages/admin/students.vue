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
        <div
            class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 course-card students-table widget-card"
        >
            <v-btn
                class="primary-btn add-btn"
                elevation="0"
                @click="
                    studentData = {};
                    studentDialog = true;
                "
            >
                <v-icon icon="mdi-plus"></v-icon> {{ $t("addStudent") }}
            </v-btn>
            <v-dialog v-model="studentDialog" persistent max-width="700px">
                <AddStudent
                    @close="closeModal"
                    @data-passed="refreshGrid"
                    :studentData="studentData"
                    :Courses="Courses"
                ></AddStudent>
            </v-dialog>
            <div class="text-center">
                <template>
                    <v-row justify="center">
                        <v-dialog v-model="dialog" width="500">
                            <v-card class="course-modal">
                                <v-card-title
                                    class="d-flex align-center justify-space-between"
                                    ><h3>{{ $t("importCsvFile") }}</h3>
                                    <v-icon @click="dialog = false">
                                        mdi-close
                                    </v-icon></v-card-title
                                >

                                <v-card-text class="import-csv py-4">
                                    <label>{{ $t("importFile") }}</label>
                                    <input
                                        type="file"
                                        ref="fileInput"
                                        @change="setFile"
                                        class="file-input"
                                    />
                                </v-card-text>

                                <v-card-actions class="px-6">
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        variant="outlined"
                                        title="Download sample CSV file"
                                        class="primary-border-btn"
                                        text
                                        @click="downloadCSV"
                                    >
                                        <v-icon icon="mdi-download"></v-icon>
                                        {{ $t("downloadSample") }}
                                    </v-btn>

                                    <v-btn
                                        class="primary-red"
                                        text
                                        @click="updateFile"
                                    >
                                        {{ $t("importCSV") }}
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                </template>
            </div>

            <DxDataGrid
                :ref="dataGridRefKey"
                class="tenants-table"
                :data-source="dataSource"
                :remote-operations="true"
                :show-borders="true"
                @exporting="onExporting"
            >
                <DxPaging :page-size="10" />
                <DxPager
                    :show-page-size-selector="true"
                    :allowed-page-sizes="[10, 25, 50, 100]"
                />
                <DxExport
                    :enabled="true"
                    :allow-export-selected-data="false"
                    :formats="['CSV']"
                />
                <DxSearchPanel :visible="true" />
                <DxEditing
                    :allow-updating="true"
                    :allow-adding="false"
                    :allow-deleting="true"
                    :use-icons="true"
                >
                </DxEditing>

                <DxColumn
                    :allow-exporting="true"
                    :visible="true"
                    :width="100"
                    data-field="course_id"
                    :caption="$t('courseId')"
                    alignment="left"
                >
                    <DxLookup
                        :data-source="Courses"
                        value-expr="id"
                        display-expr="id"
                    />
                </DxColumn>
                <DxColumn data-field="name" :caption="$t('name')" />
                <DxColumn data-field="email" :caption="$t('email')" />
                <DxColumn
                    data-field="birth_date"
                    data-type="date"
                    :caption="$t('birthDate')"
                />
                <DxColumn
                    data-field="birth_place"
                    :caption="$t('birthPlace')"
                />
                <DxColumn
                    data-field="Action"
                    type="buttons"
                    alignment="left"
                    :caption="$t('action')"
                >
                    <DxButton
                        name="edit"
                        hint="Edit"
                        icon="edit"
                        @click="editStudent"
                    />
                    <DxButton name="delete" />
                </DxColumn>
                <DxColumn :visible="false" data-field="info" />
                <DxToolbar>
                    <DxItem location="after" template="importAction" />
                    <DxItem name="addRowButton" />
                    <DxItem name="searchPanel" />
                    <DxItem name="exportButton" />
                    <DxItem name="columnChooserButton" />
                    <DxItem name="applyFilterButton" />
                </DxToolbar>
                <template #importAction>
                    <v-btn
                        elevation="0"
                        class="primary-btn ml-2 import-btn px-1"
                        @click="importCSV"
                        ><v-icon size="large"> mdi-import </v-icon>
                    </v-btn>
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
    DxExport,
    DxPopup,
    DxLookup,
    DxForm,
    DxToolbar,
    DxButton,
    DxTextArea,
    DxItem,
} from "devextreme-vue/data-grid";
import { Workbook } from "exceljs";
import { saveAs } from "file-saver-es";
import notify from "devextreme/ui/notify";
import { exportDataGrid } from "devextreme/excel_exporter";
import CustomStore from "devextreme/data/custom_store";
import AddStudent from "../../components/modals/AddStudent.vue";
function isNotEmpty(value) {
    return value !== undefined && value !== null && value !== "";
}
export default {
    name: "students",
    components: {
        AdminLayout,
        DxDataGrid,
        DxColumn,
        DxPaging,
        DxPager,
        DxSearchPanel,
        DxEditing,
        DxPopup,
        DxExport,
        DxLookup,
        DxForm,
        DxItem,
        DxTextArea,
        DxToolbar,
        DxButton,
        AddStudent,
    },
    data() {
        return {
            dialog: false,
            studentDialog: false,
            file: null,
            snackbar: false,
            message: "",
            color: "success",
            Courses: [],
            studentData: {},
            dataGridRefKey,
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
                remove: (key) => {
                    const payload = {
                        id: key.id,
                    };
                    return axios
                        .post(`/api/delete-student`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message:
                                        "Student has been deleted successfully!!",
                                    width: 300,
                                    shading: true,
                                },
                                "success",
                                2000
                            );
                            return data;
                        })
                        .catch((error) => {
                            //throw new Error("Data Loading Error");
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
                    text: this.$t("students"),
                    disabled: false,
                    href: "/students",
                },
            ];
        },
        dataGrid: function () {
            return this.$refs[dataGridRefKey].instance;
        },
    },
    methods: {
        closeModal() {
            this.studentDialog = false;
        },
        refreshGrid(data) {
            this.snackbar = data.snackbar;
            this.color = data.color;
            this.message = data.message;
            this.dataGrid.refresh();
        },
        editStudent(params) {
            this.studentDialog = true;
            this.studentData = params.row.data;
        },
        importCSV() {
            this.dialog = !this.dialog;
        },
        async getCourses() {
            try {
                const { data } = await axios.get(`/api/student-courses`);
                this.Courses = data.courses;
            } catch (err) {}
        },
        setFile(event) {
            this.file = event.target.files[0];
        },
        updateFile() {
            let formData = new FormData();
            let input = this.$refs.fileInput;
            let file = input.files[0];
            if (!file) {
                this.snackbar = true;
                this.color = "error";
                this.message = "Please choose CSV file";
                return;
            }
            if (file.type !== "text/csv") {
                this.snackbar = true;
                this.color = "error";
                this.message = "Please upload valid CSV file";
                return;
            }
            formData.append("file", file);
            axios
                .post("/api/upload-student-csv", formData)
                .then(({ data }) => {
                    this.dataGrid.refresh();
                    this.snackbar = true;
                    this.color = "success";
                    this.message = "CSV file records updated successfully!";
                })
                .catch((error) => {
                    // Handle any errors that occur during the upload process
                });
            this.$refs.fileInput.value = "";
            this.dialog = false;
        },
        onExporting(e) {
            const workbook = new Workbook();
            const worksheet = workbook.addWorksheet("Employees");
            exportDataGrid({
                component: e.component,
                worksheet: worksheet,
                autoFilterEnabled: true,
            }).then(() => {
                workbook.csv.writeBuffer().then((buffer) => {
                    saveAs(
                        new Blob([buffer], {
                            type: "application/octet-stream",
                        }),
                        "students.csv"
                    );
                });
                this.snackbar = true;
                this.color = "success";
                this.message = "CSV exported successfully!";
            });
            e.cancel = true;
        },
        downloadCSV() {
            const csvContent =
                "Courses,Name,Email,Birth date,Birth place\n1,abc,abc@gmail.com,2023-02-08T05:30:00+05:30,Abc Location\n1,pqr,pqr@gmail.com,2023-02-08T05:30:00+05:30,Pqr Location\n1,xyz,xyz@gmail.com,2023-02-08T05:30:00+05:30,Xyz Location";
            const encodedUri = encodeURI(
                `data:text/csv;charset=utf-8,${csvContent}`
            );
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "import.csv");
            link.click();
            if (link) {
                this.snackbar = true;
                this.color = "success";
                this.message = "Sample CSV file downloaded successfully!";
            }
        },
    },
    mounted() {
        this.getCourses();
    },
};
</script>
<style>
.import-btn {
    min-width: 34px;
}
</style>
