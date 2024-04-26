@can('update', $city)
<a href="{{ route('cities.edit', ['city' => $city]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
@endcan
@can('delete', $city)
<form class="d-inline-block" action="{{ route('cities.destroy', ['city' => $city]) }}" method="POST"
 onsubmit="return confirm('Are You Sure?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
</form>
@endcan