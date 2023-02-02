<template>
    <div id="chart-demo">
        <DxChart id="chart" :data-source="sharingStatisticsInfo" :palette="chartOptions.palette">
            <DxTitle text="Last year Earning" horizontalAlignment="left">
                <DxFont color="#000" size="16" :weight="weight" family="Roboto, sans-serif"></DxFont>
            </DxTitle>

            <DxCommonSeriesSettings :type="type" argument-field="year" />
            <DxCommonAxisSettings>
                <DxGrid :visible="true" />
            </DxCommonAxisSettings>

            <DxSeries v-for="architecture in architectureSources" :key="architecture.value"
                :value-field="architecture.value" :name="architecture.name" />

            <DxArgumentAxis :allow-decimals="false" :axis-division-factor="60">
                <DxLabel>
                    <DxFormat type="decimal" />
                </DxLabel>
            </DxArgumentAxis>

            <DxLegend vertical-alignment="bottom" horizontal-alignment="center" />
            <DxTooltip :enabled="true" :cornerRadius="5" :paddingLeftRight="10" :paddingTopBottom="8">
                <DxFormat type="currency" :precision="2" />
            </DxTooltip>
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
    name: "PaymentsComponent",
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
                year: 'January',
                revenue: 263,
            }, {
                year: 'February',
                revenue: 169,
            }, {
                year: 'March',
                revenue: 557,
            }, {
                year: 'April',
                revenue: 140,
            }, {
                year: 'May',
                revenue: 560,
            }, {
                year: 'June',
                revenue: 260,
            }, {
                year: 'July',
                revenue: 600,
            }, {
                year: 'August',
                revenue: 100,
            }, {
                year: 'September',
                revenue: 250,
            }, {
                year: 'October',
                revenue: 250,
            }, {
                year: 'November',
                revenue: 650,
            }, {
                year: 'December',
                revenue: 25,
            },
            ],
            architectureSources: [
                { value: 'revenue', name: 'Revenue' },
            ],
            types: ['spline', 'stackedspline', 'fullstackedspline'],
            type: 'spline',
        };
    },
};
</script>