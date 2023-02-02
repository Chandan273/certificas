<template>
    <AdminLayout>
        <v-breadcrumbs class="ps-0 pt-0" :items="breadcrumbsItems"></v-breadcrumbs>
        <!-- <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
            <DataAddRemoveTable :dataSource="dataSource" :dataType="dataType">
                <DxPopup :show-title="true" :width="600" :height="525" title="Certificates Info" />
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
    name: "Certificates",
    components: {
        DataAddRemoveTable,
        AdminLayout,
        DxPopup,
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
                    text: "Certificates",
                    disabled: false,
                    href: "/certificates",
                },
            ],
            dataSource: [
                {
                    Student: 'Student',
                    Course: 'bsc',
                    valid_from: '05/08/2002',
                    valid_until: '02/06/2002',
                    created: '02/02/2002',
                    updated: '03/09/2016',
                },
                {
                    Student: 'Student 2',
                    Course: 'bsc',
                    valid_from: '05/08/2002',
                    valid_until: '02/06/2002',
                    created: '02/02/2002',
                    updated: '03/09/2016',
                },
                {
                    Student: 'Student 3',
                    Course: 'bsc',
                    valid_from: '05/08/2002',
                    valid_until: '02/06/2002',
                    created: '02/02/2002',
                    updated: '03/09/2016',
                },
                {
                    Student: 'Student 4',
                    Course: 'bsc',
                    valid_from: '05/08/2002',
                    valid_until: '02/06/2002',
                    created: '02/02/2002',
                    updated: '03/09/2016',
                },
                {
                    Student: 'Student 5',
                    Course: 'bsc',
                    valid_from: '05/08/2002',
                    valid_until: '02/06/2002',
                    created: '02/02/2002',
                    updated: '03/09/2016',
                },
            ],

            dataType: [
                {
                    "key": "Student",
                    "type": 'string'
                },
                {
                    "key": "Course",
                    "type": 'string'
                }, {
                    "key": "valid_from",
                    "type": 'date'
                },
                {
                    "key": "valid_until",
                    "type": 'date'
                },
                {
                    "key": "created",
                    "type": 'date'
                },
                {
                    "key": "updated",
                    "type": 'date'
                },
            ],

        };

    },

};
</script>
