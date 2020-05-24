<template>
<div class="d-flex flex-column vote-controls">
    <a :title="title('up')"  
        @click.prevent = "voteUp"
        class="vote-up" :class="classes">
        <i class="fas fa-caret-up fa-3x"></i>
    </a>
    
    <span class="votes-count">
        {{ count}}
    </span>
    <a :title="title('down')" 
        @click.prevent = "voteDown"
        class="vote-down" :class="classes">
        <i class="fas fa-caret-down fa-3x"></i>
    </a>
   

       <favourite v-if="name === 'question'" :question = "model"></favourite>
       <accept v-else :answer = "model"></accept>

</div>
    
</template>

<script>
import Favourite from './Favourite.vue'
import Accept from './Accept.vue'

export default {
    props :['name','model'],

    computed :{
        classes (){
            return this.isSigned ? '' : 'off'
        },
         endpoint () {
            return `http://localhost:84/qa/${this.name}s/${this.id}/vote`;
            // return `//${this.name}/${this.id}/vote`;
        },
    },
    data(){
        return {
            count : this.model.votes_count,
            id:this.model.id
        }
    },
    components:{
        Favourite,
        Accept
    },
    methods:{
        title(voteType){
            let titles = {
                up: `This ${this.name} is useful`,
                down: `This ${this.name} is not useful`,
            }
            return titles[voteType]
        },
       
        _vote(vote){
            if (! this.isSigned) {
                this.$toast.warning(`Please login to vote the ${this.name}`, "Warning", {
                    timeout: 3000,
                    position: 'topRight'
                });
                return;
            }
            axios.post(this.endpoint,{vote})
            .then(res=>{
                console.log(res)
                this.$toast.success(res.data.message, "Success", {
                    timeout: 3000,
                    position: 'topRight'
                });
                this.count = res.data.votesCount
                console.log(res)
            })
        //     .catch(function (error) {
        //     if (error.response) {
        //     // The request was made and the server responded with a status code
        //     // that falls out of the range of 2xx
        //     console.log(error.response.data);
        //     console.log(error.response.status);
        //     console.log('Error',error.response.headers);
        //     } else if (error.request) {
        //     // The request was made but no response was received
        //     // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
        //     // http.ClientRequest in node.js
        //     console.log(error.request);
        //     } else {
        //     // Something happened in setting up the request that triggered an Error
        //     console.log('Error', error.message);
        //     }
        //     console.log('Error',error.config);
        // });
        },
         voteUp(){
            this._vote(1)
        },
        voteDown(){
            this._vote(-1)
        },

    }
}
</script>