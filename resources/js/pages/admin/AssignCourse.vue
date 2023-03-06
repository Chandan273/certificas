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
            timeout="4000"
        >
            <v-icon icon="mdi-check-circle"> </v-icon> {{ message }}
        </v-snackbar>
        <v-breadcrumbs class="ps-0" :items="translatedItems"></v-breadcrumbs>
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card profile-page">
            <v-row>
                <v-col cols="12" md="12">
                    <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 personal-info">
                        <h3 class="mb-2 pb-2">
                            {{ "Assign Course" }}
                        </h3>
                        <v-form ref="myForm">
                            <v-row align-items-center>
                                <v-col lg="4">
                                    <div class="mb-4">
                                        <label for="fname">{{
                                            $t("selectCourse")
                                        }}</label>
                                        <v-select
                                            :placeholder="$t('selectCourse')"
                                            v-model="selectedCourse"
                                            :items="courses"
                                            item-title="name"
                                            item-value="id"
                                        ></v-select>
                                        <div class="text-start">
                                            <span
                                                v-if="select_course_error"
                                                class="invalid-feedback text-red"
                                                >{{ select_course_error }}</span
                                            >
                                        </div>
                                    </div>
                                </v-col>
                                <v-col lg="4">
                                    <div class="mb-4">
                                        <label for="fname">{{
                                            $t("selectCustomer")
                                        }}</label>

                                        <v-select
                                            clearable
                                            chips
                                            :placeholder="$t('selectCustomer')"
                                            v-model="selectedCustomer"
                                            :items="customers"
                                            item-title="name"
                                            item-value="id"
                                            multiple
                                            @change="selCustomer"
                                        ></v-select>
                                        <div class="text-start">
                                            <span
                                                v-if="select_customer_error"
                                                class="invalid-feedback text-red"
                                                >{{
                                                    select_customer_error
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </v-col>
                                <v-col lg="4">
                                    <div class="mb-4">
                                        <label for="fname">{{
                                            $t("selectStudent")
                                        }}</label>
                                        <v-select
                                            clearable
                                            chips
                                            :placeholder="$t('selectStudent')"
                                            v-model="selectedStudent"
                                            :items="students"
                                            item-title="name"
                                            item-value="id"
                                            multiple
                                        ></v-select>
                                        <div class="text-start">
                                            <span
                                                v-if="select_student_error"
                                                class="invalid-feedback text-red"
                                                >{{
                                                    select_student_error
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </v-col>
                                <v-col class="text-end pt-0 mb-6" sm="12">
                                    <v-btn
                                        type="button"
                                        v-on:click="assignCourse"
                                        class="primary-btn px-6"
                                    >
                                        {{ $t("save") }}
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </div>
                    <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 personal-info">
                        <v-form ref="addForm">
                            <v-row align-items-center>
                                <v-col lg="12">
                                    <div class="mb-4">
                                        <v-btn
                                            class="primary-btn add-btn"
                                            elevation="0"
                                            @click="expand = !expand"
                                        >
                                            <v-icon icon="mdi-plus"></v-icon>
                                            {{ $t("addStudent") }}
                                        </v-btn>
                                        <v-expand-transition>
                                            <div v-show="expand">
                                                <AddNewStudent
                                                    @close="closeModal"
                                                    @data-passed="refreshPage"
                                                ></AddNewStudent>
                                            </div>
                                        </v-expand-transition>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-form>
                    </div>
                </v-col>
            </v-row>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "../../layouts/adminLayout.vue";
import AddNewStudent from "../../components/modals/AddNewStudent.vue";

export default {
    name: "AssignCourse",
    data() {
        return {
            expand: false,
            snackbar: false,
            message: "",
            color: "success",
            customers: [],
            students: [],
            courses: [],
            selectedCustomer: [],
            selectedStudent: null,
            selectedCourse: null,
            select_course_error: "",
            select_customer_error: "",
            select_student_error: "",
        };
    },
    components: {
        AdminLayout,
        AddNewStudent,
    },
    methods: {
        closeModal() {
            this.expand = false;
        },
        refreshPage(data) {
            this.snackbar = data.snackbar;
            this.color = data.color;
            this.message = data.message;
            //this.getStudents();
        },
        async getCustomers() {
            const result = await this.axios.get(`/api/all-customers`);
            this.customers = result.data.data;
        },
        async getStudents() {
            try {
                const { data } = await this.axios.get(`/api/all-students`);
                this.students = data.data;
            } catch (error) {}
        },
        async getCourses() {
            try {
                const { data } = await this.axios.get(`/api/student-courses`);
                this.courses = data.courses;
            } catch (err) {}
        },
        async assignCourse() {
            this.select_course_error = "";
            this.select_customer_error = "";
            this.select_student_error = "";
            try {
                const payload = {
                    course_id: this.selectedCourse,
                    students: this.selectedStudent,
                };
                const result = await this.axios.post(
                    "/api/create-tenant-courses",
                    payload
                );

                if (result.data.statusCode == 200) {
                    this.snackbar = true;
                    this.message = result.data.message;
                }
            } catch (error) {
                console.log(error);
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.course_id
                ) {
                    this.select_course_error = "The Course field is required.";
                }
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.students
                ) {
                    this.select_student_error =
                        error.response.data.error.students[0];
                }
            }
        },
        async selCustomer(data) {
            const payload = {
                customer_id: data,
            };
            const result = await this.axios.post("/api/get-students", payload);
            this.students = result.data.data;
            const studentIds = this.students.map((student) => student.id);
            this.selectedStudent = studentIds;

            // Call the getStudents method to update the students list
            await this.getStudents();
        },
    },
    computed: {
        translatedItems() {
            return [
                {
                    text: this.$t("admin"),
                    disabled: true,
                    href: "dashboard",
                },
                {
                    text: "Assign Course",
                    disabled: false,
                    href: "/assign-course",
                },
            ];
        },
    },
    mounted() {
        this.getCourses();
        this.getStudents();
        this.getCustomers();
    },
    watch: {
        selectedCustomer(selValue) {
            this.selCustomer(selValue);
        },
    },
};
</script>

<style>
select {
    border: 1px solid #ccc;
    padding: 6px 12px;
}
</style>
