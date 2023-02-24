<template>
    <v-card class="course-modal">
        <v-card-title
            class="d-flex justify-space-between align-center px-6 pt-4 pb-2"
        >
            <h3 v-if="customer.id">{{ $t("updateCustomer") }}</h3>
            <h3 v-else>{{ $t("addNewCustomer") }}</h3>
        </v-card-title>
        <v-card-text class="px-3 py-0">
            <v-container>
                <v-row>
                    <v-col cols="12" class="pb-0">
                        <h4>{{ $t("customerInformation") }}</h4>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <label
                            >{{ $t("number") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customer.number"
                            :placeholder="$t('customerNumber')"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="number_error"
                                class="invalid-feedback text-red"
                                >{{ number_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <label
                            >{{ $t("name") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customer.name"
                            :placeholder="$t('customerName')"
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
                    <v-col cols="12" md="4">
                        <label
                            >{{ $t("email") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customer.email"
                            :placeholder="$t('customerEmail')"
                            type="email"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="email_error"
                                class="invalid-feedback text-red"
                                >{{ email_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" md="4">
                        <label
                            >{{ $t("contact") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customer.contact"
                            :placeholder="$t('contactPerson')"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="contact_error"
                                class="invalid-feedback text-red"
                                >{{ contact_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <label
                            >{{ $t("organisationNumber") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customer.organisation_number"
                            :placeholder="$t('organisationNumber')"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="organisation_number_error"
                                class="invalid-feedback text-red"
                                >{{ organisation_number_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <label
                            >{{ $t("website") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customer.www"
                            :placeholder="$t('website')"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="www_error"
                                class="invalid-feedback text-red"
                                >{{ www_error }}</span
                            >
                        </div>
                    </v-col>
                </v-row>
                <v-row class="mt-6">
                    <v-col cols="12" class="pb-0">
                        <h4>{{ $t("customerAddress") }}</h4>
                    </v-col>
                    <v-col cols="12">
                        <label
                            >{{ $t("address") }}<span class="required">*</span>
                        </label>
                        <v-textarea
                            v-model="customer.address"
                            :placeholder="$t('customerAddress')"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                        ></v-textarea>
                        <div class="text-start">
                            <span
                                v-if="address_error"
                                class="invalid-feedback text-red"
                                >{{ address_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <label
                            >{{ $t("phoneNumber")
                            }}<span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customer.phone"
                            :placeholder="$t('phoneNumber')"
                            class="mt-2"
                            hide-details="auto"
                            variant="outlined"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="phone_error"
                                class="invalid-feedback text-red"
                                >{{ phone_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" md="4">
                        <label
                            >{{ $t("zip") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customer.zip"
                            :placeholder="$t('zip')"
                            hide-details="auto"
                            class="mt-2"
                            variant="outlined"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="zip_error"
                                class="invalid-feedback text-red"
                                >{{ zip_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" md="4">
                        <label
                            >{{ $t("city") }}
                            <span class="required">*</span></label
                        >
                        <v-text-field
                            v-model="customer.city"
                            :placeholder="$t('city')"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                        ></v-text-field>
                        <div class="text-start">
                            <span
                                v-if="city_error"
                                class="invalid-feedback text-red"
                                >{{ city_error }}</span
                            >
                        </div>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <label
                            >{{ $t("country") }}
                            <span class="required">*</span></label
                        >
                        <!-- <v-select
                            v-model="customer.country"
                            :items="countries"
                            :placeholder="$t('selectCountry')"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                            item-title="name"
                            item-value="alpha_2"
                        >
                        </v-select> -->
                        <v-select
                            v-model="customer.country"
                            :items="countries"
                            :placeholder="$t('selectCountry')"
                            variant="outlined"
                            hide-details="auto"
                            class="mt-2"
                            required
                            item-title="name"
                            item-value="alpha_2"
                        >
                            <template v-slot:prepend-item>
                                <v-list-item>
                                    <v-list-item-content>
                                        <v-text-field
                                            v-model="searchTerm"
                                            placeholder="Search"
                                            @input="searchCountries"
                                        ></v-text-field>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider class="mt-2"></v-divider>
                            </template>
                        </v-select>
                        <div class="text-start">
                            <span
                                v-if="country_error"
                                class="invalid-feedback text-red"
                                >{{ country_error }}</span
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
                @click="addCustomer(customerData.id ? 'update' : 'add')"
                class="primary-btn"
            >
                {{ $t("save") }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import countries from "../../assets/data/country-iso";
export default {
    props: {
        customerData: Object,
    },
    data() {
        return {
            refreshGrid: "refreshGrid",
            number_error: "",
            name_error: "",
            contact_error: "",
            organisation_number_error: "",
            email_error: "",
            www_error: "",
            phone_error: "",
            address_error: "",
            zip_error: "",
            city_error: "",
            country_error: "",
            customer: this.customerData || {
                country: "NO",
            },
            selectedCountry: countries[0],
            countries: countries,
            message: "",
            refreshGrid: true,
            color: "success",
            searchTerm: "",
            countriesCopy: [],
        };
    },
    methods: {
        closeModal() {
            this.$emit("close");
        },
        async addCustomer(type) {
            this.number_error = "";
            this.name_error = "";
            this.contact_error = "";
            this.organisation_number_error = "";
            this.email_error = "";
            this.www_error = "";
            this.phone_error = "";
            this.address_error = "";
            this.zip_error = "";
            this.city_error = "";
            this.country_error = "";
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
                if (type == "update") {
                    payload.id = this.customerData.id;
                    payload.tenant_id = this.customerData.tenant_id;
                    let result = await this.axios.post(
                        "/api/update-customer",
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
                        "/api/create-customer",
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
                    error.response.data.error.number
                ) {
                    this.number_error = error.response.data.error.number[0];
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
                    error.response.data.error.contact
                ) {
                    this.contact_error = error.response.data.error.contact[0];
                }
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.organisation_number
                ) {
                    this.organisation_number_error =
                        error.response.data.error.organisation_number[0];
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
                    error.response.data.error.www
                ) {
                    this.www_error = error.response.data.error.www[0];
                }
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.phone
                ) {
                    this.phone_error = error.response.data.error.phone[0];
                }
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.address
                ) {
                    this.address_error = error.response.data.error.address[0];
                }
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.zip
                ) {
                    this.zip_error = error.response.data.error.zip[0];
                }
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.city
                ) {
                    this.city_error = error.response.data.error.city[0];
                }
                if (
                    error.response.data &&
                    error.response.data.error &&
                    error.response.data.error.country
                ) {
                    this.country_error = error.response.data.error.country[0];
                }
            }
        },
        searchCountries() {
            if (!this.searchTerm) {
                this.countries = this.countriesCopy;
            } else {
                this.countries = this.countriesCopy.filter((country) => {
                    return (
                        country.name
                            .toLowerCase()
                            .indexOf(this.searchTerm.toLowerCase()) > -1
                    );
                });
            }
        },
    },
    mounted() {
        this.countriesCopy = [...this.countries];
    },
};
</script>
