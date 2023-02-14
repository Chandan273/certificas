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
                            v-model="customerData.number"
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
                            v-model="customerData.name"
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
                            v-model="customerData.email"
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
                            v-model="customerData.contact"
                            placeholder="Customer contact"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <label
                            >Organisation Number
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customerData.organisation_number"
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
                            v-model="customerData.www"
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
                            v-model="customerData.address"
                            placeholder="Customer address"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                        ></v-textarea>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <label
                            >Phone Number <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customerData.phone"
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
                            v-model="customerData.zip"
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
                            v-model="customerData.city"
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
                            :items="countries"
                            placeholder="Select country"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                            item-title="name"
                            item-value="alpha_2"
                            v-model="customer.country"
                        >
                        </v-select>
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
                @click="addCustomer(customerData.id ? 'update' : 'add')"
                class="primary-btn"
            >
                Save
            </v-btn>
        </v-card-actions>
    </v-card>
</template>
<script>
import countries from "../../assets/data/country-iso";
import axios from "axios";

export default {
    props: {
        customerData: Object,
        countryData: Object,
        name: String,
        iso: String,
    },
    data() {
        return {
            customer: this.customerData || {
                country: "NO",
            },
            selectedCountry: countries[0],
            countries: countries,
        };
    },
    methods: {
        closeModal() {
            this.$emit("close");
        },
        async addCustomer(type) {
            // this.code_error = "";
            // this.name_error = "";
            // this.date_from_error = "";
            // this.date_untill_error = "";
            // this.description_error = "";

            try {
                let payload = {
                    number: this.customerData.number,
                    name: this.customerData.name,
                    contact: this.customerData.contact,
                    email: this.customerData.email,
                    phone: this.customerData.phone,
                    www: this.customerData.www,
                    organisation_number: this.customerData.organisation_number,
                    address: this.customerData.address,
                    zip: this.customerData.zip,
                    city: this.customerData.city,
                    country: this.customerData.country,
                };

                console.log(payload, type);

                if (type == "update") {
                    payload.id = this.customerData.id;
                    payload.tenant_id = this.customerData.tenant_id;

                    let result = await axios.post(
                        "/api/update-customer",
                        payload
                    );
                } else {
                    let result = await axios.post(
                        "/api/create-customer",
                        payload
                    );
                }
            } catch (error) {
                console.log("error =>", error);
            }
        },
    },
};
</script>
