<template>
    <AdminLayout>
        <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
            <div class="text-center">
                <template>
                    <v-row justify="center">
                        <v-dialog v-model="dialog" width="500">
                            <div v-if="successMessage" class="success-message">
                                {{ successMessage }}
                            </div>
                            <v-card>
                                <div
                                    class="d-flex align-center justify-space-between"
                                >
                                    <v-card-title>Import CSV File</v-card-title>
                                    <v-icon
                                        class="mx-4"
                                        color="#333"
                                        @click="dialog = false"
                                    >
                                        mdi-close
                                    </v-icon>
                                </div>
                                <v-divider></v-divider>

                                <v-card-text style="height: 100px">
                                    <input
                                        type="file"
                                        ref="fileInput"
                                        @change="setFile"
                                    />
                                </v-card-text>

                                <v-divider></v-divider>
                                <v-card-actions>
                                    <p
                                        @click="downloadCSV"
                                        class="secondary-dark download-csv-cls"
                                    >
                                        Click Here Download sample CSV file
                                    </p>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        class="primary-red"
                                        text
                                        @click="updateFile"
                                    >
                                        Import CSV
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                </template>
            </div>

            <DxDataGrid
                :data-source="dataSource"
                :remote-operations="true"
                :show-borders="true"
                key-expr="id"
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
                    :allow-adding="true"
                    :allow-deleting="true"
                    :use-icons="true"
                    mode="popup"
                >
                    <DxPopup
                        :show-title="true"
                        :width="700"
                        :height="375"
                        title="Student Info"
                    />
                    <DxForm>
                        <DxItem :col-count="2" :col-span="2" item-type="group">
                            <DxItem data-field="course_id" />
                            <DxItem
                                data-field="name"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Name is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="email"
                                :validation-rules="[{ type: 'email' }]"
                            />
                            <DxItem
                                data-field="birth_date"
                                :validation-rules="[
                                    {
                                        type: 'custom',
                                        validationCallback: validateAge,
                                        message:
                                            'Must be 15 years old or older',
                                    },
                                ]"
                            />
                            <DxItem data-field="birth_place" />
                        </DxItem>
                        <DxItem :visible="false" data-field="info" />
                    </DxForm>
                </DxEditing>

                <DxColumn
                    :allow-exporting="true"
                    :visible="true"
                    :width="125"
                    data-field="course_id"
                    caption="Courses"
                >
                    <DxLookup
                        :data-source="Courses"
                        value-expr="id"
                        display-expr="name"
                    />
                </DxColumn>
                <DxColumn data-field="name" />
                <DxColumn data-field="email" />
                <DxColumn data-field="birth_date" data-type="date" />
                <DxColumn data-field="birth_place" />
                <DxColumn data-field="Action" type="buttons">
                    <DxButton name="edit" />
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
    DxPager,
    DxSearchPanel,
    DxEditing,
    DxExport,
    DxPopup,
    DxLookup,
    DxForm,
    DxToolbar,
} from "devextreme-vue/data-grid";
import { Workbook } from "exceljs";
import { saveAs } from "file-saver-es";
import { exportDataGrid } from "devextreme/excel_exporter";
import { DxTextArea } from "devextreme-vue/text-area";
import { DxItem } from "devextreme-vue/form";
import { DxButton } from "devextreme-vue/button";
import CustomStore from "devextreme/data/custom_store";
import notify from "devextreme/ui/notify";

function isNotEmpty(value) {
    return value !== undefined && value !== null && value !== "";
}

export default {
    name: "students",
    components: {
        AdminLayout,
        DxDataGrid,
        DxButton,
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
    },
    data() {
        return {
            successMessage: "",
            dialog: false,
            file: null,
            Courses: [],
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
                        info: [],
                    };
                    return axios
                        .post(`/api/create-student`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message:
                                        "Student has been added successfully!!",
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
                        course_id: values.course_id
                            ? values.course_id
                            : key.course_id,
                        name: values.name ? values.name : key.name,
                        email: values.email ? values.email : key.email,
                        birth_date: values.birth_date
                            ? values.birth_date
                            : key.birth_date,
                        birth_place: values.birth_place
                            ? values.birth_place
                            : key.birth_place,
                        info: values.info ? values.info : key.info,
                    };
                    return axios
                        .post(`/api/update-student`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message:
                                        "Student has been updated successfully!!",
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
                            throw new Error("Data Loading Error");
                        });
                },
            });
        },
    },
    methods: {
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
            formData.append("file", this.file);
            axios
                .post("/api/upload-student-csv", formData)
                .then(({ data }) => {
                    this.$router.go(this.$router.currentRoute);
                    this.successMessage = "Image updated successfully!";
                })
                .catch((error) => {});
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
            });
            e.cancel = true;
        },
        validateAge(params) {
            let today = new Date();
            let birthDate = new Date(params.value);
            let age = today.getFullYear() - birthDate.getFullYear();

            if (
                today.getMonth() < birthDate.getMonth() ||
                (today.getMonth() === birthDate.getMonth() &&
                    today.getDate() < birthDate.getDate())
            ) {
                age--;
            }

            if (age < 15) {
                return false;
            }

            return true;
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
        },
    },
    mounted() {
        this.getCourses();
    },
};
</script>

<style>
.download-csv-cls {
    cursor: grab;
}
</style>
