<template>
  <div id="dxTableComponent">
    <!-- <div class="d-flex justify-space-between align-center mb-3">
            <div class="logo">
                <h4 class="table-title">{{ tableTitle }}</h4>
            </div>
            <div class="right-side me-14">
                <DxSelectBox :items="statuses" :value="statuses[0]" @value-changed="onValueChanged" />
            </div>
        </div> -->
    <DxDataGrid
      id="gridContainer"
      :data-source="dataSource"
      :allow-column-reordering="true"
      :show-borders="false"
      :word-wrap-enabled="true"
      :key-expr="keyExpr"
    >
      <DxHeaderFilter :visible="false"></DxHeaderFilter>

      <DxSearchPanel :visible="true" />
      <DxEditing
        :allow-updating="true"
        :allow-deleting="true"
        :allow-adding="true"
        :use-icons="true"
        mode="popup"
      >
        <slot></slot>
      </DxEditing>

      <!-- <DxColumn type="buttons" fixed="true" fixed-position="right">
                <DxButton name="edit" />
                <DxButton name="delete" />
                <DxButton name="copy" :visible="isCloneIconVisible" />
            </DxColumn> -->

      <DxColumn
        :data-field="item.key"
        :caption="item.caption"
        :data-type="item.type"
        v-for="(item, index) in dataType"
        :key="index"
      />

      <DxPaging :page-size="10" />
      <DxPager
        :visible="true"
        :allowed-page-sizes="pageSizes"
        :display-mode="displayMode"
        :show-page-size-selector="true"
        :show-info="true"
        :show-navigation-buttons="true"
      />
      <DxScrolling :mode="scrollingMode" />
    </DxDataGrid>
  </div>
</template>
<script>
import DxButton from "devextreme-vue/button";
import DxSelectBox from "devextreme-vue/select-box";
import {
  DxDataGrid,
  DxColumn,
  DxEditing,
  DxPaging,
  DxLookup,
  DxPager,
  DxHeaderFilter,
  DxScrolling,
  DxSearchPanel,
  DxForm,
  DxItem,
} from "devextreme-vue/data-grid";

export default {
  components: {
    DxDataGrid,
    DxColumn,
    DxEditing,
    DxPaging,
    DxButton,
    DxLookup,
    DxPager,
    DxHeaderFilter,
    DxSelectBox,
    DxScrolling,
    DxSearchPanel,
    DxForm,
    DxItem,
  },
  props: {
    tableTitle: {
      type: Array,
      default: "",
    },
    dataSource: {
      type: Object,
      default: "",
    },

    dataType: {
      type: Array,
      default: [],
    },
    keyExpr: {
      type: String,
      default: "",
    },
  },

  data() {
    return {
      scrollingMode: "standard",
      statuses: ["All", "Deleted"],
      events: [],
      // pageSizes: [5, 10, 15, 20],
      // showPageSizeSelector: true,
      // showInfo: true,
      // showNavButtons: true,
    };
  },
  methods: {
    logEvent(eventName) {
      this.events.unshift(eventName);
    },
    clearEvents() {
      this.events = [];
    },
    isCloneIconVisible(e) {
      return !e.row.isEditing;
    },
  },
};
</script>
<style lang="scss">
// #dxTableComponent {
//     position: relative;

//     .dx-datagrid-header-panel {
//         position: absolute;
//         right: 0;
//         top: -48px;
//         z-index: 1;
//     }
// }
</style>
