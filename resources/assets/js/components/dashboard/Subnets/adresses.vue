<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-lg-20">
                <div class="row">
                    <div class="col-lg-4">
                        <h1>Subnet: {{subnetDetails.name}} </h1>
                    </div>
                    <div class="col-lg-16">
                        <at-input placeholder="Enter your search-Term" v-model="searchTerm" icon="search" v-on:change="triggerResync"/>
                    </div>
                    <div class="col-lg-4">
                        <at-checkbox v-model="compact" label="compact Mode">Enable Compact View</at-checkbox>

                    </div>
                </div>
                <hr>
                <div v-if="!compact">
                    <div class="row">
                        <div class="col-lg-24">
                            <h2>Online IPv4: {{subnetDetails.countedIPs}}</h2>
                            <hr>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <at-card style="width: 100%;" class="justify-content-center" :loading="cardLoading" >
                                <h4 slot="title"  class="hostname created" > Subnet-Statistics</h4>
                                <div>
                                    <ul class="subnet-stats">
                                        <li><b>Name</b>: <span>{{subnetDetails.name}}</span></li>
                                        <li><b>Subnet</b>: <span>{{subnetDetails.subnet}}</span></li>
                                        <li><b>Start</b>: <span>{{subnetDetails.start}}</span></li>
                                        <li><b>End</b>: <span>{{subnetDetails.end}}</span></li>
                                        <li><b>Used IPv4</b>: <span>{{subnetDetails.countedIPs}} <small>({{Math.round(subnetDetails.countedIPs/subnetDetails.size*1000)/10}}%)</small></span></li>
                                        <li><b># of IPv4</b>: <span>{{subnetDetails.size}}</span></li>
                                        <li><b>Creator</b>: <span>{{subnetDetails.creator_name}}</span></li>
                                        <li><b>Created</b>: <span>{{subnetDetails.created_at}}</span></li>
                                    </ul>
                                </div>
                            </at-card>

                        </div>
                        <div class="col-lg-6 " v-for="item in this.onlineAdresses">
                            <at-card style="width: 100%; height: 90%;" class="justify-content-center" :loading="cardLoading" >
                                <h4 slot="title"  :class="item.status" class="hostname" v-if="item.hostname"> {{item.hostname}}</h4>
                                <h4 slot="title" :class="item.status" class="hostname" v-else> {{item.adress}}</h4>
                                <div v-if="!compact">
                                    <ul>
                                        <li>IP-Adress: {{item.adress}}</li>
                                        <li>Last seen: <span v-if="item.lastFound">{{ item.lastFound | moment("from") }}</span> <span v-else>Never</span></li>
                                    </ul>
                                    <br>
                                    <at-table :columns="portTableLayout" :page-size="5" :data="item.open_ports" pagination/>

                                </div>
                            </at-card>
                            <br>
                            <br>
                        </div>
                    </div>
                    <div v-if="!searchTerm">
                    <h3>Offline IPv4</h3><br>

                        <at-table :columns="tableLayoutAdresses"size="small"  :data="badAdresses" pagination/>
                    </div>
                </div>
                <div v-else>
                    <div class="row">
                        <div class="col-lg-4 " v-for="item in this.onlineAdresses">
                            <h4 class="hostname compact" :class="item.status"> {{item.shortDomain}}</h4>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {

            this.subnet = this.$route.params.id;
            this.triggerResync();
            setInterval(function () {
                this.triggerResync();
            }.bind(this), 2500);

        },
        methods: {
            triggerResync() {
                this.getOnlineIPs();
                this.getOfflineIPs();
                this.loadSubnetDetails();
            },
            getOnlineIPs(){
                let resultPromise = this.$askApp.makeProtectedGET("api/ips/"+this.subnet+"/"+this.searchTerm+"?status=online&limit=200")
                resultPromise.then((data)=>{
                    this.onlineAdresses = data.data.response;
                    this.cardLoading = false;
                }).catch((error)=>{
                    this.$Message.error("There was an error communicating with the backend. Please try again later.")
                    console.log(error);

                });
            },
            getOfflineIPs(){
                let resultPromise = this.$askApp.makeProtectedGET("api/ips/"+this.subnet+"/"+this.searchTerm+"?status=offline&limit=254")
                resultPromise.then((data)=>{
                    this.badAdresses = data.data.response;
                }).catch((error)=>{
                    this.$Message.error("There was an error communicating with the backend. Please try again later.")
                    console.log(error);

                });
            },
            loadSubnetDetails() {
                let resultPromise = this.$askApp.makeProtectedGET("api/subnets/"+this.subnet)
                resultPromise.then((data)=>{
                    this.subnetDetails = data.data.response;
                }).catch((error)=>{
                    this.$Message.error("There was an error communicating with the backend. Please try again later.")
                    console.log(error);

                });

            }
        },
        data(){
            return {
                onlineAdresses:[{},{},{},{},{},{}],
                badAdresses:[],
                searchTerm: "",
                subnet: 0,
                compact: 0,
                subnetDetails: [],
                subnetName: "",
                portTableLayout: [
                    {
                        title: "Program",
                        key: "app"
                    },
                    {
                        title: "Port",
                        key: "port"
                    },
                    {
                        title: "Type",
                        key: "type"
                    }

                ],
                cardLoading: true,
                tableLayoutAdresses: [

                    {
                        title: 'IPv4',
                        key: "adress"
                    },
                    {
                        title: 'Status',
                        render: (h, params) => {
                            let status = params.item.status;
                            let color ="";
                            if(status ==="up") {
                                color ="success"
                            } else if(status === "down") {
                                color = "default";
                            } else if(status === "error") {
                                color = "error";
                            }
                            return h('div', [
                                h("at-tag", {
                                    props: {
                                        color: color,
                                    },

                                },status)
                            ])
                        }
                    },
                    {
                        title: 'Hostname',
                        render: (h, params) => {

                            if(params.item.hostname === '') {
                                return h('div', [
                                    "Unknown"
                                ])

                            }

                            return h('div', [
                                params.item.hostname
                            ])
                        }
                    },
                    {
                        title: 'Last seen',
                        render: (h, params) => {

                            if(params.item.lastFound === 0) {
                                return h('div', [
                                    "Never"
                                ])

                            }

                            return h('div', [
                                this.$options.filters.moment(params.item.lastFound,"from")
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
    .hostname:before {
        content: '';
        display: inline-block;
        width: 15px;
        height: 15px;
        -moz-border-radius: 7.5px;
        -webkit-border-radius: 7.5px;
        border-radius: 7.5px;
        animation: blinker 2s linear infinite;

    }
    .up:before {
        background-color: #13ce66;
    }
    .down:before {
        background-color: #ff4949;

    }
    .error:before {
        background-color: #ffc82c;

    }
    .created:before {
        background-color: #78a4fa;
    }
    @keyframes blinker {
        25% {
            opacity: 1;
        }
        50% {
            opacity: 0;
        }
    }

    .compact {
        border: 1px solid #ECECEC;
        background-color: white;
        position: relative;
        line-height: 48px;
        padding: 0 24px;
    }

    ul.subnet-stats {
        li {
            b {
                color: #6190e8;
            }
            span {
                float: right;
            }
            border: 1px solid #ECECEC;
            background-color: white;
            position: relative;
            line-height: 36px;
            padding: 0 10px;
            border-bottom: none;
        }
        li:last-of-type {
            border: 1px solid #ECECEC;

        }
    }
</style>