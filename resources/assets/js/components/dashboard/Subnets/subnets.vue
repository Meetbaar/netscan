<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-20">
                        <h1>Subnets</h1>
                    </div>
                    <div class="col-lg-3">
                        <at-button type="success" hollow icon="icon-plus" size="small" @click="addSubnet">Add Subnet</at-button>

                    </div>
                </div>
                <hr>
                <at-alert message="Please wait while we fetch the data..." type="info" v-if="loading" style="margin-bottom: 10px;"/>

            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
            this.loadData()
        },
        methods: {
            loadData(){
            },
        },
        data(){
            return {
            };
        }
    }
</script>

<style lang="scss">
    .notFirst {
        margin-top: 15px;
    }
</style>