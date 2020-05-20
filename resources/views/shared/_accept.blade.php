@can('accept', $model)

    <a title="Mark this answer as best answer" 
        class="{{$model->status}} mt-2"
        onclick="event.preventDefault();document.getElementById('accept-answer-{{$model->id}}').submit();">
        <i class="fas fa-check fa-2x"></i>
    </a>
    <form action="{{route('accept.answer',$model->id)}}" 
          id="accept-answer-{{$model->id}}" 
          method="POST"
          style="display:none;">
          @csrf
    </form>
@else
    @if($model->isBest)
        <a title="This question owner accepted this answer as best answer" 
           class="{{$model->status}} mt-2">
           <i class="fas fa-check fa-2x"></i>
        </a>
    @endif

@endcan