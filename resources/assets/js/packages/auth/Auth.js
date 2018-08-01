export default function (Vue) {
    Vue.auth = {
        //set token
        setToken(token, expiration) {
            localStorage.setItem("token", token);
            localStorage.setItem("expiration", expiration)
        },
        //get token

        getToken(){
            let token = localStorage.getItem("token")
            let expiration = localStorage.getItem("expiration")

            if(!token || !expiration) {
                return null;
            }

            if(Date.now() > parseInt(expiration)) {
                this.destroyToken();
                return null;
            }
            return token;
        },


        //destroy token
        destroyToken() {
            localStorage.removeItem("token");
            localStorage.removeItem("expiration")

        },
        //isAuthenticated

        isAuthenticated (){
            if(this.getToken()) {
                return true;
            } else {
                return false;
            }
        }
    }

    Object.defineProperties(Vue.prototype, {
        $auth: {
            get: ()=> {
                return Vue.auth
            }
        }
    });
}