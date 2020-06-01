<template>
     <div class="media post">        
        <vote :model="answer" name="answer"></vote>
         <div class="media-body">
             <form v-show="authorize('modify',answer) && editing" @submit.prevent="update">                               
                <div class="form-group">
                    <m-editor :body="body" :name="uniqueName">
                        <textarea rows="10" class="form-control" v-model="body"  required></textarea>
                    </m-editor>
                </div>
                <button type="submit" class="btn btn-info" :disabled="invalid">Update</button>
                <button @click.prevent="cancel" class="btn btn-outline-secondary" type="button">Cancel</button>
             </form>
             <div v-show="!editing">
             <div v-html="bodyHtml" ref="bodyHtml" :id = "uniqueName"></div>
             <div class="row">
                 <div class="col-4">
                     <div class="ml-auto">                       
                        <a  v-if="authorize('modify',answer)" @click.prevent = "edit" class="btn btn-sm btn-outline-info">Edit</a>
                        <button v-if="authorize('modify',answer)" @click.prevent="destroy" class="btn btn-sm btn-outline-danger">Delete</button>                                                    
                     </div>
                 </div>
                 <div class="col-4"></div>
                 <div class="col-4">
                    <user-info :model="answer" label="Answered"></user-info>
                 </div>
             </div>
             </div>
         </div>
     </div>
</template>

<script>

import modification from '../mixins/modifications'
export default {
    props: ['answer'],
    mixins:[modification],
    data(){
        return{
            body : this.answer.body,
            bodyHtml : this.answer.body_html,
            id : this.answer.id,
            questionId : this.answer.question_id,
            beforeEdit : null
        }
    },
    methods:{
        setEditCache(){
            this.beforeEdit = this.body
        },
        restoreFormCache(){
            this.body = this.beforeEdit
            this.editing = false
        },
       payload(){
           return {
                body: this.body
            }
       },
        
       delete(){
           axios.delete(this.endpoint)
            .then(res=>{
                this.$toast.success(res.data.message,'Success')
                this.$emit('deleted')
            })
       },


    },

    computed:{
        invalid(){
            return this.body.length <10
        },
         endpoint () {
            // return `http://localhost:84/qa/questions/${this.questionId}/answers/${this.id}`;
            return `/questions/${this.questionId}/answers/${this.id}`;
        },
        uniqueName(){
            return `answer-${this.id}`
        }
    }
}
</script>