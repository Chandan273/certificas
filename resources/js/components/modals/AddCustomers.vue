<template>
  <v-card class="course-modal">
    <v-card-title
      class="d-flex justify-space-between align-center px-6 pt-4 pb-2"
    >
      <h3>Add new customer</h3>
      <v-icon icon="mdi-close" @click="closeModal"></v-icon>
    </v-card-title>

    <v-card-text class="px-3 py-0">
      <v-container>
        <v-row>
          <v-col cols="12" class="pb-0">
            <h4>Customer Information</h4>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <label>Number <span class="required">*</span></label>
            <v-text-field
              type="number"
              placeholder="Customer number"
              variant="outlined"
              hide-details="auto"
              class="mt-2"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <label>Name <span class="required">*</span></label>
            <v-text-field
              placeholder="Customer name"
              class="mt-2"
              hide-details="auto"
              variant="outlined"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <label>Email <span class="required">*</span></label>
            <v-text-field
              placeholder="Customer email"
              type="email"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <label>Contact <span class="required">*</span></label>
            <v-text-field
              type="number"
              placeholder="Customer contact"
              variant="outlined"
              hide-details="auto"
              class="mt-2"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="4">
            <label>Organisation Number <span class="required">*</span></label>
            <v-text-field
              placeholder="Organisation number"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="4">
            <label>Website <span class="required">*</span></label>
            <v-text-field
              placeholder="Website"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
              required
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row class="mt-6">
          <v-col cols="12" class="pb-0">
            <h4>Customer Address</h4>
          </v-col>
          <v-col cols="12">
            <label>Address <span class="required">*</span></label>
            <v-textarea
              placeholder="Customer address"
              variant="outlined"
              hide-details="auto"
              class="mt-2"
              required
            ></v-textarea>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <label>Phone Number <span class="required">*</span></label>
            <v-text-field
              placeholder="Phone number"
              class="mt-2"
              hide-details="auto"
              variant="outlined"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <label>Zip <span class="required">*</span></label>
            <v-text-field
              type="number"
              placeholder="Zip"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <label>City <span class="required">*</span></label>
            <v-text-field
              placeholder="City"
              variant="outlined"
              hide-details="auto"
              class="mt-2"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="4">
            <label>Country <span class="required">*</span></label>
            <v-select
              :items="items"
              placeholder="Select country"
              hide-details="auto"
              class="mt-2"
              variant="outlined"
              required
            ></v-select>
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
      items: ["Afghanistan", "Albania", "USA", "Australia"],
    };
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
  },
};
</script>
