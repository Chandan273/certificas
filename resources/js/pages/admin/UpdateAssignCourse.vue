<template>
<AdminLayout>
    <v-snackbar v-model="snackbar" :value="true" absolute right top location="top right" :color="color" timeout="4000">
        <v-icon icon="mdi-check-circle"> </v-icon> {{ message }}
    </v-snackbar>
    <v-breadcrumbs class="ps-0" :items="translatedItems"></v-breadcrumbs>
    <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card profile-page">
        <v-row v-if="courseAssign">
            <v-col cols="12" md="12">
                <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6">
                    <v-form ref="myForm">
                        <v-row align-items-center>
                            <v-col lg="3">
                                <div class="mb-4">
                                    <label for="fname">{{ $t("selectCustomer") }}</label>
                                    <v-select class="student-select" clearable variant="outlined" chips :placeholder="$t('selectCustomer')" v-model="selectedCustomer" :items="customers" item-title="name" item-value="id" multiple hide-details @change="selCustomer">
                                    </v-select>
                                    <div class="text-start">
                                        <span v-if="select_student_error" class="invalid-feedback text-red">{{ select_student_error }}</span>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-form>
                    <DxDataGrid class="assign-course-table" id="gridContainer" :ref="dataGridRefKey" :data-source="students" :remote-operations="true" :show-borders="true" :selected-row-keys="selectedRowKeys" key-expr="id" @selection-changed="onSelectionChanged" :selection-filter="selectionFilter">
                        <DxScrolling mode="infinite" />
                        <DxSelection mode="multiple" :deferred="true" show-check-boxes-mode="always" />
                        <DxColumn data-field="name" :caption="$t('studentName')" />
                        <DxColumn data-field="email" :caption="$t('email')" />
                        <DxColumn data-field="birth_date" data-type="date" :caption="$t('birthDate')" />
                        <DxColumn data-field="birth_place" :caption="$t('birthPlace')" />
                    </DxDataGrid>
                </div>

                <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6">
                    <v-row>
                        <v-col class="text-end pt-0 mb-6" sm="12">
                            <v-btn type="button" v-on:click="assignCourse" class="primary-btn px-6">
                                {{ "update" }}
                            </v-btn>
                        </v-col>
                    </v-row>
                </div>
            </v-col>
        </v-row>
        <v-row v-else>
            <v-col cols="12" md="12">
                <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6">
                  <v-alert density="compact" type="warning" title="Please First Assign Tenant Course"></v-alert>
                </div>
            </v-col>
        </v-row>
    </div>
</AdminLayout>
</template>

<script>
const dataGridRefKey = "my-data-grid";
import AdminLayout from "../../layouts/adminLayout.vue";
import {
    DxDataGrid,
    DxColumn,
    DxItem,
    DxSelection,
    DxScrolling,
} from "devextreme-vue/data-grid";

export default {
    name: "AssignCourse",
    data() {
        return {
            dataGridRefKey,
            snackbar: false,
            message: "",
            color: "success",
            select_student_error: "",
            customers: [],
            students: [],
            selectedCustomer: [],
            selectedStudent: [],
            selectedRowsData: [],
            selectionFilter: null,
            courseAssign: "",
        };
    },
    components: {
        AdminLayout,
        DxDataGrid,
        DxColumn,
        DxItem,
        DxSelection,
        DxScrolling,
    },
    computed: {
        translatedItems() {
            return [{
                    text: this.$t("admin"),
                    disabled: true,
                    href: "dashboard",
                },
                {
                    text: "Update Assign Course",
                    disabled: false,
                    href: "/update-assign-course",
                },
            ];
        },
        dataGrid: function () {
            return this.$refs[dataGridRefKey].instance;
        },
        selectedRowKeys() {
            return this.selectedRowsData.map((student) => student.id);
        },
    },
    methods: {
        async getCustomers() {
            const result = await this.axios.get(`/api/all-customers`);
            this.customers = result.data.data;
        },
        async getStudents() {
            try {
                const params = {
                    course_id: this.$route.params.id
                };
                const {data} = await this.axios.get(`/api/all-students`);
                
                if(data.statusCode = 200){
                    const students = data.data;
                    this.students = students;
                    const selectedRow = this.selectionFilter;
                    this.dataGrid.selectRows(selectedRow);
                }
            } catch (error) { }
        },
        async getTenantCourse() {
            try {
                const payload = {
                    course_id: this.$route.params.id,
                };
                const {
                    data
                } = await this.axios.post(`/api/tenant-course`, payload);
                if (data.statusCode == 200) {   
                    const selectedArr = data.tenantCourse.students;
                    var selectedIntArr = selectedArr.replace(/"/g, '').split(',').map(value => parseInt(value));
                    this.selectionFilter = selectedIntArr;
                    this.courseAssign = true;
                } else {
                    this.courseAssign = false;
                }
            } catch (error) {}
        },
        async assignCourse() {
            this.select_course_error = "";
            this.select_student_error = "";
            try {
                let selectedStudentString = this.selectedStudent.join(',');
                const payload = {
                    course_id: this.$route.params.id,
                    students: selectedStudentString,
                };
                const result = await this.axios.post(
                    "/api/create-tenant-courses",
                    payload
                );

                if (result.data.statusCode == 200) {
                    this.snackbar = true;
                    this.message = result.data.message;
                    localStorage.removeItem("selectedCourseID");
                    setTimeout(() => this.$router.push({
                        path: "/students"
                    }), 3000);
                }
            } catch (error) {
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.students
                ) {
                    this.select_student_error = error.response.data.error.students[0];
                }
            }
        },
        async selCustomer(obj) {
            this.selectedRowsData = this.students.filter((student) =>
                obj.includes(student.customer_id)
            );
        },
        onSelectionChanged() {
            const selectedStudent = [];
            this.dataGrid.getSelectedRowsData().then((selectedRowsData) => {
                this.select_student_error = "";
                selectedRowsData.forEach((row) => {
                    selectedStudent.push(row.id)
                });
                this.selectedStudent = selectedStudent;
            });
        },
    },
    mounted() {
        this.getTenantCourse();
        this.getCustomers();
        this.getStudents();
        this.selCustomer(this.selectedCustomer);
    },
    watch: {
        selectedCustomer(selectedCustomerIds) {
            this.selCustomer(selectedCustomerIds);
        },
    },
};
</script>

<style>
select {
    border: 1px solid #ccc;
    padding: 6px 12px;
}

#gridContainer {
    height: 440px;
}
</style>
