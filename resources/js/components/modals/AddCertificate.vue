<template>
    <v-card class="course-modal">
        <v-card-title
            class="d-flex justify-space-between align-center px-6 pt-4 pb-2"
        >
            <h3 v-if="certificateData.id">{{ $t("updateCertificate") }}</h3>
            <h3 v-else>{{ $t("addNewCertificate") }}</h3>
            <v-icon icon="mdi-close" @click="closeModal"></v-icon>
        </v-card-title>
        <v-card-text class="px-3 py-0">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <label
                            >{{ $t("studentName") }}
                            <span class="required">*</span></label
                        >
                        <v-select
                            v-model="certificateData.student_id"
                            :items="students"
                            :placeholder="$t('studentName')"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                            item-title="name"
                            item-value="id"
                        ></v-select>
                        <div class="text-start">
                            <span
                                v-if="student_id_error"
                                class="invalid-feedback text-red"
                                >{{ student_id_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" sm="12">
                        <label>
                            {{ $t("description")
                            }}<span class="required">*</span>
                        </label>
                        <v-textarea
                            v-model="certificateData.description"
                            :placeholder="$t('description')"
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
                    <v-col cols="12" md="6">
                        <label>
                            {{ $t("validFrom")
                            }}<span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="certificateData.valid_from"
                            type="datetime-local"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="valid_from_error"
                                class="invalid-feedback text-red"
                                >{{ valid_from_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" md="6">
                        <label>
                            {{ $t("validUntill")
                            }}<span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="certificateData.valid_untill"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            type="datetime-local"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="valid_untill_error"
                                class="invalid-feedback text-red"
                                >{{ valid_untill_error }}</span
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
                @click="addCertificates(certificateData.id ? 'update' : 'add')"
                class="primary-btn"
            >
                {{ $t("save") }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import axios from "axios";
export default {
    props: {
        certificateData: Object,
        students: Object,
    },
    data() {
        return {
            student_id_error: "",
            description_error: "",
            valid_from_error: "",
            valid_untill_error: "",
            message: "",
            refreshGrid: true,
            color: "success",
        };
    },
    methods: {
        closeModal() {
            this.$emit("close");
        },
        async addCertificates(type) {
            this.student_id_error = "";
            this.description_error = "";
            this.valid_from_error = "";
            this.valid_untill_error = "";
            this.validateDates();
            if (!this.valid_from_error && !this.valid_untill_error) {
                try {
                    let payload = {
                        student_id: this.certificateData.student_id,
                        description: this.certificateData.description,
                        valid_from: this.certificateData.valid_from,
                        valid_untill: this.certificateData.valid_untill,
                    };
                    if (type == "update") {
                        payload.id = this.certificateData.id;
                        payload.course_id = this.certificateData.course_id;
                        let result = await axios.post(
                            "/api/update-certificate",
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
                        let result = await axios.post(
                            "/api/create-certificate",
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
                        error.response.data.error.student_id
                    ) {
                        this.student_id_error =
                            "The student field is required.";
                    }
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.description
                    ) {
                        this.description_error =
                            error.response.data.error.description[0];
                    }
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.valid_from
                    ) {
                        this.valid_from_error =
                            error.response.data.error.valid_from[0];
                    }
                    if (
                        error.response.data &&
                        error.response.data.error &&
                        error.response.data.error.valid_untill
                    ) {
                        this.valid_untill_error =
                            error.response.data.error.valid_untill[0];
                    }
                }
            }
        },
        validateDates() {
            if (
                this.certificateData.valid_from &&
                this.certificateData.valid_untill
            ) {
                const date_from = new Date(this.certificateData.valid_from);
                const date_untill = new Date(this.certificateData.valid_untill);
                if (date_from > date_untill) {
                    this.valid_from_error =
                        "Start date cannot be after end date.";
                    this.valid_untill_error =
                        "End date cannot be before start date.";
                } else {
                    this.valid_from_error = "";
                    this.valid_untill_error = "";
                }
            }
        },
    },
};
</script>
