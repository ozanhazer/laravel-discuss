@extends('discuss::layout')

@section('content')
  <div class="mb-3">
    <a href="#" class="btn btn-primary" data-action="add-permission">Add Permission</a>
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
          <td>{{$user->discussDisplayName()}} #{{$user->id}}</td>
          <td>
            @foreach ($user->discussPermissions as $permission)
              <div class="mb-2">
                @lang('discuss::discuss.permission', [
                          'ability' => __('discuss::discuss.ability_' . $permission->ability),
                          'entity' => __('discuss::discuss.' . $permission->entity)
                        ])
                <div class="small text-muted">
                  Granted by {{$permission->grantor->discussDisplayName()}}
                  at {{$permission->created_at->format('d.m.Y H:i')}}
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

@section('after-scripts')
  <script>
    $('[data-action=add-permission]').on('click', function () {
      bootbox.prompt({
        title: 'E-mail of the existing user',
        inputType: 'email',
        callback: function (answer) {
          if (!answer) {
            return;
          }

          $.get('{{route('discuss.permissions.find-user')}}?user=' + answer)
            .then(response => location.href = response.uri)
            .catch(response => {
              if (response.status === 422) {
                let msgs = '';
                for (const fieldName in response.responseJSON.errors) {
                  const errorMessage = response.responseJSON.errors[fieldName].join('\n');
                  msgs += errorMessage + "\n";
                }
                bootbox.alert(msgs);
              } else {
                bootbox.alert('An unexpected error occured!');
              }
            });
        }
      });
    });
  </script>
@append
