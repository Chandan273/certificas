<template>
  <DxPieChart id="pie" :data-source="dougChartData" type="doughnut" :palette="chartOptions.palette" :title="dataTitle">
    <DxTitle :text="title" horizontalAlignment="left">
      <DxFont color="#000" size="16" :weight="weight" family="Roboto, sans-serif">
      </DxFont>
    </DxTitle>
    <DxSeries argument-field="region"></DxSeries>
    <DxLegend vertical-alignment="bottom" horizontal-alignment="center" />
    <DxTooltip :enabled="true" :customize-tooltip="customizeTooltip" :cornerRadius="5" :paddingLeftRight="10"
      :paddingTopBottom="8">
      <DxFormat type="fixedPoint" />
    </DxTooltip>
  </DxPieChart>
</template>
<script>
import DxPieChart, {
  DxSeries,
  DxTooltip,
  DxFormat,
  DxFont,
  DxTitle,
  DxLegend,
} from "devextreme-vue/pie-chart";

export default {
  name: "DoughnutChart",
  components: {
    DxPieChart,
    DxSeries,
    DxTooltip,
    DxFormat,
    DxTitle,
    DxFont,
    DxLegend,
  },
  props: {
    title: {
      type: String,
      default: null,
    },
    weight: {
      type: Number,
      default: 600,
    },
  },
  data() {
    return {
      dataTitle: {
        text: this.title,
        horizontalAlignment: "left",
      },
      dougChartData: [
        {
          region: "New",
          val: 113,
        },
        {
          region: "Returned",
          val: 64,
        },
      ],
      chartOptions: {
        palette: ["#ff0000", "rgb(153, 153, 153)"],
      },
    };
  },
  methods: {
    customizeTooltip(pointInfo) {
      return {
        text: `${pointInfo.argumentText} : ${pointInfo.valueText}`,
      };
    },
  },
};
</script>
<style>
#pie {
  height: 400px;
}
</style>
