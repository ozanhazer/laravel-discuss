@extends('discuss::layout')

@section('content')
  <div class="mb-3">
    <a href="" class="btn btn-primary">Add Permission</a>
  </div>

  <div class="alert alert-warning">
    Please note that super admins are not shown...
  </div>

  @if ($usersWithPermissions->count() > 0)
    <table class="table table-bordered">
      <thead>
      <tr>
        <th>User</th>
        <th>Permissions</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      @foreach($usersWithPermissions as $user)
        <tr>
          <td>{{$user->name}} ({{$user->id}})</td>
          <td>
            @foreach ($user->discussPermissions as $permission)
            <div class="mb-2">
              @lang('discuss::discuss.permission', [
                        'ability' => __('discuss::discuss.ability_' . $permission->ability),
                        'entity' => __('discuss::discuss.' . $permission->entity)
                      ])
              <div class="small text-muted">
                Granted by {{$permission->grantor->name}} at {{$permission->created_at->format('d.m.Y H:i')}}
              </div>
            </div>
            @endforeach
          </td>
          <td><a href="{{route('discuss.permissions.edit', $user)}}">Edit</a></td>
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
    {{$usersWithPermissions->links()}}
  </nav>
@endsection
