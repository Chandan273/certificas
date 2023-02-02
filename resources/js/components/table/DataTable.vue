<template>
    <div id="dxTableComponent">
        <div class="mb-4 d-flex justify-space-between align-center">
            <h4 class="table-title">{{ tableTitle }}</h4>
            <v-btn color="#ff0000" class="text-white" elevation="1">Add</v-btn>
        </div>
        <DxDataGrid id="gridContainer" :data-source="tableData" :columns="tableHeader" :show-borders="false">
            <DxFilterRow :visible="showFilterRow" />

            <DxPaging :page-size="10" />
            <DxPager :visible="true" :allowed-page-sizes="pageSizes" :display-mode="displayMode"
                :show-page-size-selector="showPageSizeSelector" :show-info="showInfo"
                :show-navigation-buttons="showNavButtons" />
        </DxDataGrid>

    </div>
</template>
<script>
import 'devextreme/dist/css/dx.light.css';
import {
    DxDataGrid,
    DxPager,
    DxPaging,
    DxFilterRow,
} from 'devextreme-vue/data-grid';

export default {
    name: 'DataTable',
    components: {
        DxDataGrid,
        DxPager,
        DxPaging,
        DxFilterRow,
    },
    props: {
        tableTitle: {
            type: String,
            default: '',
        },
        tableData: {
            type: Array,
            default: {},
        },
        tableHeader: {
            type: Array,
            default: {},
        },
        showFilterRow: {
            type: Boolean,
            default: true,
        }
    },
    data() {
        return {
            displayModes: [{ text: 'Display Mode \'full\'', value: 'full' }, { text: 'Display Mode \'compact\'', value: 'compact' }],
            displayMode: 'full',
            pageSizes: [5, 10, 15, 20],
            showPageSizeSelector: true,
            showInfo: true,
            showNavButtons: true,


        };
    },
    computed: {
        isCompactMode() {
            return this.displayMode === 'compact';
        },
    },
    methods: {
        customizeColumns(columns) {
            columns[0].width = 70;
        },
    },
};
</script>
<style lang="scss">

</style>