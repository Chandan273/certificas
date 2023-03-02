<template>
    <AdminLayout>
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
                                            $t("selectCustomer")
                                        }}</label>

                                        <v-select
                                            :placeholder="$t('selectCustomer')"
                                            v-model="selectedCustomer"
                                            :items="customers"
                                            item-title="name"
                                            item-value="id"
                                        ></v-select>
                                    </div>
                                </v-col>
                                <v-col lg="4">
                                    <div class="mb-4">
                                        <label for="fname">{{
                                            $t("selectStudent")
                                        }}</label>
                                        <v-select
                                            :placeholder="$t('selectStudent')"
                                            v-model="selectedStudent"
                                            :items="students"
                                            item-title="name"
                                            item-value="id"
                                        ></v-select>
                                    </div>
                                </v-col>
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
                </v-col>
            </v-row>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "../../layouts/adminLayout.vue";

export default {
    name: "AssignCourse",
    data() {
        return {
            customers: {},
            students: {},
            courses: {},
            selectedCustomer: null,
            selectedStudent: null,
            selectedCourse: null,
        };
    },
    components: {
        AdminLayout,
    },
    methods: {
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
        async assignCourse(){
            //const result = await this.axios.post('/api/');
        }
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
                    text: this.$t("Assign Course"),
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
};
</script>

<style>
select {
    border: 1px solid #ccc;
    padding: 6px 12px;
}
</style>
