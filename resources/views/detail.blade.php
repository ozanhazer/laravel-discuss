@extends('discuss::layout')

@section('content')
  <h1 class="thread-title">{{$thread->title}}</h1>

  <div class="small text-muted overflow-auto">
    <div class="float-left">
      @component('discuss::components.author-link', ['user' => $thread->author])@endcomponent
      •
      {{$thread->created_at->diffForHumans()}}
    </div>
    <div class="float-right">
      <i class="fa fa-eye"></i> {{$thread->view_count}}
      <i class="fa fa-comment"></i> {{$thread->post_count}}

      <a href="{{route('discuss.category', $thread->category)}}" class="btn btn-outline-info rounded-pill">
        {{$thread->category->name}}
      </a>
    </div>
  </div>

  <div class="thread-body">
    {{$thread->body}}
  </div>

  @canany(['update', 'delete', 'change-category'], $thread)
    <div class="bg-light p-1 d-inline-block rounded mt-3">
      @can('update', $thread)
        <a href="#" class="btn btn-light btn-sm" data-toggle="modal" data-target="#thread-edit-form-modal">Edit Thread</a>
      @endcan

      @can('change-category', $thread)
        <a href="#" class="btn btn-light btn-sm">Change Category</a>
      @endcan

      @can('make-sticky', $thread)
        @if ($thread->sticky)
          <a href="#" class="btn btn-light btn-sm">Make Unsticky</a>
        @else
          <a href="#" class="btn btn-light btn-sm">Make Sticky</a>
        @endif
      @endcan

      @can('delete', $thread)
        <a href="#" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>
      @endcan
    </div>
  @endcanany

  <hr>

  <div>

    @if ($posts->count() > 0)
      @foreach($posts as $post)
        <div class="media mt-4">
          <img src="https://randomuser.me/api/portraits/men/32.jpg" class="mr-3 rounded-circle"
               style="width: 50px;" alt="...">
          <div class="media-body">
            <h5 class="mt-0">{{$post->title}}</h5>

            @component('discuss::components.author-link', ['user' => $post->author])@endcomponent

            @lang('discuss::discuss.posted_at', ['created_at' => $post->created_at->diffForHumans()])

            <div class="post-body">
              {{$thread->body}}
            </div>


            @canany(['update', 'delete'], $post)
              <div class="bg-light p-1 d-inline-block rounded mt-3">
                @can('update', $post)
                  <a href="#" class="btn btn-light btn-sm">Edit Post</a>
                @endcan

                @can('delete', $post)
                  <a href="#" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>
                @endcan
              </div>
            @endcanany


          </div>
        </div>
      @endforeach

      {{$posts->links()}}
    @endif
  </div>
@stop

@section('buttons-area')
  <a href="#" class="btn btn-primary w-100 rounded-pill mb-2" data-toggle="modal" data-target="#post-form-modal">
    Reply
  </a>

  <a href="#" class="btn btn-light w-100 rounded-pill">
    Follow
  </a>
@stop

@section('after-scripts')
  @include('discuss::partials.post-form')
  @include('discuss::partials.thread-edit-form')
@stop
