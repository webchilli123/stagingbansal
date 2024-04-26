<a href="{{ route('staff-types.edit', ['type' => $type]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
<form class="d-inline-block" action="{{ route('staff-types.destroy', ['type' => $type]) }}" method="POST"
 onsubmit="return confirm('Are You Sure?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
</form>