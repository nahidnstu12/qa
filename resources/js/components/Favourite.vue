<template>
    <div>
        <a title="Click to mark as favorite question (Click again to undo)"
        :class="classes" @click.prevent = "toggle">
        <i class="fas fa-star fa-2x"></i>           
        </a> 
        <span class="favourites-count">{{ count }}</span>
    </div>

</template>
<script>
export default {
    props : ['question'],

    data(){
        return{
            isFavourited : this.question.is_favourited,
            count : this.question.favourites_count,
            id : this.question.id,
        }
    },
    
    computed: {
        classes(){
            return ['favourite','mt-2',
            !this.isSigned ? 'off' : (this.isFavourited ? 'favourited' : '')
            ]
        },
        endpoint () {
            // return `http://localhost:84/qa/questions/${this.id}/favourites`;
             return `/questions/${this.id}/favourites`;
        },
       
    },

    methods:{
        toggle (){
            if(!this.isSigned){
                this.$toast.warning('Please login to favourite this question','Warning',{
                    timeout : 3000,
                    position : 'topRight'
                  
                })
                return;
            }
            this.isFavourited ? this.destroy() : this.create()
        },
        destroy(){
            axios.delete(this.endpoint)
            .then(res =>{
                this.count--
                this.isFavourited = false
            })

        },
        create(){
            axios.post(this.endpoint)
            .then(res=>{
                this.count++
                this.isFavourited = true
            })

        },

    }
}
</script>