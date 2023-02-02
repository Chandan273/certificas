<template>
    <div class="container">
        <div class="row justify-center text-center p-6" >
            <div class="col-lg-8 offset-md-2" >
                <img src="../assets/images/404.png" >
                <h2 data-v-d5ea9138="">OOPS! PAGE NOT FOUND</h2>
                <p>Sorry, the page you are looking for does not exist.</p>
                <router-link to="/" class="btn btn-primary">Home Page</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PageNotFound'
}
</script>
