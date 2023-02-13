<template>
  <v-card class="course-modal">
    <v-card-title
      class="d-flex justify-space-between align-center px-6 pt-4 pb-2"
    >
      <h3>Add new course</h3>
      <v-icon icon="mdi-close" @click="closeModal"></v-icon>
    </v-card-title>
  
    <v-card-text class="px-3 py-0">
      <v-container>
        <v-row>
          <v-col cols="12" sm="6" md="6">
            <label>Code</label>
            <v-text-field
              v-model="courseData.code"
              placeholder="Course code"
              variant="outlined"
              hide-details="auto"
              class="mt-2"
              required
            ></v-text-field>
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
          </v-col>
          <v-col cols="12" md="6">
            <label>Date untill <span class="required">*</span></label>
            <v-text-field
              v-model="courseData.date_untill"
              variant="outlined"
              hide-details="auto"
              class="mt-2"
              type="date"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="12">
            <label>Description <span class="required">*</span></label>
            <v-textarea
              v-model="courseData.description"
              placeholder="Course description"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
              required
            ></v-textarea>
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
      <v-btn @click="addCourse()" class="primary-btn"> Save </v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
  props:{
    courseData: Object
  },
  data() {
    return {
      code:null,
      name:null,
      date_from:null,
      date_untill:null,
      description:null,
    };
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
    addCourse() {
      const formData = {
        code: this.code,
        name: this.name,
        date_from: this.date_from,
        date_untill: this.date_untill,
        description: this.description,
      }
      console.log("test=>",formData);
    }
  },
};
</script>
