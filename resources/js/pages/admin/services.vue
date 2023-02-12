<template>
  <AdminLayout>
    <div>
      <v-breadcrumbs
        class="ps-0 pt-0"
        :items="breadcrumbsItems"
      ></v-breadcrumbs>

      <div class="pa-8 pa-sm-4 pa-md-4 pa-lg-6 widget-card">
        <div id="dxTableComponent">
          <div class="d-flex justify-space-between align-center mb-3">
            <div class="right-side">
              <DxSelectBox
                :items="statuses"
                :value="statuses[0]"
                @value-changed="onValueChanged"
              />
            </div>
          </div>

          <DxDataGrid
            id="gridContainer"
            :ref="dataGridRefName"
            :data-source="dataSource"
            :column-auto-width="true"
            :show-borders="false"
          >
            <DxColumn :width="80" data-field="ID" />
            <DxColumn
              :allow-sorting="false"
              data-field="UserName"
              css-class="employee"
              caption="Name"
            />
            <DxColumn
              :allow-sorting="false"
              data-field="UserEmail"
              caption="Email"
            />

            <DxColumn data-field="Date" data-type="date" caption="Join Date" />
            <DxColumn data-field="UserType" caption="UserType" />
            <DxColumn data-field="UserStatus" caption="Status" />

            <DxFilterRow :visible="showFilterRow" />
            <DxPaging :page-size="10" />
            <DxPager
              :visible="true"
              :allowed-page-sizes="pageSizes"
              :display-mode="displayMode"
              :show-page-size-selector="showPageSizeSelector"
              :show-info="showInfo"
              :show-navigation-buttons="showNavButtons"
            />

            <DxPopup
              :show-title="true"
              :width="600"
              :height="525"
              title="Services Info"
            />
          </DxDataGrid>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
<script>
import {
  DxColumn,
  DxDataGrid,
  DxPager,
  DxPaging,
  DxFilterRow,
} from 'devextreme-vue/data-grid'
import DxSelectBox from 'devextreme-vue/select-box'
import AdminLayout from '../../layouts/adminLayout.vue'
import { DxPopup, DxForm, DxItem } from 'devextreme-vue/data-grid'
export default {
  name: 'Services',
  components: {
    DxSelectBox,
    DxColumn,
    DxDataGrid,
    DxPager,
    DxPaging,
    DxFilterRow,
    AdminLayout,
    DxPopup,
    DxForm,
    DxItem,
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
          text: 'Admin',
          disabled: true,
          href: 'dashboard',
        },
        {
          text: 'Services',
          disabled: false,
          href: '/services',
        },
      ],
      displayModes: [
        { text: "Display Mode 'full'", value: 'full' },
        { text: "Display Mode 'compact'", value: 'compact' },
      ],
      displayMode: 'full',
      pageSizes: [5, 10, 15, 20],
      showPageSizeSelector: true,
      showInfo: true,
      showNavButtons: true,
      dataSource: {
        store: [
          {
            UserName: 'Jack',
            UserEmail: 'example@yopmail.com',
            Date: '2013-02-12T00:00:00',
            UserType: 'customer',
            UserStatus: 'Deleted',
          },
          {
            UserName: 'John',
            UserEmail: 'example@yopmail.com',
            Date: '2013-02-12T00:00:00',
            UserType: 'Promoter',
            UserStatus: 'Active',
          },
        ],
        expand: 'ResponsibleEmployee',
        select: ['UserName', 'Date', 'UserType', 'UserStatus', 'UserEmail'],
      },
      statuses: ['All', 'Deleted', 'Active', 'Customer', 'Promoter'],
      dataGridRefName: 'dataGrid',
    }
  },
  methods: {
    onValueChanged({ value }) {
      const dataGrid = this.$refs[this.dataGridRefName].instance
      if (value === 'All') {
        dataGrid.clearFilter()
      } else if (value === 'Promoter' || value === 'Customer') {
        dataGrid.filter(['UserType', '=', value])
      } else if (value === 'Deleted' || value === 'Active') {
        dataGrid.filter(['UserStatus', '=', value])
      } else {
        dataGrid.clearFilter()
      }
    },
  },
}
</script>
<style lang="scss">
.dx-row.dx-data-row .employee {
  color: #bf4e6a;
  font-weight: bold;
}

.dx-texteditor.dx-state-focused.dx-editor-outlined,
.dx-texteditor.dx-state-hover {
  border-color: var(--main-primary);

  .dx-list-item.dx-list-item-selected {
    background-color: #bf4e6a;
  }
}
</style>
