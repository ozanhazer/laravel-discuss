<ul class="nav nav-pills flex-column">
  <li class="nav-item">
    <a class="nav-link {{Route::is('discuss.index') ? 'active' : ''}}"
       href="{{route('discuss.index')}}">
      All Threads
    </a>
  </li>
  @auth
    <li class="nav-item">
      <a class="nav-link" href="{{route('discuss.my-participation')}}">My Participation</a>
    </li>
  @else
    <li class="nav-item">
      <a class="nav-link" href="#">My Participation</a>
    </li>
  @endauth
  <li class="nav-item">
    <a class="nav-link" href="#">Following</a>
  </li>

  <li class="dropdown-divider"></li>

  @foreach ($categories as $category)
    <li class="nav-item">
      <a class="nav-link {{URL::current() == route('discuss.category', $category) ? 'active' : ''}}"
         href="{{route('discuss.category', $category)}}">
        {{$category->name}}
      </a>
    </li>
  @endforeach
</ul>
