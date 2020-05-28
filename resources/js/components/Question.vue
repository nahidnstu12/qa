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
                                <a href="/qa" class="btn btn-outline-secondary">Back</a>
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

export default {
    props:['question'],
    components:{Vote,UserInfo},
    data(){
        return{
            title :this.question.title,
            body :this.question.body,
            bodyHtml :this.question.body_html,
            editing : false,
            questionId : this.question.id,
            beforeEdit : {},

        }
    },
    computed:{
        invalid(){
            return this.body.length <10 || this.title.length < 10
        },
        endpoint () {
            return `http://localhost:84/qa/questions/${this.questionId}`;
            // return `/questions/${this.questionId}`;
        },
    },
    methods:{
         edit(){
            this.beforeEdit = {
                body:this.body ,
                title : this.title
            }
            this.editing = true
        },
        cancel(){
            this.body = this.beforeEdit.body
            this.title = this.beforeEdit.title
            this.editing = false
        },
        update(){
            axios.put(this.endpoint, {
                body:this.body ,
                title : this.title               
            })
            .then(res =>{
               this.editing = false
               this.bodyHtml = res.data.body_html
               this.$toast.success(res.data.message,'Success',{timeout:3000})
            })
            .catch(err=>{
                this.$toast.error(err.response.data.message,'Error',{timeout:3000})
            })
        },
        
        destroy(){
            this.$toast.question('Are you sure about that?','Confirm',{
                timeout: 10000,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 999,
                title: 'Hey',
                position: 'center',
                buttons: [
                    ['<button><b>YES</b></button>',  (instance, toast)=> {
                    axios.delete(this.endpoint)
                    .then(res=>{
                        this.$toast.success(res.data.message,'Success')    
                    })
                   setTimeout(() => {
                       window.location.href = '/qa'
                   }, 3000);
                    
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            
                    }, true],
                    ['<button>NO</button>', function (instance, toast) {
            
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            
                    }],
                ],
               
            });
           
        },

    }
}
</script>