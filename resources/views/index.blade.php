@extends('discussions::layout')

@section('content')
  <input type="search" name="q" placeholder="Forum içerisinde ara..." class="form-control">

  @foreach($threads as $thread)
    <div class="media mt-4">
      <img src="https://randomuser.me/api/portraits/men/32.jpg" class="mr-3 rounded-circle" style="width: 50px;"
           alt="...">
      <div class="media-body">
        <h5 class="mt-0"><a href="{{route('discussions.detail')}}">{{$thread->title}}</a></h5>
        <a href="https://laracasts.com/@KangarooMusiQue" class="tw-uppercase tw-font-bold">{{$thread->author->name}}</a>
        replied
        <span>
        <a href="?">2 minutes ago</a></span>
      </div>
      <div style="width: 50px;" class="align-self-center">
        <i class="fa fa-eye"></i> 43
      </div>
      <div style="width: 50px;" class="align-self-center">
        <i class="fa fa-comment"></i> 43
      </div>
      <div style="width: 150px;" class="align-self-center">
        <a href="#" class="btn btn-outline-info rounded-pill btn-block">Site Hakkında</a>
      </div>
    </div>
  @endforeach

  <nav aria-label="Threads navigation" class="mt-5">
    {{$threads->links()}}
  </nav>
@endsection
