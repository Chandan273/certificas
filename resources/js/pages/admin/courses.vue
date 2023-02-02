<template>
    <AdminLayout>
        <v-breadcrumbs class="ps-0 pt-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <!-- <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
            <DataAddRemoveTable :dataSource="dataSource" :dataType="dataType">
                <DxPopup :show-title="true" :width="600" :height="525" title="Course Info" />

            </DataAddRemoveTable>
        </div> -->
    </AdminLayout>
</template>
<script>
import DataAddRemoveTable from '../../components/table/DataAddRemoveTable.vue';
import AdminLayout from '../../layouts/adminLayout.vue';
import {
    DxPopup,
    DxForm,
    DxItem
} from 'devextreme-vue/data-grid';
export default {
    name: "courses",
    components: {
        DataAddRemoveTable, AdminLayout, DxPopup,
        DxForm,
        DxItem
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
                    text: "Course",
                    disabled: false,
                    href: "/course",
                },
            ],
            dataSource: [
                {
                    Customer: 'John',
                    Code: '6262',
                    Name: 'dvnjn',
                    Description: 'text description',
                    date_from: '1964/03/24',
                    date_until: '1964/03/5',
                    Updated: '1464/03/13',

                },
                {
                    Customer: 'John',
                    Code: '6262',
                    Name: 'dvnjn',
                    Description: 'text description',
                    date_from: '1964/03/24',
                    date_until: '1964/03/5',
                    Updated: '1464/03/13',

                },
                {
                    Customer: 'John',
                    Code: '6262',
                    Name: 'dvnjn',
                    Description: 'text description',
                    date_from: '1964/03/24',
                    date_until: '1964/03/5',
                    Updated: '1464/03/13',

                },
                {
                    Customer: 'John',
                    Code: '6262',
                    Name: 'dvnjn',
                    Description: 'text description',
                    date_from: '1964/03/24',
                    date_until: '1964/03/5',
                    Updated: '1464/03/13',

                },
                {
                    Customer: 'John',
                    Code: '6262',
                    Name: 'dvnjn',
                    Description: 'text description',
                    date_from: '1964/03/24',
                    date_until: '1964/03/5',
                    Updated: '1464/03/13',

                },

            ],

            dataType: [
                {
                    "key": "Customer",
                    "type": 'string'
                },
                {
                    "key": "Code",
                    "type": 'string'
                },
                {
                    "key": "Name",
                    "type": 'string'
                },
                {
                    "key": "Description",
                    "type": 'string'
                },
                {
                    "key": "date_from",
                    "type": 'date'
                },
                {
                    "key": "date_until",
                    "type": 'date'
                },
                {
                    "key": "Updated",
                    "type": 'date'
                },
            ],

        };

    },

};
</script>
