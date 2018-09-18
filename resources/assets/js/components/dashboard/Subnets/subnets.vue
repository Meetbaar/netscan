<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-lg-20">
                <div class="row">
                    <div class="col-lg-20">
                        <h1>Subnets</h1>
                    </div>
                    <div class="col-lg-3">

                    </div>
                </div>
                <hr>
                <at-table :columns="tableLayoutSubnet" :data="subnets"/>

            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
            this.loadSubnet();

        },
        methods: {

            loadSubnet(){
                let resultPromise = this.$askApp.makeProtectedGET("api/subnets/")
                resultPromise.then((data)=>{
                    this.subnets = data.data.response;
                }).catch((error)=>{
                    this.$Message.error("There was an error communicating with the backend. Please try again later.")
                    console.log(error);

                });
            },

        },
        data(){
            return {
                subnets:[],
                tableLayoutSubnet: [
                    {
                        title: 'ID',
                        key: "id"
                    },
                    {
                        title: 'Name',
                        key: "name"
                    },
                    {
                        title: 'Subnet',
                        key: "subnet"
                    },
                    {
                        title: 'Start',
                        key: "start"
                    },
                    {
                        title: 'End',
                        key: "end"
                    },
                    {
                        title: 'Creator',
                        key: "creator_name"
                    },
                    {
                        title: '# of IPv4',
                        key: "size"
                    },
                    {
                        title: 'Actions',
                        render: (h, params) => {
                            return h('div', [
                                h('AtButton', {
                                    props: {
                                        size: 'small',
                                        hollow: true
                                    },
                                    style: {
                                        marginRight: '8px'
                                    },

                                }, 'View IPv4 Adresses'),
                                h('AtButton', {
                                    props: {
                                        size: 'small',
                                        hollow: true
                                    },
                                    on: {

                                    }
                                }, 'Edit Subnet')
                            ])
                        }
                    }


                ],
            }
        }
    }
</script>

<style lang="scss">
    .notFirst {
        margin-top: 15px;
    }
</style>