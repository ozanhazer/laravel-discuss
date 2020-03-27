<ul class="nav flex-column">
  <li class="nav-item">
    <a class="nav-link active" href="#">All Threads</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">My Participation</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Following</a>
  </li>

  <li class="dropdown-divider"></li>

  @foreach ($categories as $category)
  <li class="nav-item">
    <a class="nav-link" href="{{route('discuss.category', $category)}}">{{$category->name}}</a>
  </li>
  @endforeach
</ul>
