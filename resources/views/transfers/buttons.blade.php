<a href="{{ route('transfers.show', ['transfer' => $transfer]) }}" class="btn btn-sm text-primary"><i class="fa fa-eye"></i></a>

@can('delete', $transfer)
    <form class="d-inline-block" action="{{ route('transfers.destroy', ['transfer' => $transfer]) }}" method="POST"
    onsubmit="return confirm('Are You Sure?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
    </form>
@endcan