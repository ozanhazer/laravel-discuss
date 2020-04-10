@extends('discuss::layout')

@section('content')
{{--  <input type="search" name="q" placeholder="@lang('discuss::discuss.search_in_discussions_placeholder')"--}}
{{--         class="form-control">--}}

  @if ($threads->count() > 0)
    @foreach($threads as $thread)
      <div class="media mt-4 {{$thread->sticky ? 'sticky-thread' : ''}}">
        <img src="{{$thread->author->discussAvatar()}}" class="mr-3 rounded-circle"
             style="width: 50px;" alt="Profile photo of {{$thread->author->discussDisplayName()}}">
        <div class="media-body">
          <h5 class="mt-0"><a href="{{$thread->url()}}">{{$thread->title}}</a></h5>

          @component('discuss::components.author-link', ['user' => $thread->author])@endcomponent

          @if ($thread->last_post_at)
            @lang('discuss::discuss.replied_at', ['last_post_at' => $thread->last_post_at->diffForHumans()])
          @else
            @lang('discuss::discuss.posted_at', ['created_at' => $thread->created_at->diffForHumans()])
          @endif

        </div>
        <div style="width: 50px;" class="align-self-center">
          <i class="fa fa-eye"></i> {{$thread->view_count}}
        </div>
        <div style="width: 50px;" class="align-self-center">
          <i class="fa fa-comment"></i> {{$thread->post_count}}
        </div>
        <div style="width: 150px;" class="align-self-center">
          <a href="{{route('discuss.category', $thread->category)}}" class="btn btn-outline-info rounded-pill btn-block">{{$thread->category->name}}</a>
        </div>
      </div>
    @endforeach
  @else
    <p class="my-3">
      <em>@lang('No posts found...')</em>
    </p>
  @endif

  <nav aria-label="Threads navigation" class="mt-5">
    {{$threads->links()}}
  </nav>
@endsection
