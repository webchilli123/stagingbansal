@can('update', $designation)
<a href="{{ route('designations.edit', ['designation' => $designation]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
@endcan

@can('delete', $designation)
    <form class="d-inline-block" action="{{ route('designations.destroy', ['designation' => $designation]) }}" method="POST"
    onsubmit="return confirm('Are You Sure?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
    </form>
@endcan