<template>
    <v-card class="course-modal">
        <v-card-title
            class="d-flex justify-space-between align-center px-6 pt-4 pb-2"
        >
            <h3 v-if="studentData.id">{{ $t("updateStudent") }}</h3>
            <h3 v-else>{{ $t("addNewStudent") }}</h3>
            <v-icon icon="mdi-close" @click="closeModal"></v-icon>
        </v-card-title>
        <v-card-text class="px-3 py-0">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <label
                            >customersData
                            <span class="required">*</span></label
                        >
                        <v-select
                            v-model="studentData.customer_id"
                            :items="customers"
                            placeholder="Customer Name"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            item-title="name"
                            item-value="id"
                        ></v-select>
                        <div class="text-start">
                            <span
                                v-if="course_id_error"
                                class="invalid-feedback text-red"
                                >{{ course_id_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <label
                            >{{ $t("courses") }}
                            <span class="required">*</span></label
                        >

                        <v-select
                            v-model="studentData.course_id"
                            :items="Courses"
                            :placeholder="$t('courseName')"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            item-title="name"
                            item-value="id"
                        ></v-select>
                        <div class="text-start">
                            <span
                                v-if="course_id_error"
                                class="invalid-feedback text-red"
                                >{{ course_id_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <label
                            >{{ $t("name") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="studentData.name"
                            :placeholder="$t('studentName')"
                            class="mt-2"
                            hide-details="auto"
                            variant="outlined"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="name_error"
                                class="invalid-feedback text-red"
                                >{{ name_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" md="6">
                        <label
                            >{{ $t("email") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="studentData.email"
                            :placeholder="$t('studentEmail')"
                            type="email"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="email_error"
                                class="invalid-feedback text-red"
                                >{{ email_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" md="6">
                        <label
                            >{{ $t("dob") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="studentData.birth_date"
                            :placeholder="$t('dob')"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            type="date"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="birth_date_error"
                                class="invalid-feedback text-red"
                                >{{ birth_date_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="6" sm="6">
                        <label
                            >{{ $t("birthPlace") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="studentData.birth_place"
                            :placeholder="$t('birthPlace')"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="birth_place_error"
                                class="invalid-feedback text-red"
                                >{{ birth_place_error }}</span
                            >
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-card-text>
        <v-card-actions class="px-6 py-3">
            <v-spacer></v-spacer>
            <v-btn
                @click="closeModal()"
                variant="outlined"
                class="primary-border-btn"
            >
                {{ $t("close") }}
            </v-btn>
            <v-btn
                @click="addStudent(studentData.id ? 'update' : 'add')"
                class="primary-btn"
            >
                {{ $t("save") }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>
<script>
export default {
    props: {
        studentData: Object,
        customers: Object,
        Courses: Object,
    },
    data() {
        return {
            course_id_error: "",
            name_error: "",
            email_error: "",
            birth_date_error: "",
            birth_place_error: "",
            message: "",
            refreshGrid: true,
            color: "success",
        };
    },
    methods: {
        closeModal() {
            this.$emit("close");
        },
        async addStudent(type) {
            this.course_id_error = "";
            this.name_error = "";
            this.email_error = "";
            this.birth_date_error = "";
            this.birth_place_error = "";
            this.dobErrorMessage();
            if (!this.birth_date_error) {
                try {
                    let payload = {
                        customer_id: this.studentData.customer_id,
                        course_id: this.studentData.course_id,
                        name: this.studentData.name,
                        email: this.studentData.email,
                        birth_date: this.studentData.birth_date,
                        birth_place: this.studentData.birth_place,
                    };
                    if (type == "update") {
                        payload.id = this.studentData.id;
                        let result = await this.axios.post(
                            "/api/update-student",
                            payload
                        );
                        if (result.data.statusCode == 200) {
                            this.message = result.data.message;
                            this.$emit("data-passed", {
                                snackbar: true,
                                message: this.message,
                                color: this.color,
                                refreshGrid: this.refreshGrid,
                            });

                            this.closeModal();
                        }
                    } else {
                        let result = await this.axios.post(
                            "/api/create-student",
                            payload
                        );
                        if (result.data.statusCode == 200) {
                            this.message = result.data.message;
                            this.$emit("data-passed", {
                                snackbar: true,
                                message: this.message,
                                color: this.color,
                                refreshGrid: this.refreshGrid,
                            });

                            this.closeModal();
                        }
                    }
                } catch (error) {
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.course_id
                    ) {
                        this.course_id_error = "The Course field is required.";
                    }
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.name
                    ) {
                        this.name_error = error.response.data.error.name[0];
                    }
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.email
                    ) {
                        this.email_error = error.response.data.error.email[0];
                    }
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.birth_date
                    ) {
                        this.birth_date_error =
                            error.response.data.error.birth_date[0];
                    }
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.birth_place
                    ) {
                        this.birth_place_error =
                            error.response.data.error.birth_place[0];
                    }
                }
            }
        },
        dobErrorMessage() {
            if (this.studentData.birth_date) {
                let dob = new Date(this.studentData.birth_date);
                let ageInYears = Math.floor((new Date() - dob) / 31557600000);
                if (ageInYears < 15) {
                    this.birth_date_error =
                        "You must be at least 15 years old.";
                } else {
                    this.birth_date_error = "";
                }
            }
        },
    },
};
</script>
