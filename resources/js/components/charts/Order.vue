<template>
    <div id="chart-demo">
        <DxChart id="chart" :data-source="sharingStatisticsInfo" :palette="chartOptions.palette">
            <DxTitle text="Yearly Orders" horizontalAlignment="left">
                <DxFont color="#000" size="16" :weight="weight" family="Roboto, sans-serif"></DxFont>
            </DxTitle>

            <DxCommonSeriesSettings :type="type" argument-field="year" />
            <DxCommonAxisSettings>
                <DxGrid :visible="true"/>
            </DxCommonAxisSettings>

            <DxSeries v-for="architecture in architectureSources" :key="architecture.value"
                :value-field="architecture.value" :name="architecture.name" />

            <DxArgumentAxis :allow-decimals="false" :axis-division-factor="60">
                <DxLabel>
                    <DxFormat type="decimal" />
                </DxLabel>
            </DxArgumentAxis>

            <DxLegend vertical-alignment="bottom" horizontal-alignment="center" />
            <DxTooltip :enabled="true" :cornerRadius="5" :paddingLeftRight="10" :paddingTopBottom="8"/>
        </DxChart>
    </div>
</template>
<script>

import {
    DxChart,
    DxSeries,
    DxArgumentAxis,
    DxCommonSeriesSettings,
    DxCommonAxisSettings,
    DxGrid,
    DxLegend,
    DxTooltip,
    DxLabel,
    DxFormat,
    DxRow,
    DxTitle,
    DxFont,
} from 'devextreme-vue/chart';
import DxSelectBox from 'devextreme-vue/select-box';
export default {
    name: "orderTable",
    components: {
        DxSelectBox,
        DxChart,
        DxSeries,
        DxArgumentAxis,
        DxCommonSeriesSettings,
        DxCommonAxisSettings,
        DxGrid,
        DxLegend,
        DxTooltip,
        DxLabel,
        DxFormat,
        DxRow,
        DxTitle,
        DxFont,
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

            sharingStatisticsInfo: [{
                year: 2018,
                orders: 263,
            }, {
                year: 2019,
                orders: 169,
            }, {
                year: 2020,
                orders: 57,
            }, {
                year: 2021,
                orders: 40,
            }, {
                year: 2022,
                orders: 560,
            }, {
                year: 2023,
                orders: 250,
            }],
            architectureSources: [
                { value: 'orders', name: 'orders' },
            ],
            types: ['spline', 'stackedspline', 'fullstackedspline'],
            type: 'spline',
        };
    },
};
</script>