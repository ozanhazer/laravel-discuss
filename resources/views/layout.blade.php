<!doctype html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Discuss</title>

  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="//use.fontawesome.com">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <style>
    .sticky-thread h5:after {
      content: "📍";
      font-size: .6em;
      vertical-align: super;
    }
  </style>

</head>
<body>

<header>
  <div class="container overflow-auto py-3">
    <h1 class="float-left">Forum</h1>

    <div class="float-right pt-3">
      @auth
        @component('discuss::components.author-link', ['user' => auth()->user()])
          <i class="fa fa-user"></i> {{auth()->user()->discussDisplayName()}}
        @endcomponent
      @else
        <div class="text-muted small">Not logged in</div>
      @endauth
    </div>
  </div>
</header>

<div class="container">
  @include('discuss::partials.breadcrumbs')

  <div class="row">
    <div class="col" style="max-width: 200px;">
      <div class="buttons-area mb-4">
        @section('buttons-area')
          <a href="#" class="btn btn-primary w-100 rounded-pill"
             {!! auth()->check() ? 'data-toggle="modal"' : 'data-action="not-logged-in"' !!}
             data-target="#thread-create-form-modal">
            New Discussion
          </a>
        @show
      </div>
      @include('discuss::partials.menu')

      @can('edit-permissions')
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link" href="{{route('discuss.permissions.list')}}">Edit Permissions</a>
          </li>
        </ul>
      @endcan

    </div>
    <div class="col">
      @yield('content')
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
<script>
  // FIXME
  ($ => {
    $.fn.handleForm = function (callbackFn) {
      const $form = this;
      const form = $form.get(0);

      if (form.nodeName.toLowerCase() !== 'form') {
        throw "Invalid node type. handleForm should be used on form elements only.";
      }

      const setLoading = start => {
        if (start) {
          $form.find('[type=submit]').prop('disabled', true);
          $form.find('[type=submit]').append('<i class="fa fa-refresh fa-spin ml-2"></i>');
        } else {
          $form.find('[type=submit]').prop('disabled', false);
          $form.find('[type=submit]').find('.fa-refresh').remove();
        }
      };

      const resetValidation = () => {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').remove();
      };

      const addValidationMessages = errors => {
        for (const fieldName in errors) {
          const errorMessage = errors[fieldName].join('\n');
          $form.find('[name=' + fieldName + ']')
            .addClass('is-invalid')
            .after('<div class="invalid-feedback">' + errorMessage + '</div>')
        }
      };

      form.addEventListener('submit', e => {
        e.preventDefault();
        setLoading(true);
        resetValidation();

        const xhr = $.post($form.attr('action'), $form.serialize())
          .catch(response => response.status === 422 ?
            addValidationMessages(response.responseJSON.errors) :
            bootbox.alert('An unexpected error has occured. Please try again later...'))
          .always(() => setLoading(false));

        callbackFn(xhr);
      });
    };

    // CSRF Setup
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    @guest
    // Not logged in notification
    $('[data-action=not-logged-in]').click(() => bootbox.alert('You should be logged in to do that :)'));
    @endguest
  })(jQuery);
</script>

@include('discuss::partials.thread-create-form')
@yield('after-scripts')

</body>
</html>
