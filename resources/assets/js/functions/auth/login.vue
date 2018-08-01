<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default" style="margin-top: 250px;">
                    <div class="card-header">App Login</div>
                    <div class="justify-content-center">

                        <div class="row">
                            <div class="col-lg-22 col-md-offset-1">
                                <at-alert v-bind:message="alertMessage" v-if="alertMessage != null" show-icon type="error" style="margin-top: 20px; "/>

                            </div>
                        </div>

                    </div>

                    <div class="card-body">
                        <at-input
                                v-model="mail"
                                placeholder="e-Mail (xxx@nextbike.com)"
                                icon="user"
                                v-bind:status="usernameStatus"
                                type="email"
                                autofocus="true"
                        />


                        <at-input
                                v-bind:status="passwordStatus"
                                type="password"
                                v-model="password"
                                placeholder="Password"
                                icon="lock"
                                style="margin-top: 10px;"
                        />
                        <br>
                        <at-button type="primary" @click="login" v-bind:loading="loginLoading">Login</at-button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {

        },
        methods: {
            login(){
                this.alertMessage = null;
                this.usernameStatus = "";
                this.passwordStatus = "";
                this.loginLoading = true;
                this.$Loading.start();

                let client_id = 2;
                let secret = "nBuDW2tM93r4DMzFzGoXMn9DDytrYaEte9PQlzhM";

                const resultPromise = this.$http.post("/oauth/token",
                    {
                        username: this.mail,
                        password: this.password,
                        client_id: client_id,
                        client_secret: secret,
                        grant_type: "password"
                    });
                resultPromise.then((data) => {
                    this.$auth.setToken(data.data.access_token, data.data.expires_in+Date.now())
                    this.$Message.success("Login successful!")
                    this.$Loading.finish()
                    this.loginLoading = false;
                    this.$router.push("dashboard")

                })

                .catch((error) => {

                    this.$Loading.finish()
                    this.loginLoading = false;
                    if(error.response.data  != null) {

                        this.$Message.error("Login failed");

                        this.alertMessage = error.response.data.message;

                    } else {
                        this.alertMessage = "Internal Error"

                    }
                })
            }
        },
        data() {
            return {
                mail: "",
                password: "",
                loginLoading: false,
                usernameStatus: '',
                passwordStatus: '',
                alertMessage: null,
            }
        }
    }
</script>

<style lang="scss">
    .hidden {
        display: none;
    }
</style>