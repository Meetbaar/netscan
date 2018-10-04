<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-lg-20">
                <div class="row">
                    <div class="col-lg-24">
                        <h1>System Dashboard</h1>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-24">
                        <h3>IP Stats</h3><br>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <at-table :columns="ipTableLayout" :data="ipadresses"/>
                        <div class="row">
                            <div class="col-lg-24">
                                <br><h3>Queue Stats</h3><br>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12" v-for="item in this.queues">
                                <at-card style="width: 100%;" class="justify-content-center" :loading="cardLoading">
                                    <h4 slot="title">Queue #{{ item.id}} <small>ETA: {{calcEndTime(item.load, item.change/2.5)}}</small></h4>
                                    <div>
                                        <h3>Current Rate: {{item.change/2.5}}<small> Items/sec.</small></h3>
                                        <h6>Total Items: {{item.load}}</h6>

                                    </div>
                                </at-card>
                                <br>
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-12">
                        <h3>Queue Stats</h3><br>

                        <at-table :columns="tableLayoutJobStats" :data="job"/>


                    </div>
                    <div class="col-lg-12">

                    </div>
                </div>

            </div>

        </div>
        <div class="row no-gutter">
            <div class="col-sm-24 col-md-12">
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
            this.loadJobs();
            this.loadQueueLoad();
            this.loadIPList()

            setInterval(function () {
                this.loadJobs();
                this.loadQueueLoad();
                this.loadIPList()

            }.bind(this), 2500);

        },
        methods: {

            loadJobs(){
                let resultPromise = this.$askApp.makeProtectedGET("api/jobs/list")
                resultPromise.then((data)=>{
                    this.job = data.data.response;
                }).catch((error)=>{
                    this.$Message.error("There was an error communicating with the backend. Please try again later.")
                    console.log(error);

                });
            },
            loadQueueLoad(){
                let resultPromise = this.$askApp.makeProtectedGET("api/jobs/load")
                resultPromise.then((data)=>{

                    data.data.response.forEach((item, index)=>{
                        let current = this.queues[index];

                        if(!current || (!current.old && current.old !== 0)) {
                            current = {id: "", load: "0", old: "0", change: ""};

                        }
                        let change = parseInt(item.load) - parseInt(current.old);
                        item.old = item.load;
                        item.change = change;
                        this.queues[index] = item;

                    });
                    this.cardLoading = false;
                }).catch((error)=>{
                    this.$Message.error("There was an error communicating with the backend. Please try again later.")
                    console.log(error);

                });
            },
            loadIPList(){
                let resultPromise = this.$askApp.makeProtectedGET("api/ips/latest")
                resultPromise.then((data)=>{

                    this.ipadresses = data.data.response;

                }).catch((error)=>{
                    this.$Message.error("There was an error communicating with the backend. Please try again later.")
                    console.log(error);

                });
            },
            calcEndTime(amount, change) {
                if(change >= 0) {
                    return "Unknown"
                }
                return Math.round((amount/change)*(-1)/60)+" Minutes";
            }


        },
        data(){
            return {
                job:[],
                tableLayoutJobStats: [
                    {
                        title: 'Name',
                        render: (h, params) => {
                            return h('div', [
                                "["+params.item.job_id+"] "+params.item.log
                            ])
                        }
                    },
                    {
                        title: 'Status',
                        render: (h, params) => {
                            let status = params.item.status;
                            let color ="";
                            if(status ==="running") {
                                color ="success"
                            } else if(status === "scheduled") {
                                color = "primary";
                            } else if(status === "done") {
                                color = "default";
                            } else if(status === "error") {
                                color = "error";
                            }
                            return h('div', [
                                h("at-tag", {
                                    props: {
                                        color: color,
                                    },

                                },params.item.status)
                            ])
                        }
                    },
                    {
                        title: 'Progress',
                        render: (h, params) => {
                            return h('div', [
                                h('at-progress', {
                                    props: {
                                        percent: params.item.progress,
                                    }
                                }),
                            ])
                        }
                    }
                ],
                tableLayoutQueueLoad: [
                    {
                        title: 'Name',
                        render: (h, params) => {
                            return h('div', [
                                params.item.id
                            ])
                        }
                    },
                    {
                        title: 'Progress',
                        render: (h, params) => {
                            return h('div', [
                                params.item.change
                            ])
                        }
                    }
                ],
                ipTableLayout: [
                    {
                        title: 'Adress',
                        key: "adress"
                    },
                    {
                        title: 'Subnet',
                        key: "subnet_name"
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
                    }
                ],
                queues: [{},{},{},{},{}],
                ipadresses: [],
                cardLoading: true
            }
        }
    }
</script>
<style lang="scss">
</style>