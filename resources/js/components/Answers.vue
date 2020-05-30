<template>
    <div>
        <div class="row mt-4" v-cloak v-if="count">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h2>{{title}}</h2>
                        </div>
                        <answer @deleted="remove(index)" v-for="(answer,index) in answers" :answer="answer" :key="answer.id"></answer>
                        <div class="text-center mt-3" v-if="nextURL">
                            <button @click.prevent = "fetch(nextURL)" class="btn btn-outline-secondary">Load More Answers</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <new-answer @created = "add" :question-id = "question.id"></new-answer>
    </div>
</template>
<script>
import Answer from './Answer.vue'
import NewAnswer from './NewAnswer.vue'
export default {
    props: ['question'],
    components:{Answer,NewAnswer},
    data(){
        return{
            questionId : this.question.id,
            count : this.question.answers_count,
            answers :[],
            nextURL : null,
        }
    },
    created(){
        // this.fetch(`/questions/${this.questionId}/answers`)
        this.fetch(this.endpoint)

    },
    computed:{
        title(){
            return `${this.count} ${this.count>1 ? 'Answers':'Answer'}`
        },
        endpoint () {
            // return `http://localhost:84/qa/questions/${this.questionId}/answers`;
            return `/questions/${this.questionId}/answers`;
        },

    },
    methods:{
        add(answer){
            this.answers.push(answer)
            this.count++
        },
        remove(index){
            this.answers.splice(index,1)
            this.count--
        },
        fetch(endpoint){
            axios.get(endpoint)
            .then(({data}) =>{
                this.answers.push(... data.data)
                this.nextURL = data.next_page_url
                // console.log(data)
            })
        }

    }
   
}
</script>