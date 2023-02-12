<template>
    <AdminLayout>
        <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
            <DxDataGrid
                id="gridContainer"
                :allow-column-reordering="true"
                :word-wrap-enabled="true"
                :key-expr="keyExpr"
                :data-source="dataSource"
                :show-borders="true"
                :remote-operations="true"
                :hover-state-enabled="true"
            >
                <DxColumn
                    data-field="username"
                    data-type="string"
                    data-sort="false"
                    caption="User Name"
                    :validation-rules="[{ type: 'required' }]"
                />
                <DxColumn
                    data-field="tenant.name"
                    data-type="string"
                    caption="Tenant Name"
                    :validation-rules="[{ type: 'required' }]"
                />
                <DxColumn
                    data-field="email"
                    data-type="string"
                    caption="Email"
                    :validation-rules="[
                        {
                            type: 'required',
                            message: 'Email Address is Required',
                        },
                        { type: 'email' },
                    ]"
                />
                <DxColumn
                    data-field="tenant.paid_untill"
                    data-type="date"
                    caption="Paid Untill"
                    :validation-rules="[
                        {
                            type: 'required',
                            message: 'Paid Untill is Required',
                        },
                    ]"
                />
                <DxSearchPanel :visible="true" />
                <DxColumn data-field="Action" type="buttons">
                    <DxButton name="edit" />
                    <DxButton name="delete" />
                </DxColumn>
                <DxEditing
                    data-field="Action"
                    :allow-updating="true"
                    :allow-deleting="true"
                    :allow-adding="true"
                    :use-icons="true"
                    mode="row"
                >
                </DxEditing>
                <DxPaging :page-size="10" />
                <DxPager
                    :show-page-size-selector="true"
                    :allowed-page-sizes="[10, 25, 50, 100]"
                />
            </DxDataGrid>
        </div>
    </AdminLayout>
</template>
<script>
import axios from "axios";

import AdminLayout from "../../layouts/adminLayout.vue";
import { DxTextArea } from "devextreme-vue/text-area";
import {
    DxPopup,
    DxForm,
    DxItem,
    DxButton,
    DxPosition,
    DxToolbarItem,
} from "devextreme-vue/data-grid";
import {
    DxDataGrid,
    DxColumn,
    DxPaging,
    DxSearchPanel,
    DxPager,
    DxEditing,
} from "devextreme-vue/data-grid";
import CustomStore from "devextreme/data/custom_store";
function isNotEmpty(value) {
    return value !== undefined && value !== null && value !== "";
}
import notify from "devextreme/ui/notify";
export default {
    name: "tenant",
    components: {
        AdminLayout,
        DxPopup,
        DxForm,
        DxItem,
        DxTextArea,
        DxButton,
        DxPosition,
        DxToolbarItem,
        DxDataGrid,
        DxColumn,
        DxPager,
        DxPaging,
        DxSearchPanel,
        DxEditing,
    },

    props: {
        showFilterRow: {
            type: Boolean,
            default: true,
        },
    },
    data() {
        return {
            breadcrumbsItems: [
                {
                    text: "Admin",
                    disabled: true,
                    href: "dashboard",
                },
                {
                    text: "Tenants",
                    disabled: false,
                    href: "/tenants",
                },
            ],
            tableTitle: "Tenant Details",
        };
    },
    computed: {
        dataSource: () => {
            let that = this;
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
                        .get(`/api/all-tenants`, { params })
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
                        name: values.tenant.name,
                        email: values.email,
                        username: values.username,
                        paid_untill: values.tenant.paid_untill,
                    };
                    return axios
                        .post(`/api/create-tenant`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message:
                                        "Tenant has been added and password sent to user's email!!",
                                    width: 300,
                                    shading: true,
                                },
                                "success",
                                2000
                            );
                            return data;
                        })
                        .catch((error) => {
                            let err = "";

                            if (
                                error.response &&
                                error.response.data &&
                                error.response.data.error
                            ) {
                                let emailErr = error.response.data.error.email
                                    ? error.response.data.error.email[0]
                                    : "";
                                let userNameErr = error.response.data.error
                                    .username
                                    ? error.response.data.error.username[0]
                                    : "";

                                if (emailErr !== "" && userNameErr !== "") {
                                    err =
                                        "The username and email has been already taken!";
                                } else if (emailErr !== "") {
                                    err = emailErr;
                                } else if (userNameErr !== "") {
                                    err = userNameErr;
                                }

                                notify(
                                    {
                                        position: "top right",
                                        message: err,
                                        width: 300,
                                        shading: true,
                                    },
                                    "error",
                                    3000
                                );
                            }
                        });
                },
                update: (key, values) => {
                    const payload = {
                        id: key.id,
                        name:
                            values.tenant && values.tenant.name
                                ? values.tenant.name
                                : key.tenant.name,
                        email: values.email ? values.email : key.email,
                        username: values.username
                            ? values.username
                            : key.username,
                        paid_untill:
                            values.tenant && values.tenant.paid_untill
                                ? values.tenant.paid_untill
                                : key.tenant.paid_untill,
                    };
                    return axios
                        .post(`/api/update-tenant`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message: "Tenant updated successfully!!",
                                    width: 300,
                                    shading: true,
                                },
                                "success",
                                3000
                            );
                            return data;
                        })
                        .catch((error) => {
                            if (
                                error.response &&
                                error.response.data &&
                                error.response.data.message
                            ) {
                                let err = error.response.data.message;

                                notify(
                                    {
                                        position: "top right",
                                        message: err,
                                        width: 300,
                                        shading: true,
                                    },
                                    "error",
                                    3000
                                );
                            }
                        });
                },

                remove: (key) => {
                    const payload = {
                        id: key.tenant_id,
                    };
                    return axios
                        .post(`/api/delete-tenant`, payload)
                        .then(({ data }) => {
                            notify(
                                {
                                    position: "top right",
                                    message:
                                        "Certificate deleted successfully!!",
                                    width: 300,
                                    shading: true,
                                },
                                "success",
                                3000
                            );
                            return data;
                        })
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
            });
        },
    },
    mounted() {
        let userRole = localStorage.getItem("role");
        if (userRole == "superadmin") {
            this.$router.push("/tenants");
        } else {
            this.$router.push("/courses");
        }
    },
};
</script>
