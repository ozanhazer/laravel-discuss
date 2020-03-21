@extends('discussions::layout')

@section('content')
  <input type="search" name="q" placeholder="Forum içerisinde ara..." class="form-control">

  @for($i = 0; $i < 10; $i++)
    <div class="media mt-4">
      <img src="https://randomuser.me/api/portraits/men/32.jpg" class="mr-3 rounded-circle" style="width: 50px;"
           alt="...">
      <div class="media-body">
        <h5 class="mt-0"><a href="{{route('discussion.detail')}}">How can i automatically refresh an included view within a blade template?</a></h5>
        <a href="https://laracasts.com/@KangarooMusiQue" class="tw-uppercase tw-font-bold">KangarooMusiQue</a>
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
  @endfor

  <nav aria-label="Page navigation example" class="mt-5">
    <ul class="pagination">
      <li class="page-item"><a class="page-link" href="#">Previous</a></li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
  </nav>
@endsection
