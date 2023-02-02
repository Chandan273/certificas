<template>
    <DxChart id="chart" :data-source="dataSource" :palette="chartOptions.palette">
        <DxTitle text="Trafic Type" horizontalAlignment="left">
            <DxFont color="#000" size="16" :weight="weight" family="Roboto, sans-serif"></DxFont>
        </DxTitle>
        <DxCommonSeriesSettings argument-field="state" type="fullstackedbar" />

        <DxSeries value-field="maleyoung" name="Male" stack="male" />
        <DxSeries value-field="femaleyoung" name="Female" stack="male" />
        <DxLegend vertical-alignment="bottom" horizontal-alignment="center" />

        <DxTooltip :customize-tooltip="customizeTooltip" :enabled="true" :cornerRadius="5" :paddingLeftRight="10" :paddingTopBottom="8" />
    </DxChart>
</template>

<script>
import {
    DxChart,
    DxSeries,
    DxCommonSeriesSettings,
    DxLegend,
    DxTooltip,
    DxTitle,
    DxFont,
    DxMargin
} from 'devextreme-vue/chart';

export default {
    name: "FullStack",
    components: {
        DxChart,
        DxSeries,
        DxCommonSeriesSettings,
        DxLegend,
        DxTooltip,
        DxTitle,
        DxFont,
        DxMargin
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
            chartOptions: {
                palette: ["#ff0000", "rgb(153, 153, 153)"],
            },
            dataSource: [{
                state: 'USA',
                maleyoung: 26,
                femaleyoung: 297,
            }, {
                state: 'Russia',
                maleyoung: 193,
                femaleyoung: 971,
            }, {
                state: 'Japan',
                maleyoung: 575,
                femaleyoung: 95,
            }, {
                state: 'Brazil',
                maleyoung: 516,
                femaleyoung: 519,
            }, {
                state: 'Mexico',
                maleyoung: 1393,
                femaleyoung: 171,
            }, {
                state: 'Germany',
                maleyoung: 95,
                femaleyoung: 9105,
            }, {
                state: 'United Kindom',
                maleyoung: 5816,
                femaleyoung: 522,
            }
            ],
        };
    },
    methods: {
        customizeTooltip({ percentText, valueText }) {
            return {
                text: `${percentText} - ${valueText}`,
            };
        },
    },
};
</script>
<style>
#chart {
    height: 400px;
}
</style>