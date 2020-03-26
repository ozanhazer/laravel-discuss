{{-- User may disable profile page by setting profile_route to null --}}
@if (config('discuss.profile_route'))
  <a href="{{route('discuss.user', $user)}}">{{$slot->toHtml() ? $slot : $user->name}}</a>
@else
  <span>{{$slot->toHtml() ? $slot : $user->name}}</span>
@endif
