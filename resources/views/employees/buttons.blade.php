@can('update', $employee)
<a href="{{ route('employees.edit', ['employee' => $employee]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
@endcan

@can('delete', $employee)
    <form class="d-inline-block" action="{{ route('employees.destroy', ['employee' => $employee]) }}" method="POST"
    onsubmit="return confirm('Are You Sure?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
    </form>
@endcan