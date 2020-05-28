@extends('layouts.app')

@section('content')
<div class="container">  
    <questions-page :question="{{$question}}"></questions-page>
</div>
@endsection