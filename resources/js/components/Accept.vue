<template>
    <div>
        <a v-if="canAccept" title="Mark this answer as best answer"  @click.prevent = "create"
            :class="classes">
            <i class="fas fa-check fa-2x"></i>
        </a>
        <a v-if="accepted" title="This question owner accepted this answer as best answer" 
        :class="classes">
        <i class="fas fa-check fa-2x"></i>
        </a>
    </div>
</template>
<script>
export default {
    props : ['answer'],

    data(){
        return{
            isBest : this.answer.is_best,
            id: this.answer.id,
        }
    },
    computed:{
        classes(){
            return ['mt-2',this.isBest ? 'vote-accepted':'']
        },
        canAccept(){
            return this.authorize('accept',this.answer)
        },
        accepted(){
            return !this.canAccept && this.isBest
        },
         endpoint () {
            return `http://localhost:84/qa/answers/${this.id}/accept`;
            // return `/answers/${this.id}/accept`;
        },

    },
    methods:{
        create(){
            axios.post(this.endpoint)
            .then(res=>{
                this.$toast.success (res.data.message,'success',{
                    timeout : 3000,
                    position : 'topRight'
                })
            })
            this.isBest = true
        }

    }
}
</script>