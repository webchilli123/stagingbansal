@can('update', $user)
<a href="{{ route('users.edit', ['user' => $user]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
@endcan
@can('delete', $user)
<form class="d-inline-block" action="{{ route('users.destroy', ['user' => $user]) }}" method="POST"
 onsubmit="return confirm('Are You Sure?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
</form>
@endcan