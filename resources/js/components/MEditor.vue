<template>
<div class="card">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link active" :href="tabIndex('write','#')" data-toggle="tab">Write</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" :href="tabIndex('preview','#')" data-toggle="tab">Preview</a>
      </li>
    </ul>
  </div>
  <div class="card-body tab-content">
    <div class="tab-pane active" :id="tabIndex('write')">
        <slot></slot>
    </div>
    <div class="tab-pane" :id="tabIndex('preview')" v-html="preview"></div>
  </div>
</div>
    
</template>
<script>
import MarkDown from 'markdown-it'
import prism from 'markdown-it-prism'
import autosize from 'autosize'

const md = new MarkDown()
md.use(prism)

export default {
    props:['body','name'],
    components:{},
    data(){
        return{

        }
    },
    mounted(){
        // autosize(this.$el.querySelector('textarea'))
    },
    updated(){
        autosize(this.$el.querySelector('textarea'))
    },
    computed:{
        preview(){
            return md.render(this.body)
        }
    },
    methods:{
        tabIndex(tab,hash=''){
            return `${hash}${tab}${this.name}`
        }
    }
}
</script>