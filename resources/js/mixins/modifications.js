import Vote from '../components/Vote'
import UserInfo from '../components/UserInfo'
import MEditor from '../components/MEditor'
import highlight from './highlight'
export default{
    data(){
        return {
            editing : false
        }
    },
    components:{Vote,UserInfo,MEditor},
    mixins:[highlight],
    methods:{
        edit(){
            this.setEditCache()
            this.editing = true
        },
        cancel(){
            this.restoreFormCache()
            this.editing = false
        },
        setEditCache(){},
        restoreFormCache(){},
        update(){
            axios.put(this.endpoint, this.payload())
            .then(res =>{
               this.editing = false
               this.bodyHtml = res.data.body_html
               this.$toast.success(res.data.message,'Success',{timeout:3000})
            })
            .then(()=>this.highlight())
            .catch(err=>{
                this.$toast.error(err.response.data.message,'Error',{timeout:3000})
            })
        },
        payload(){},
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
                    this.delete()
                    
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            
                    }, true],
                    ['<button>NO</button>', function (instance, toast) {
            
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            
                    }],
                ],
               
            });
           
        },
        delete(){}
    }
}