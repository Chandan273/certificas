<template>
  <v-card class="course-modal">
    <v-card-title
      class="d-flex justify-space-between align-center px-6 pt-4 pb-2"
    >
      <h3 v-if="courseData.id">{{ $t("updateCourse") }}</h3>
      <h3 v-else>{{ $t("addNewCourse") }}</h3>
      <v-icon icon="mdi-close" @click="closeModal"></v-icon>
    </v-card-title>
    <v-card-text class="px-3 py-0">
      <v-container>
        <v-row>
          <v-col cols="12" sm="6" md="6">
            <label>{{ $t("code") }} <span class="required">*</span></label>
            <v-text-field
              v-model="courseData.code"
              :placeholder="$t('code')"
              variant="outlined"
              hide-details="auto"
              class="mt-2"
              required
            ></v-text-field>
            <div class="text-start">
              <span v-if="code_error" class="invalid-feedback text-red">{{
                code_error
              }}</span>
            </div>
          </v-col>
          <v-col cols="12" sm="6" md="6">
            <label>{{ $t("name") }} </label>
            <v-text-field
              v-model="courseData.name"
              :placeholder="$t('courseName')"
              class="mt-2"
              hide-details="auto"
              variant="outlined"
              required
            ></v-text-field>
            <div class="text-start">
              <span v-if="name_error" class="invalid-feedback text-red">{{
                name_error
              }}</span>
            </div>
          </v-col>
          <v-col cols="12" md="6">
            <label>{{ $t("dateFrom") }} </label>
            <v-text-field
              v-model="courseData.date_from"
              type="date"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
              required
            ></v-text-field>
            <div class="text-start">
              <span v-if="date_from_error" class="invalid-feedback text-red">{{
                date_from_error
              }}</span>
            </div>
          </v-col>
          <v-col cols="12" md="6">
            <label>{{ $t("dateUntill") }} </label>
            <v-text-field
              v-model="courseData.date_untill"
              variant="outlined"
              hide-details="auto"
              class="mt-2"
              type="date"
              required
              :min="courseData.date_from"
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
            <label>{{ $t("description") }} </label>
            <v-textarea
              v-model="courseData.description"
              :placeholder="$t('courseDescription')"
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
      <v-checkbox
        class="tenant-checkbox"
        v-if="!courseData.id"
        v-model="selectTenantCourse"
        label="Click To Assign Course to Tenant"
        value="1"
        hide-details
        color="#008cff"
      >
      </v-checkbox>
      <v-spacer></v-spacer>
      <v-btn
        @click="closeModal()"
        variant="outlined"
        class="primary-border-btn"
      >
        {{ $t("close") }}
      </v-btn>
      <v-btn
        @click="addCourse(courseData.id ? 'update' : 'add')"
        class="primary-btn"
      >
        {{ $t("save") }}
      </v-btn>
      <v-btn
      title=" To assign course to student click here"
        v-if="!courseData.id"
        @click="courseModal()"
        variant="outlined"
        class="primary-btn left"
      >
        <v-icon icon="mdi-plus"></v-icon>
        {{ "Assign Course" }}
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
  props: {
    courseData: Object,
  },
  data() {
    return {
      selectTenantCourse: "",
      code_error: "",
      name_error: "",
      date_from_error: "",
      date_untill_error: "",
      description_error: "",
      message: "",
      refreshGrid: true,
      color: "success",
      selectedCourseId: null,
    };
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
    async courseModal() {
      try {
        if (this.selectTenantCourse == "") {
          this.$emit("data-passed", {
            snackbar: true,
            message: "Please select checkbox to assign course for tenant",
            color: "error",
            refreshGrid: this.refreshGrid,
          });
        } else {
          const response = await this.addCourse();
          if (response.success == true) {
            this.$router.push("/assign-course");
          }
        }
      } catch (error) {}
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
            let result = await this.axios.post("/api/update-course", payload);
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
            let result = await this.axios.post("/api/create-course", payload);
            this.selectedCourseId = result.data.course.id;
            if (this.selectTenantCourse != "") {
              localStorage.setItem("selectedCourseID", this.selectedCourseId);
            }
            if (result.data.statusCode == 200) {
              this.message = result.data.message;
              this.$emit("data-passed", {
                snackbar: true,
                message: this.message,
                color: this.color,
                refreshGrid: this.refreshGrid,
              });

              this.closeModal();
              return result.data;
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
            this.date_from_error = error.response.data.error.date_from[0];
          }
          if (
            error.response.data &&
            error.response.data.error &&
            error.response.data.error.date_untill
          ) {
            this.date_untill_error = error.response.data.error.date_untill[0];
          }
          if (
            error.response.data &&
            error.response.data.error &&
            error.response.data.error.description
          ) {
            this.description_error = error.response.data.error.description[0];
          }
        }
      }
    },
    validateDates() {
      if (this.courseData.date_from && this.courseData.date_untill) {
        const date_from = new Date(this.courseData.date_from);
        const date_untill = new Date(this.courseData.date_untill);
        if (date_from > date_untill) {
          this.date_from_error = "Start date cannot be after end date.";
          this.date_untill_error = "End date cannot be before start date.";
        } else {
          this.date_from_error = "";
          this.date_untill_error = "";
        }
      }
    },
  },
};
</script>
