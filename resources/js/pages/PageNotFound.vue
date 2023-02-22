<template>
    <v-container fluid>
        <v-row justify="center" class="text-center py-6">
            <v-col cols="12" md="8" class="offset-md-2">
                <img src="../assets/images/404.png" style="width: 800px" />
                <h2>{{ $t("opsPageNotFound") }}</h2>
                <p>{{ $t("pageNotExist") }}</p>
                <v-btn class="mt-5" to="/" color="primary">{{
                    $t("homePage")
                }}</v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "PageNotFound",
};
</script>
