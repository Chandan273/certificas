<template>
    <AdminLayout>
        <v-breadcrumbs class="ps-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
            <DxDataGrid :data-source="dataSource" :show-borders="true" key-expr="id">
                <DxPaging :enabled="false" />
                <DxEditing :allow-updating="true" :allow-adding="true" :allow-deleting="true" mode="popup">
                    <DxPopup :show-title="true" :width="800" :height="380" title="Course Info" />
                    <DxForm>
                        <DxItem :col-count="3" :col-span="2" item-type="group">
                            <DxItem data-field="customer_id" :validation-rules="[{ type: 'required' }]" />
                            <DxItem data-field="code" :validation-rules="[{ type: 'required' }]" />
                            <DxItem data-field="name" :validation-rules="[{ type: 'required' }]" />
                        </DxItem>
                        <DxItem :col-count="2" :col-span="2" item-type="group">
                            <DxItem :col-span="2" :editor-options="{ height: 100 }" data-field="description"
                                editor-type="dxTextArea" :validation-rules="[{ type: 'required' }]" />
                            <DxItem data-field="date_from" />
                            <DxItem data-field="date_untill" />
                        </DxItem>
                    </DxForm>
                </DxEditing>

                <DxColumn :visible="false" data-field="customer_id" caption="Customer">
                    <DxLookup :data-source="customers" value-expr="id" display-expr="name" />
                </DxColumn>
                <DxColumn :width="70" data-field="code" />
                <DxColumn data-field="name" />
                <DxColumn data-field="description" />
                <DxColumn data-field="date_from" data-type="date" />
                <DxColumn data-field="date_untill" data-type="date" />
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
    DxPager,
    DxEditing,
    DxLookup,
} from "devextreme-vue/data-grid";
import CustomStore from "devextreme/data/custom_store";
function isNotEmpty(value) {
    return value !== undefined && value !== null && value !== "";
}

export default {
    name: "courses",
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
        DxLookup,
        DxPager,
        DxPaging,
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
            customers: {},
            breadcrumbsItems: [
                {
                    text: "Admin",
                    disabled: true,
                    href: "dashboard",
                },
                {
                    text: "Courses",
                    disabled: false,
                    href: "/courses",
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
                        .get(`/api/all-courses`, { params })
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
                        customer_id: values.customer_id,
                        code: values.code,
                        name: values.name,
                        description: values.description,
                        date_from: values.date_from,
                        date_untill: values.date_untill,
                    };

                    return axios
                        .post(`/api/create-course`, payload)
                        .then((data) => { })
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
                update: (key, values) => {
                    const payload = {
                        id: key.id,
                        code: values.code ? values.code : key.code,
                        name: values.name ? values.name : key.name,
                        description: values.description ? values.description : key.description,
                        organisation_number: values.organisation_number ? values.organisation_number : key.organisation_number,
                        date_from: values.date_from ? values.date_from : key.date_from,
                        date_untill: values.date_untill ? values.date_untill : key.date_untill,
                    };

                    return axios
                        .post(`/api/update-course`, payload)
                        .then((data) => { })
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
                remove: (key) => {
                    const payload = {
                        id: key.id,
                    };
                    return axios
                        .post(`/api/delete-course`, payload)
                        .then((data) => { })
                        .catch((error) => {
                            throw new Error("Data Loading Error");
                        });
                },
            });
        },
    },
    methods: {
        async getData() {
            try {
                const { data } = await axios.get(`/api/all-customers`);
                this.customers = data.data;
            } catch (err) {
                console.log(err)
            }
        }
    },
    mounted() {
        this.getData();
    }
};
</script>
