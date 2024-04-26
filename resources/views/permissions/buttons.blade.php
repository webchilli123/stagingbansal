@can('update', $permission)
<a href="{{ route('permissions.edit', ['permission' => $permission]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
@endcan
@can('delete', $permission)
<form class="d-inline-block" action="{{ route('permissions.destroy', ['permission' => $permission]) }}" method="POST"
 onsubmit="return confirm('Are You Sure?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
</form>
@endcan