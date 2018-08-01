export default function (Vue) {
    Vue.askApp = {
        //makeProtectedPOST
        makeProtectedPOST(url, data) {
            let token = Vue.auth.getToken();

            return Vue.axios.post(url,data,this.getConfig(token));

        },
        //makeProtectedGET
        makeProtectedGET(url) {
            let token = Vue.auth.getToken();

            return Vue.axios.get(url,this.getConfig(token));

        },
        //makeTestRequest
        makeTestRequest() {
            let token = Vue.auth.getToken();
            if(token === null) {
                Vue.auth.destroyToken();
                Vue.router.push("/login");

            }

            Vue.axios.get("api/user",this.getConfig(token)).then(()=>{

            }).catch((error) => {
                Vue.auth.destroyToken()
                Vue.router.push("/login")
            })

        },
        getConfig(token) {
            return {
                'headers': {
                    'Authorization': 'Bearer '+token
                }
            }
        }
    }

    Object.defineProperties(Vue.prototype, {
        $askApp: {
            get: ()=> {
                return Vue.askApp
            }
        }
    });
}