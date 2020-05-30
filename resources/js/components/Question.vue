<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form class="card-body" v-if="editing"  @submit.prevent="update">
                    <div class="card-title">
                        <input class="form-control form-control-lg" type="text" v-model="title">
                    </div>
                    <hr>
                    <div class="media">
                        <div class="media-body">
                           <div class="form-group">
                                <textarea rows="10" class="form-control" v-model="body"  required></textarea>
                            </div>
                            <button type="submit" class="btn btn-info" :disabled="invalid">Update</button>
                            <button @click.prevent="cancel" class="btn btn-outline-secondary" type="button">Cancel</button>
                        </div>
                    </div>
                </form>
                <div class="card-body" v-else>
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h2> {{title}}</h2>
                            <div class="ml-auto">
                                <a href="/" class="btn btn-outline-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="media">
                        <vote :model="question" name="question"></vote>
                        <div class="media-body">
                            <div v-html="bodyHtml"></div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="ml-auto">                       
                                        <a  v-if="authorize('modify',question)" @click.prevent = "edit" class="btn btn-sm btn-outline-info">Edit</a>
                                        <button v-if="authorize('deleteQuestion',question)" @click.prevent="destroy" class="btn btn-sm btn-outline-danger">Delete</button>                                                    
                                    </div>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <user-info :model="question" label="Asked"></user-info>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Vote from './Vote'
import UserInfo from './UserInfo'
import modification from '../mixins/modifications'

export default {
    props:['question'],
    components:{Vote,UserInfo},
     mixins:[modification],
    data(){
        return{
            title :this.question.title,
            body :this.question.body,
            bodyHtml :this.question.body_html,
            questionId : this.question.id,
            beforeEdit : {},

        }
    },
    computed:{
        invalid(){
            return this.body.length <10 || this.title.length < 10
        },
        endpoint () {
            // return `http://localhost:84/qa/questions/${this.questionId}`;
            return `/questions/${this.questionId}`;
        },
    },
    methods:{
        setEditCache(){
            this.beforeEdit = {
                body:this.body ,
                title : this.title
            }
        },
        restoreFormCache(){
            this.body = this.beforeEdit.body
            this.title = this.beforeEdit.title
        },
        payload(){
            return {
                body:this.body ,
                title : this.title               
            }
        },
        
        delete(){            
            axios.delete(this.endpoint)
            .then(res=>{
                this.$toast.success(res.data.message,'Success')    
            })
            setTimeout(() => {
                window.location.href = '/'
            }, 3000);
        },
    }
}
</script>