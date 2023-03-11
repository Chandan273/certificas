<template>
<v-card>
    <v-layout>
        <v-app-bar color="blue" prominent>
            <v-toolbar-title>
            </v-toolbar-title>
            <v-spacer></v-spacer>
        </v-app-bar>
        <v-main style="height:100vh;">
            <v-card-text class="ma-5 pa-5">
                <v-card elevation="2" class="rounded  card_background  mx-auto mt-5 pa-3 " max-width="90vw">
                    <v-list-item two-line>
                        <v-row>
                            <v-col cols="12" v-if="isExpired === 1">
                                <v-list-item-title class="text-lg-h4 text-sm-h4  font-weight-bold">
                                    <v-icon large color="red" class="verify_icon"> mdi-close-circle</v-icon>
                                    Verified
                                </v-list-item-title>
                            </v-col>
                            <v-col cols="12" v-else>
                                <v-list-item-title class="text-lg-h4   text-sm-h4  font-weight-bold">
                                    <v-icon large color="green  "> mdi-check-circle</v-icon> Verified
                                </v-list-item-title>
                                
                            </v-col>
                        </v-row>
                        <v-list-item-subtitle class="my-2"> Dit certificaat is geldig t/m {{ this.student.expired_date }}
                        </v-list-item-subtitle>
                    </v-list-item>
                    <v-divider></v-divider>
                    <v-card-text class="complition_text text-h5 font-weight-bold ">
                        Certificaat van het {{ this.student.course_name }} programma.
                    </v-card-text>
                    <v-card-text>
                        <v-row align="center">
                            <v-col class="font-weight-bold" cols="6">

                                <p class="lable_text">
                                    Uitgereikt aan :
                                </p>
                            </v-col>
                            <v-col cols="6">
                                <p class="value_text">
                                    {{ this.student.name }}
                                </p>
                            </v-col>
                        </v-row>
                        <v-row align="center">
                            <v-col class="font-weight-bold" cols="6">

                                <p class="lable_text">
                                    Cursus aanbieder :
                                </p>
                            </v-col>
                            <v-col cols="6">
                                <p class="value_text">
                                    {{ this.student.company_name }}
                                </p>
                            </v-col>
                        </v-row>
                        <v-row align="center">
                            <v-col class="font-weight-bold" cols="6">

                                <p class="lable_text">
                                    Cursus naam :
                                </p>
                            </v-col>
                            <v-col cols="6">
                                <p class="value_text">
                                    {{ this.student.course_name }}
                                </p>
                            </v-col>
                        </v-row>
                        <v-row align="center">
                            <v-col class="font-weight-bold" cols="6">
                                <p class="lable_text">
                                    Geboortedatum :
                                </p>
                            </v-col>
                            <v-col cols="6">
                                <p class="value_text">
                                    {{ this.student.birth_date }}
                                </p>
                            </v-col>
                        </v-row>
                        <v-row align="center">
                            <v-col class="font-weight-bold" cols="6">
                                <p class="lable_text">
                                    Datum toetsing :
                                </p>
                            </v-col>
                            <v-col cols="6">
                                <p class="value_text">
                                    {{ this.student.valid_from }}
                                </p>
                            </v-col>
                        </v-row>
                        <v-row align="center">
                            <v-col class="font-weight-bold" cols="6">
                                <p class="lable_text">
                                    Geldig tot :
                                </p>
                            </v-col>
                            <v-col cols="6">
                                <p class="value_text">
                                    {{ this.student.birth_date }}
                                </p>
                            </v-col>
                        </v-row>
                        <v-row align="center">
                            <v-col class="font-weight-bold" cols="6">
                                <p class="lable_text">
                                    Geboorte plaats :
                                </p>
                            </v-col>
                            <v-col cols="6">
                                <p class="value_text">
                                    {{ this.student.birth_place }}
                                </p>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-row>
                            <v-col cols="12">
                            </v-col>
                        </v-row>
                    </v-card-actions>
                </v-card>
            </v-card-text>
        </v-main>
    </v-layout>
</v-card>
</template>

<script>
export default {
    name: "StudentInfo",
    data() {
        return {
            student: [],
        }
    },
    methods: {
        async getStudentInfo() {
            try {
                const payload = {
                    student_id: this.$route.params.id,
                };
                const {
                    data
                } = await this.axios.post(`/api/student-record`, payload);
                this.student = data.studentInfo;
            } catch (err) {}
        },
    },
    computed: {
        isExpired() {
            const expiredDate = new Date(this.student.expired_date);
            const currentDate = new Date();
            if (expiredDate < currentDate) {
                return 1;
            }
            return 0;
        },
    },
    mounted() {
        this.getStudentInfo();
    },
}
</script>

<style>
.complition_text {
    color: green;
}

p.lable_text {
    font-size: 14px;
    font-weight: 300;

}

p.value_text {
    font-weight: 500;
}

.card_background {
    background-color: #b3b3b32e;
}
</style>
