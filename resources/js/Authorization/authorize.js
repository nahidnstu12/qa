import policies from './policies'
export default {
    install(Vue,options){
        Vue.prototype.authorize =function (policy,model){
    
        if(!window.Auth.isSigned){
            return false
        }
        if(typeof policy === 'string' && typeof model ==='object'){
            const user = window.Auth.user

            return policies[policy](user,model)
        }
      }
    Vue.prototype.isSigned = window.Auth.isSigned
    }
   
}
