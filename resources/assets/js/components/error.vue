<template>
    <div>
        <h1>Internal Error</h1>
        <at-button @click="goToStart">Go back home...</at-button>

    </div>
</template>
<script>
    export default {
        mounted() {

        },
        methods: {
            goToStart: function () {
                this.$router.push({path: "/dashboard"})
            }
        }
    }
</script>