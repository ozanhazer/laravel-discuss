@extends('discuss::layout')

@section('content')
  <div class="mb-3">
    <a href="" class="btn btn-primary">Add Permission</a>
  </div>

  @if ($permissions->count() > 0)
    <table class="table table-bordered">
      <thead>
      <tr>
        <th>User</th>
        <th>Permission</th>
        <th>Granted by</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      @foreach($permissions as $permission)
        <tr>
          <td>{{$permission->user->name}} ({{$permission->user->id}})</td>
          <td>{{$permission->ability}} {{$permission->entity}}</td>
          <td>
            {{$permission->grantor->name}} at {{$permission->created_at->format('d.m.Y H:i')}}
          </td>
          <td><a href="">Delete</a></td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @else
    <p class="my-3">
      <em>@lang('No permissions found...')</em>
    </p>
  @endif

  <nav class="mt-5">
    {{$permissions->links()}}
  </nav>
@endsection
