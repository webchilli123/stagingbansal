@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5>Items</h5>
  @can('create', App\Models\Item::class)
   <a href="{{ route('items.create') }}" class="btn btn-primary">Item</a>
  @endcan
</header>

<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="items">
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Company Name</th>
        <th>Tax Rate</th>
        <th>Quantity</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>
</section>

@endsection


@push('scripts')
<script>
  $(document).ready(() => {

        $('table#items').DataTable({
             processing: true,
             serverSide: true,
             order: [[0,'desc']],
             ajax: '{{ route('items.index') }}',
             columns: [
                { data: 'id', visible: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'company_name', name: 'company_name' },
                { data: 'tax_rate', name: 'tax_rate' },
                { data: 'quantity', name: 'quantity' },
                { data: 'action', 'orderable': false, searchable: false}
            ],
        });
    });   
</script>
@endpush