<template>
    <AdminLayout>
        <v-snackbar
            v-model="snackbar"
            :value="true"
            absolute
            right
            top
            location="top right"
            :color="color"
            timeout="3000">
            <v-icon icon="mdi-check-circle"> </v-icon> {{ message }}
        </v-snackbar>
        <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <div class="pa-5 pa-sm-4 pa-md-4 pa-lg-6 course-card widget-card">
            <v-btn
                class="primary-btn add-btn"
                elevation="0"
                @click="
                    customerData = {};
                    customerDialog = true;
                "
            >
                <v-icon icon="mdi-plus"></v-icon> {{ $t("addCustomer") }}
            </v-btn>
            <v-dialog v-model="customerDialog" persistent max-width="860px">
                <AddCustomer
                    @data-passed="refreshGrid"
                    @close="closeModal"
                    :customerData="customerData"
                ></AddCustomer>
            </v-dialog>
            <DxDataGrid
                :ref="dataGridRefKey"
                class="tenants-table"
                :data-source="dataSource"
                :show-borders="true"
            >
                <DxSearchPanel :visible="true" :placeholder="$t('search')" />
                <DxEditing
                    :allow-updating="true"
                    :allow-adding="true"
                    :allow-deleting="true"
                    :use-icons="true"
                >
                    <DxTexts
                        confirmDeleteMessage="<p><h3 style='text-align:center'>Are you sure you want to delete this record? </h3></p><p> <b>Note:</b> Existing related records such as certificates are not affected.</p>"
                    ></DxTexts>
                </DxEditing>
                <DxPaging :page-size="10" />
                <DxPager
                    :show-page-size-selector="true"
                    :allowed-page-sizes="[10, 25, 50, 100]"
                />
                <DxColumn data-field="number" :caption="$t('number')" />
                <DxColumn data-field="name" :caption="$t('name')" />
                <DxColumn data-field="contact" :caption="$t('contact')" />
                <DxColumn data-field="email" :caption="$t('email')" />
                <DxColumn data-field="phone" :caption="$t('phoneNumber')" />
                <DxColumn data-field="www" :caption="$t('website')" />
                <DxColumn
                    data-field="organisation_number"
                    :caption="$t('organisationNumber')"
                />
                <DxColumn data-field="address" :caption="$t('address')" />
                <DxColumn data-field="Action" type="buttons" alignment="left" 
                    :caption="$t('action')">
                    <DxButton
                        name="edit"
                        hint="Edit Customer Details"
                        icon="edit"
                        @click="editCustomer"
                    />
                    <DxButton name="delete" />
                </DxColumn>
            </DxDataGrid>
        </div>
    </AdminLayout>
</template>
<script>
const dataGridRefKey = "my-data-grid";
import AdminLayout from "../../layouts/adminLayout.vue";
import AddCustomer from "../../components/modals/AddCustomer.vue";
import {
    DxDataGrid,
    DxColumn,
    DxPaging,
    DxPager,
    DxSearchPanel,
    DxEditing,
    DxPopup,
    DxLookup,
    DxForm,
    DxButton,
    DxTexts,
} from "devextreme-vue/data-grid";
import { DxTextArea } from "devextreme-vue/text-area";
import { DxItem } from "devextreme-vue/form";
import CustomStore from "devextreme/data/custom_store";
import notify from "devextreme/ui/notify";
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
        DxPager,
        DxSearchPanel,
        DxEditing,
        DxPopup,
        DxLookup,
        DxForm,
        DxItem,
        DxTextArea,
        AddCustomer,
        DxButton,
        DxTexts,
    },
    data() {
        return {
            customerDialog: false,
            country: [],
            dataGridRefKey,
            snackbar: false,
            message: "",
            color: "success",
        };
    },
    methods: {
        closeModal() {
            this.customerDialog = false;
        },
        refreshGrid(data) {
            this.snackbar = data.snackbar;
            this.color = data.color;
            this.message = data.message;
            this.dataGrid.refresh();
        },
        editCustomer(params) {
            this.customerDialog = true;
            this.customerData = params.row.data;
        },
    },
    computed: {
        dataSource: function () {
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
                    return this.axios
                        .get(`/api/all-customers`, { params })
                        .then(({ data }) => ({
                            data: data.data,
                            totalCount: data.totalCount,
                        }))
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
                remove: (key) => {
                    const payload = {
                        id: key.id,
                    };
                    return this.axios
                        .post(`/api/delete-customer`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message:
                                        "Customer has been deleted successfully!!",
                                    width: 300,
                                    shading: true,
                                },
                                "success",
                                2000
                            );
                            return data;
                        })
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
            });
        },
        breadcrumbsItems() {
            return [
                {
                    text: this.$t("admin"),
                    disabled: true,
                    href: "dashboard",
                },
                {
                    text: this.$t("customers"),
                    disabled: false,
                    href: "/customers",
                },
            ];
        },
        dataGrid: function () {
            return this.$refs[dataGridRefKey].instance;
        },
    },
};
</script>

