<template>
    <v-card class="course-modal">
        <v-card-title
            class="d-flex justify-space-between align-center px-6 pt-4 pb-2"
        >
            <h3>Add certificate</h3>
            <v-icon icon="mdi-close" @click="closeModal"></v-icon>
        </v-card-title>

        <v-card-text class="px-3 py-0">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <label>Student <span class="required">*</span></label>
                        <v-select
                            v-model="certificateData.name"
                            :items="studentData.map((s) => s.name)"
                            :item-text="studentData.map((s) => s.name)"
                            :item-value="studentData.map((s) => s.id)"
                            placeholder="Student Name"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                        ></v-select>
                    </v-col>
                    <v-col cols="12" sm="12">
                        <label
                            >Description <span class="required">*</span></label
                        >
                        <v-textarea
                            v-model="certificateData.description"
                            placeholder="Description"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                            required
                        ></v-textarea>
                    </v-col>
                    <v-col cols="12" md="6">
                        <label
                            >Valid from <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="certificateData.valid_from"
                            type="datetime-local"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                        <label
                            >Valid untill <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="certificateData.valid_untill"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            type="datetime-local"
                            required
                        ></v-text-field>
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
                @click="addCertificates(certificateData.id ? 'update' : 'add')"
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
        certificateData: Object,
        studentData: Object,
    },
    data() {
        return {};
    },
    methods: {
        closeModal() {
            this.$emit("close");
        },
        async addCertificates(type) {
            this.code_error = "";
            this.name_error = "";
            this.date_from_error = "";
            this.date_untill_error = "";
            this.description_error = "";

            try {
                let payload = {
                    student_id: this.certificateData.student_id,
                    course_id: this.certificateData.course_id,
                    description: this.certificateData.description,
                    valid_from: this.certificateData.valid_from,
                    valid_untill: this.certificateData.valid_untill,
                };
                if (type == "update") {
                    payload.id = this.certificateData.id;

                    console.log(payload);

                    let result = await axios.post(
                        "/api/update-certificate",
                        payload
                    );
                } else {
                    let result = await axios.post(
                        "/api/create-certificate",
                        payload
                    );
                }
            } catch (error) {
                console.log(error);
            }
        },
    },
};
</script>
