<template>
    <AdminLayout>
        <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
            <DxDataGrid
                :data-source="dataSource"
                :show-borders="true"
                key-expr="id"
            >
                <DxPaging :enabled="false" />
                <DxSearchPanel :visible="true" />
                <DxEditing
                    :allow-updating="true"
                    :allow-adding="true"
                    :allow-deleting="true"
                    :use-icons="true"
                    mode="popup"
                >
                    <DxPopup
                        :show-title="true"
                        :width="700"
                        :height="600"
                        title="Customer Info"
                    />
                    <DxForm>
                        <DxItem :col-count="2" :col-span="2" item-type="group">
                            <DxItem
                                data-field="number"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Number is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="name"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Name is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="contact"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Contact is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="organisation_number"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message:
                                            'organisation Number is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="email"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Email Address is Required',
                                    },
                                    { type: 'email' },
                                ]"
                            />
                            <DxItem
                                data-field="www"
                                caption="Website"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Website is required',
                                    },
                                    {
                                        type: 'pattern',
                                        pattern:
                                            /^(https?:\/\/)?([\w\d]+\.)+[\w\d]+$/,
                                        message: 'Invalid website address',
                                    },
                                ]"
                            />

                            <DxItem
                                :col-count="2"
                                :col-span="2"
                                item-type="group"
                                caption="Customer Address"
                            >
                                <DxItem
                                    :col-span="2"
                                    :editor-options="{ height: 100 }"
                                    data-field="address"
                                    editor-type="dxTextArea"
                                    :validation-rules="[
                                        {
                                            type: 'required',
                                            message: 'Address is required',
                                        },
                                    ]"
                                />
                            </DxItem>
                            <DxItem
                                data-field="phone"
                                caption="Phone Number"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Phone number is required',
                                    },
                                    {
                                        type: 'pattern',
                                        pattern: /^[\d()\-+ ]{10,15}$/,
                                        message:
                                            'Invalid phone number, phone number should have minimum length 10 and maximum length 15',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="zip"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Zip is required',
                                    },
                                ]"
                            />
                            <DxItem
                                data-field="city"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'City is required',
                                    },
                                ]"
                            />

                            <DxItem
                                data-field="country"
                                :validation-rules="[
                                    {
                                        type: 'required',
                                        message: 'Country is required',
                                    },
                                ]"
                            />
                        </DxItem>
                    </DxForm>
                </DxEditing>

                <DxColumn data-field="number" />
                <DxColumn data-field="name" />
                <DxColumn data-field="contact" />
                <DxColumn data-field="email" />
                <DxColumn data-field="phone" caption="Phone Number" />
                <DxColumn data-field="www" caption="Website" />
                <DxColumn
                    data-field="organisation_number"
                    caption="Organisation Number"
                />
                <DxColumn data-field="address" />
                <DxColumn :visible="false" data-field="zip" />
                <DxColumn :visible="false" data-field="city" />
                <DxColumn :width="125" data-field="country" caption="Country">
                    <DxLookup
                        :data-source="country"
                        value-expr="alpha-2"
                        display-expr="name"
                    />
                </DxColumn>
                <DxColumn data-field="Action" type="buttons">
                    <DxButton name="edit" />
                    <DxButton name="delete" />
                </DxColumn>
            </DxDataGrid>
        </div>
    </AdminLayout>
</template>
<script>
import axios from "axios";
import AdminLayout from "../../layouts/adminLayout.vue";
import {
    DxDataGrid,
    DxColumn,
    DxPaging,
    DxSearchPanel,
    DxEditing,
    DxPopup,
    DxLookup,
    DxForm,
} from "devextreme-vue/data-grid";

import { countries } from "../../assets/data/country-iso";
import { DxTextArea } from "devextreme-vue/text-area";
import { DxItem } from "devextreme-vue/form";
import CustomStore from "devextreme/data/custom_store";
function isNotEmpty(value) {
    return value !== undefined && value !== null && value !== "";
}

export default {
    name: "customers",
    components: {
        AdminLayout,
        DxDataGrid,
        DxColumn,
        DxPaging,
        DxSearchPanel,
        DxEditing,
        DxPopup,
        DxLookup,
        DxForm,
        DxItem,
        DxTextArea,
    },
    data() {
        return {
            country: countries,
            breadcrumbsItems: [
                {
                    text: "Admin",
                    disabled: true,
                    href: "dashboard",
                },
                {
                    text: "Customers",
                    disabled: false,
                    href: "/customers",
                },
            ],
        };
    },
    computed: {
        dataSource: () => {
            return new CustomStore({
                load: (loadOptions) => {
                    let params = {};
                    [
                        "skip",
                        "take",
                        "requireTotalCount",
                        "requireGroupCount",
                        "sort",
                        "filter",
                    ].forEach((i) => {
                        if (i in loadOptions && isNotEmpty(loadOptions[i])) {
                            params[i] = `${JSON.stringify(loadOptions[i])}`;
                        }
                    });

                    return axios
                        .get(`/api/all-customers`, { params })
                        .then(({ data }) => ({
                            data: data.data,
                            totalCount: data.totalCount,
                        }))
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
                insert: (values) => {
                    const payload = {
                        number: values.number,
                        name: values.name,
                        contact: values.contact,
                        email: values.email,
                        phone: values.phone,
                        www: values.www,
                        organisation_number: values.organisation_number,
                        address: values.address,
                        zip: values.zip,
                        city: values.city,
                        country: values.country,
                    };
                    return axios
                        .post(`/api/create-customer`, payload)
                        .then((data) => {})
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
                update: (key, values) => {
                    const payload = {
                        id: key.id,
                        tenant_id: key.tenant_id,
                        number: values.number ? values.number : key.number,
                        name: values.name ? values.name : key.name,
                        contact: values.contact ? values.contact : key.contact,
                        organisation_number: values.organisation_number
                            ? values.organisation_number
                            : key.organisation_number,
                        email: values.email ? values.email : key.email,
                        www: values.www ? values.www : key.www,
                        phone: values.phone ? values.phone : key.phone,
                        address: values.address ? values.address : key.address,
                        zip: values.zip ? values.zip : key.zip,
                        city: values.city ? values.city : key.city,
                        country: values.country ? values.country : key.country,
                    };
                    return axios
                        .post(`/api/update-customer`, payload)
                        .then((data) => {})
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
                remove: (key) => {
                    const payload = {
                        id: key.id,
                    };
                    return axios
                        .post(`/api/delete-customer`, payload)
                        .then((data) => {})
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
            });
        },
    },
};
</script>
