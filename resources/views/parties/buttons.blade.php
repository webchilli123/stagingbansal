<a href="{{ route('ledger.index') }}?party_id={{ $party->id }}" class="btn btn-sm text-primary"><i class="fa fa-book"></i></a>
@can('update', $party)
<a href="{{ route('parties.edit', ['party' => $party]) }}" class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
@endcan
@can('delete', $party)
<form class="d-inline-block" action="{{ route('parties.destroy', ['party' => $party]) }}" method="POST"
 onsubmit="return confirm('Are You Sure?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
</form>
@endcan