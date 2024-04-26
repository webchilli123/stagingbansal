@can('update', $category)
<a href="{{ route('categories.edit', ['category' => $category]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
@endcan

@can('delete', $category)
<form class="d-inline-block" action="{{ route('categories.destroy', ['category' => $category]) }}" method="POST"
 onsubmit="return confirm('Are You Sure?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
</form>
@endcan