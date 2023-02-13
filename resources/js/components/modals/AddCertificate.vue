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
          <v-col cols="12" sm="12">
            <label>Description <span class="required">*</span></label>
            <v-textarea
              placeholder="Description"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
              required
            ></v-textarea>
          </v-col>
          <v-col cols="12" md="6">
            <label>Valid from <span class="required">*</span></label>
            <v-text-field
              type="date"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <label>Valid untill <span class="required">*</span></label>
            <v-text-field
              variant="outlined"
              hide-details="auto"
              class="mt-2"
              type="date"
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
    return {};
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
  },
};
</script>
