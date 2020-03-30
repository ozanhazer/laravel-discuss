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
        <a href="#" class="btn btn-light btn-sm" data-toggle="modal" data-target="#thread-edit-form-modal">
          Edit Thread
        </a>
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
        <a href="#" class="btn btn-danger btn-sm"
           data-action="delete-post"
           data-url="{{route('discuss.thread.delete', $thread)}}">
        <i class="fa fa-trash-o"></i>
        </a>
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
              {{$post->body}}
            </div>


            @canany(['update', 'delete'], $post)
              <div class="bg-light p-1 d-inline-block rounded mt-3">
                @can('update', $post)
                  <a href="#" class="btn btn-light btn-sm"
                     data-toggle="edit-reply-modal"
                     data-populate-url="{{route('discuss.post.populate', $post)}}"
                     data-action="{{route('discuss.post.update', $post)}}"
                     data-title="Edit Reply">Edit Reply</a>
                @endcan

                @can('delete', $post)
                  <a href="#" class="btn btn-danger btn-sm"
                     data-action="delete-post"
                     data-url="{{route('discuss.post.delete', $post)}}">
                    <i class="fa fa-trash-o"></i>
                  </a>
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
  <a href="#" class="btn btn-primary w-100 rounded-pill mb-2"
     data-toggle="reply-modal"
     data-action="{{route('discuss.post.create', $thread)}}"
     data-title="Reply">
    Reply
  </a>

  <a href="#" class="btn btn-light w-100 rounded-pill">
    Follow
  </a>
@stop

@section('after-scripts')
  @include('discuss::partials.post-form')
  @include('discuss::partials.thread-edit-form')

  <script>
    ($ => {
      const $modal = $('#post-form-modal');
      $('[data-toggle=reply-modal]').on('click', function (e) {
        e.preventDefault();

        $modal.find('.modal-title').text($(this).data('title'));
        $modal.find('form').attr('action', $(this).data('action'));
        $modal.find('[name=body]').val('');
        $modal.modal('show');
      });

      $('[data-toggle=edit-reply-modal]').on('click', function (e) {
        e.preventDefault();

        $modal.find('.modal-title').text($(this).data('title'));
        $modal.find('form').attr('action', $(this).data('action'));
        $.get($(this).data('populate-url')).then(response => $modal.find('[name=body]').val(response.body));
        $modal.modal('show');
      });

      $('[data-action=delete-post]').on('click', function () {
        const $btn = $(this);
        bootbox.confirm('Are you sure that you want to delete this post?', answer => {
          if (!answer) {
            return;
          }
          $.post($btn.data('url')).then(response => location.href = response.url)
        });
      })
    })(jQuery);
  </script>
@stop
