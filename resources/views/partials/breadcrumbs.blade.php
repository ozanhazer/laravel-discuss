@if (count($breadcrumbs))
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      @foreach($breadcrumbs as $crumb)
        <li class="breadcrumb-item {{$loop->last ? 'active' : ''}}">
          @if ($crumb->url and !$loop->last)
            <a href="{{$crumb->url}}">{{$crumb->title}}</a>
          @else
            {{$crumb->title}}
          @endif
        </li>
      @endforeach
    </ol>
  </nav>
@endif
