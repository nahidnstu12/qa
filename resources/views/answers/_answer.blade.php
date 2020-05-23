<answer :answer="{{$answer}}" inline-template>
    <div class="media post">
        
        @include('shared._vote',['model'=>$answer])
         <div class="media-body">
             <form v-if="editing" @submit.prevent="update">                
                 {{-- @method('PATCH')
                 @csrf --}}
                <div class="form-group">
                    <textarea rows="10" class="form-control" v-model="body"  required></textarea>
                </div>
                <button type="submit" class="btn btn-info" :disabled="invalid">Update</button>
                <button @click.prevent="cancel" class="btn btn-outline-secondary" type="button">Cancel</button>
             </form>
             <div v-else>
             <div v-html="bodyHtml"></div>
             <div class="row">
                 <div class="col-4">
                     <div class="ml-auto">
                         @can('update', $answer)
                            <a  @click.prevent = "edit"
                             class="btn btn-sm btn-outline-info">Edit</a>
                         @endcan
    
                         @can('delete', $answer)                           
                         <button @click.prevent="destroy" class="btn btn-sm btn-outline-danger">Delete</button>                            
                         @endcan    
                     </div>
                 </div>
                 <div class="col-4"></div>
                 <div class="col-4">
                    <user-info :model="{{$answer}}" label="Answered"></user-info>
                 </div>
             </div>
             </div>
         </div>
     </div>
</answer>