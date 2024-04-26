@can('update', $item)
<a href="{{ route('items.edit', ['item' => $item]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
@endcan

@can('delete', $item)
    <form class="d-inline-block" action="{{ route('items.destroy', ['item' => $item]) }}" method="POST"
    onsubmit="return confirm('Are You Sure?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
    </form>
@endcan