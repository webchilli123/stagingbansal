@can('update', $process)
<a href="{{ route('processes.edit', ['process' => $process]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
@endcan

@can('delete', $process)
    <form class="d-inline-block" action="{{ route('processes.destroy', ['process' => $process]) }}" method="POST"
    onsubmit="return confirm('Are You Sure?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
    </form>
@endcan