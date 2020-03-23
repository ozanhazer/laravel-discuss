@extends('discussions::layout')

@section('content')
  <input type="search" name="q" placeholder="@lang('discussions::discussions.search_in_discussions_placeholder')" class="form-control">

  @foreach($threads as $thread)
    <div class="media mt-4">
      <img src="https://randomuser.me/api/portraits/men/32.jpg" class="mr-3 rounded-circle"
           style="width: 50px;" alt="...">
      <div class="media-body">
        <h5 class="mt-0"><a href="{{route('discussions.detail', $thread)}}">{{$thread->title}}</a></h5>
        <a href="https://laracasts.com/@KangarooMusiQue" class="tw-uppercase tw-font-bold">{{$thread->author->name}}</a>
        @if ($thread->last_post_at)
          @lang('discussions::discussions.replied_at', ['last_post_at' => $thread->last_post_at->diffForHumans()])
        @else
          @lang('discussions::discussions.posted_at', ['created_at' => $thread->created_at->diffForHumans()])
        @endif
      </div>
      <div style="width: 50px;" class="align-self-center">
        <i class="fa fa-eye"></i> {{$thread->view_count}}
      </div>
      <div style="width: 50px;" class="align-self-center">
        <i class="fa fa-comment"></i> {{$thread->post_count}}
      </div>
      <div style="width: 150px;" class="align-self-center">
        <a href="#" class="btn btn-outline-info rounded-pill btn-block">{{$thread->category->name}}</a>
      </div>
    </div>
  @endforeach

  <nav aria-label="Threads navigation" class="mt-5">
    {{$threads->links()}}
  </nav>
@endsection
