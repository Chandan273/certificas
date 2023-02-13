<template>
  <v-card class="course-modal">
    <v-card-title
      class="d-flex justify-space-between align-center px-6 pt-4 pb-2"
    >
      <h3>Add new student</h3>
      <v-icon icon="mdi-close" @click="closeModal"></v-icon>
    </v-card-title>

    <v-card-text class="px-3 py-0">
      <v-container>
        <v-row>
          <v-col cols="12" sm="6" md="6">
            <label>Courses</label>

            <v-select
              :items="items"
              placeholder="Course code"
              variant="outlined"
              hide-details="auto"
              class="mt-2"
            ></v-select>
          </v-col>
          <v-col cols="12" sm="6" md="6">
            <label>Name <span class="required">*</span></label>
            <v-text-field
              placeholder="Student name"
              class="mt-2"
              hide-details="auto"
              variant="outlined"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <label>Email</label>
            <v-text-field
              placeholder="Student email"
              type="email"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <label>DOB</label>
            <v-text-field
              placeholder="Date of birth"
              variant="outlined"
              hide-details="auto"
              class="mt-2"
              type="date"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="6" sm="6">
            <label>Birth place</label>
            <v-text-field
              placeholder="Birth place"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
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
      <v-btn @click="closeModal()" class="primary-btn"> Save </v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
  data() {
    return {
      items: ["Course 1", "Course 2", "Course 3", "Course 4"],
    };
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
  },
};
</script>
