<template>   
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>Your Answer</h2>                       
                    </div>
                </div>
                <div class="card-body">
                    <form @submit.prevent = "create">               
                        <div class="form-group">
                            <textarea name="body" id="" rows="7" v-model="body" required class="form-control"></textarea>                                             
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-outline-primary" :disabled = "invalid">Submit</button>
                        </div>                      
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:['questionId'],
    data(){
        return{
            body:'',
        }
    },
    computed:{
        invalid(){
            return !this.isSigned || this.body.length <10
        },
        endpoint () {
            // return `http://localhost:84/qa/questions/${this.questionId}/answers`;
            return `/questions/${this.questionId}/answers`;
        },

    },
    methods:{
        create(){
            axios.post(this.endpoint,{body:this.body})
            .then(({data}) =>{
                this.$toast.success(data.message,'Success')
                this.body = ''
                this.$emit('created',data.answer)
                // console.log(data)
            })
            .catch(err =>{
                this.$toast.error(err.response.data.message,'Error')
            })

        }

    }
}
</script>