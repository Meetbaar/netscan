<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-lg-20">
                <div class="row">
                    <div class="col-lg-21">
                        <h1>Subnets</h1>
                    </div>
                    <div class="col-lg-3">
                        <at-popover placement="bottom" v-model="show" @toggle="toggleShow">
                            <at-button type="success" hollow icon="icon-plus">Add Subnet</at-button>
                            <template slot="content">
                                <p>Create new Subnet</p><br>

                                <at-input v-model="newSubnet.name" size="small" placeholder="Enter a descriptive name"/><br>
                                <at-input v-model="newSubnet.subnet" size="small" placeholder="CIDR (10.0.0.0/24)"/>

                                <div style="text-align: right; margin-top: 8px;">
                                    <at-button size="smaller" @click="show = false">Cancel</at-button>
                                    <at-button type="primary" size="smaller" @click="createSubnet">Save</at-button>
                                </div>
                            </template>
                        </at-popover>


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
            createSubnet() {
                this.$Loading.start();
                let responsePromise = this.$askApp.makeProtectedPOST("api/subnets",this.newSubnet);
                console.log(this.newSubnet);
                responsePromise.then((response)=>{
                    console.log(response)
                })
            }

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
                                        hollow: true,

                                    },
                                    on: {
                                        click: () => {
                                            this.$router.push({path: "/subnets/"+params.item.id});
                                        },
                                    },
                                    style: {
                                        marginRight: '8px'
                                    },

                                }, 'View IPv4 Adresses'),
                                h('AtButton', {
                                    props: {
                                        size: 'small',
                                        hollow: true,
                                    },
                                    on: {

                                    }
                                }, 'Edit Subnet')
                            ])
                        }
                    }


                ],
                newSubnet: {
                    name: "",
                    subnet: ""
                },
                show: false,
            }
        }
    }
</script>

<style lang="scss">
    .notFirst {
        margin-top: 15px;
    }
</style>