<template>
    <AdminLayout>
        <div>
            <v-breadcrumbs class="ps-0 pt-0" :items="breadcrumbsItems"></v-breadcrumbs>
            <!-- <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
                <DataAddRemoveTable :dataSource="dataSource" :dataType="dataType">
                    <DxPopup :show-title="true" :width="600" :height="525" title="Students Info" />

                </DataAddRemoveTable>
            </div> -->
        </div>
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
    name: "Students",
    components: {
        DataAddRemoveTable, AdminLayout, DxPopup, DxForm, DxItem
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
                    text: "Students",
                    disabled: false,
                    href: "/students",
                },
            ],
            dataSource: [
                {
                    Course: 'bsc',
                    Name: 'john',
                    Email: 'john@yopmail.com',
                    birthDate: '02/06/2002',
                    birthPlace: 'IN',
                    Updated: '03/04/2016',
                },
                {
                    Course: 'bsc',
                    Name: 'john',
                    Email: 'john@yopmail.com',
                    birthDate: '02/06/2002',
                    birthPlace: 'IN',
                    Updated: '03/04/2016',
                },
                {
                    Course: 'bsc',
                    Name: 'john',
                    Email: 'john@yopmail.com',
                    birthDate: '02/06/2002',
                    birthPlace: 'IN',
                    Updated: '03/04/2016',
                },
                {
                    Course: 'bsc',
                    Name: 'john',
                    Email: 'john@yopmail.com',
                    birthDate: '02/06/2002',
                    birthPlace: 'IN',
                    Updated: '03/04/2016',
                },
                {
                    Course: 'bsc',
                    Name: 'john',
                    Email: 'john@yopmail.com',
                    birthDate: '02/06/2002',
                    birthPlace: 'IN',
                    Updated: '03/04/2016',
                },
            ],

            dataType: [ {
                    "key": "Course",
                    "type": 'string'
                },
                {
                    "key": "Name",
                    "type": 'string'
                },
                {
                    "key": "Email",
                    "type": 'string'
                },
                {
                    "key": "birthDate",
                    "type": 'string'
                },
                {
                    "key": "birthPlace",
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
