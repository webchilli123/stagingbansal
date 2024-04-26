
<a href="{{ route('employee-attendances.edit', ['attendance' => $attendance]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>

<form class="d-inline-block" action="{{ route('employee-attendances.destroy', ['attendance' => $attendance]) }}" method="POST"
 onsubmit="return confirm('Are You Sure?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
</form>