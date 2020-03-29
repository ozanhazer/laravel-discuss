<!doctype html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Discuss</title>

  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="//use.fontawesome.com">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

<header>
  <div class="container">
    <h1>Forum</h1>
  </div>
</header>

<div class="container">
  @include('discuss::partials.breadcrumbs')

  <div class="row">
    <div class="col" style="max-width: 200px;">
      <div class="buttons-area mb-4">
        @section('buttons-area')
          <a href="#" class="btn btn-primary w-100 rounded-pill" data-toggle="modal" data-target="#thread-form-modal">
            New Discussion
          </a>
        @show
      </div>
      @include('discuss::partials.menu')
    </div>
    <div class="col">
      @yield('content')
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>

@include('discuss::partials.thread-form')

</body>
</html>
