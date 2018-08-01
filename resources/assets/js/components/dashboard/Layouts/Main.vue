<template>
    <div>
        <div class="row blue-background" >
            <div class="col-lg-3 no-gutters app-logo">
                <h1>Network</h1>
            </div>
            <div class="col-lg-21">
                <at-menu mode="horizontal" router>
                    <at-menu-item name="1" to="dashboard"><i class="icon icon-home"></i>Home</at-menu-item>
                    <at-menu-item name="2" to="users"><i class="icon icon-users"></i>Users</at-menu-item>
                </at-menu>

            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-24">
                <router-view></router-view>

            </div>
        </div>
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
<style lang="scss">
    .app-logo{
        width: 100%;
        height: 48px;
        text-align:center;
        h1 {
            color: white;
        }
    }
    .blue-background {
        background-color: #78A4FA;
        margin-bottom: 20px;
    }
</style>