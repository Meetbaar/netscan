<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
w            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            if(this.$auth.isAuthenticated())
            {
                this.$router.push("dashboard")
            } else {
                this.$router.push("login")
            }
        }, methods: {
            goToStart: function () {


            },
        },
        data () {
            return {
                info: null
            }
        }
    }
</script>
