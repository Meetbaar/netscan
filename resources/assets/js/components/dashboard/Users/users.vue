<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-20">
                        <h1>Users</h1>
                    </div>
                    <div class="col-lg-3">
                        <at-button type="success" hollow icon="icon-plus" size="small" @click="addUser">Add User</at-button>

                    </div>
                </div>
                <hr>
                <at-alert message="Please wait while we fetch the data..." type="info" v-if="loading" style="margin-bottom: 10px;"/>
                <at-table :columns="columns" :data="users" border pagination/>

            </div>
            <div class="col-lg-6" v-if="showUserProfile">
                <div class="row">
                    <div class="col-lg-20">
                        <h1>Editing {{selectedUser.username}} </h1>
                    </div>
                    <div class="col-lg-3">
                        <at-button type="error" hollow icon="icon-x" size="small" @click="closeUser"/>

                    </div>
                </div>
                <hr>
                <at-table :columns="this.userdetailedView.columns" :data="this.userdetailedView.data"/>

            </div>
            <div class="col-lg-6" v-if="showUserAdd">
                <div class="row">
                    <div class="col-lg-20">
                        <h1>Creating User {{newUser.username}} </h1>
                    </div>
                    <div class="col-lg-3">
                        <at-button type="error" hollow icon="icon-x" size="small" @click="closeCreate"/>

                    </div>
                </div>
                <hr>
                <at-input v-model="newUser.username" placeholder="Please enter the username" >
                    <template slot="prepend">
                        <span>Username</span>
                    </template>
                </at-input>
                <at-input v-model="newUser.email" placeholder="Please enter the E-Mail" class="notFirst" type="email">
                    <template slot="prepend">
                        <span>E-Mail</span>
                    </template>
                </at-input>
                <at-input v-model="newUser.password" placeholder="Please enter a password" class="notFirst" type="password">
                    <template slot="prepend">
                        <span>Password</span>
                    </template>
                </at-input>

                <at-button icon="icon-save" type="success" class="notFirst" @click="createUser">Save user</at-button>
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
                this.$Loading.start();
                let resultPromise = this.$askApp.makeProtectedGET("api/users")
                    resultPromise.then((data)=>{
                        this.users = data.data.response;
                        this.$Loading.finish();
                        this.loading=false;
                    }).catch((error)=>{
                        this.$Loading.finish();
                        this.$Message.error("There was an error communicating with the backend. Please try again later.")
                        this.loading=false;

                    });
            },
            addUser() {
                this.$Loading.start();
                this.showUserAdd = true;
                this.$Loading.finish();

            },
            profile(user){
                this.$Loading.start()
                this.selectedUser = user;

                this.userdetailedView = {
                    columns: [{
                            title: "Property",
                            key: "property"
                        },
                        {
                            title: "Value",
                            key: "value"
                        }],
                    data: [
                        {
                            property: "Username",
                            value: user.username
                        },
                        {
                            property: "E-Mail",
                            value: user.email
                        },
                        {
                            property: "Created",
                            value: user.created_at
                        },
                        {
                            property: "Last Update",
                            value: user.updated_at
                        }
                    ]

                };

                this.showUserProfile = true;
                this.$Loading.finish()
            },
            closeUser(){
                this.showUserProfile = false;
                this.selectedUser = null;
            },
            closeCreate(){
                this.showUserAdd = false;
            },
            createUser(){
                this.$Loading.start();
                let responsePromise = this.$askApp.makeProtectedPOST("api/user",this.newUser);
                responsePromise.then((response)=>{
                    console.log(response)
                })
            }
        },
        data(){
            return {
                columns: [
                    {
                        title: 'Name',
                        key: 'username'
                    },
                    {
                        title: 'eMail',
                        key: 'email'
                    },
                    {
                        title: 'Created',
                        key: 'created_at'
                    },
                    {
                        title: 'Options',
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
                                    on: {
                                        click: () => {
                                            this.profile(params.item)
                                        }
                                    }
                                }, 'View Profile'),
                                h('AtButton', {
                                    props: {
                                        size: 'small',
                                        hollow: true
                                    },
                                    on: {
                                        click: () => {
                                            this.$Message(params.item.email)
                                        }
                                    }
                                }, 'View Address')
                            ])
                        }
                    }

                ],
                users: [],
                loading: true,
                newUser: {
                    username: '',
                    password: '',
                    email: ''
                },
                showUserProfile: false,
                selectedUser: null,
                showUserAdd: false,
                userdetailedView: {

                }
            };
        }
    }
</script>

<style lang="scss">
    .notFirst {
        margin-top: 15px;
    }
</style>