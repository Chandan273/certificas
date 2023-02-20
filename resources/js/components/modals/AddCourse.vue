<template>
    <v-card class="course-modal">
        <v-card-title
            class="d-flex justify-space-between align-center px-6 pt-4 pb-2"
        >
            <h3 v-if="courseData.id">Update Course</h3>
            <h3 v-else>Add New Course</h3>
            <v-icon icon="mdi-close" @click="closeModal"></v-icon>
        </v-card-title>
        <v-card-text class="px-3 py-0">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <label>Code <span class="required">*</span></label>
                        <v-text-field
                            v-model="courseData.code"
                            placeholder="Course code"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="code_error"
                                class="invalid-feedback text-red"
                                >{{ code_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <label>Name <span class="required">*</span></label>
                        <v-text-field
                            v-model="courseData.name"
                            placeholder="Course name"
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
                        <label>Date from <span class="required">*</span></label>
                        <v-text-field
                            v-model="courseData.date_from"
                            type="date"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="date_from_error"
                                class="invalid-feedback text-red"
                                >{{ date_from_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" md="6">
                        <label
                            >Date untill <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="courseData.date_untill"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            type="date"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="date_untill_error"
                                class="invalid-feedback text-red"
                                >{{ date_untill_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" sm="12">
                        <label
                            >Description <span class="required">*</span></label
                        >
                        <v-textarea
                            v-model="courseData.description"
                            placeholder="Course description"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                            required
                        ></v-textarea>
                        <div class="text-start">
                            <span
                                v-if="description_error"
                                class="invalid-feedback text-red"
                                >{{ description_error }}</span
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
                Close
            </v-btn>
            <v-btn
                @click="addCourse(courseData.id ? 'update' : 'add')"
                class="primary-btn"
            >
                Save
            </v-btn>
        </v-card-actions>
    </v-card>
</template>
<script>
import axios from "axios";

export default {
    props: {
        courseData: Object,
    },
    data() {
        return {
            code_error: "",
            name_error: "",
            date_from_error: "",
            date_untill_error: "",
            description_error: "",
            message: "",
            refreshGrid: true,
            color: "success",
        };
    },
    methods: {
        closeModal() {
            this.$emit("close");
        },
        async addCourse(type) {
            this.code_error = "";
            this.name_error = "";
            this.date_from_error = "";
            this.date_untill_error = "";
            this.description_error = "";
            this.validateDates();

            if (!this.date_from_error && !this.date_until_error) {
                try {
                    let payload = {
                        code: this.courseData.code,
                        name: this.courseData.name,
                        date_from: this.courseData.date_from,
                        date_untill: this.courseData.date_untill,
                        description: this.courseData.description,
                    };
                    if (type == "update") {
                        payload.id = this.courseData.id;

                        let result = await axios.post(
                            "/api/update-course",
                            payload
                        );

                        if(result.data.statusCode == 200){
                            this.message = result.data.message;
                            this.$emit('data-passed', {
                                snackbar: true,
                                message: this.message,
                                color: this.color,
                                refreshGrid: this.refreshGrid,
                            });
                            
                            this.closeModal();
                        }
                    } else {
                        let result = await axios.post(
                            "/api/create-course",
                            payload
                        );

                        if(result.data.statusCode == 200){
                            this.message = result.data.message;
                            this.$emit('data-passed', {
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
                        error.response.data.error.code
                    ) {
                        this.code_error = error.response.data.error.code[0];
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
                        error.response.data.error.date_from
                    ) {
                        this.date_from_error =
                            error.response.data.error.date_from[0];
                    }
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.date_untill
                    ) {
                        this.date_untill_error =
                            error.response.data.error.date_untill[0];
                    }
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.description
                    ) {
                        this.description_error =
                            error.response.data.error.description[0];
                    }
                }
            }
        },
        validateDates() {
            if (this.courseData.date_from && this.courseData.date_untill) {
                const date_from = new Date(this.courseData.date_from);
                const date_untill = new Date(this.courseData.date_untill);
                if (date_from > date_untill) {
                    this.date_from_error =
                        "Start date cannot be after end date.";
                    this.date_untill_error =
                        "End date cannot be before start date.";
                } else {
                    this.date_from_error = "";
                    this.date_untill_error = "";
                }
            }
        },
    },
};
</script>
