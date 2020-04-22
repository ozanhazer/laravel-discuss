@extends('discuss::layout')

@section('content')

  <form action="{{route('discuss.permissions.save')}}" method="post">
    @csrf
    <input type="hidden" name="user_id" value="{{$user->getKey()}}">

    <h1>
      {{$user->discussDisplayName()}} #{{$user->id}}
    </h1>
    <div class="small">
      {{$user->email}}
    </div>

    @if($user->isDiscussSuperAdmin())
      <div class="alert alert-success d-inline-block p-2 mt-2">
        Super Admin!
      </div>
    @endif

    <ul class="list-unstyled mt-4">
      @foreach ($permissions as $permission)
        <li class="mb-1">
          <label>
            <input type="checkbox"
                   name="perms[{{$permission[0]}}][]"
                   value="{{$permission[1]}}"
              {{$user->isDiscussSuperAdmin() ? 'disabled checked' : ''}}
              {{(isset($userPermissions[$permission[0]]) and in_array($permission[1], $userPermissions[$permission[0]])) ? 'checked' : ''}}>

            @lang('discuss::discuss.permission', [
                'ability' => __('discuss::discuss.ability_' . $permission[1]),
                'entity' => __('discuss::discuss.' . $permission[0])
              ])
          </label>
        </li>
      @endforeach
      <li><hr></li>
      <li class="mb-1">
        <label>
          <input type="checkbox" name="" disabled {{$user->isDiscussSuperAdmin() ? 'checked' : ''}}>
          Edit Permissions
          <span class="small text-muted d-block mt-2 ml-3">Only super admins can edit permissions.</span>
        </label>
      </li>
    </ul>

    <button type="submit" class="btn btn-primary">Save Permissions</button>

  </form>

@stop
