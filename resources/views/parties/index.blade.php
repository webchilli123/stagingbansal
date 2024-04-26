@extends('layouts.dashboard')
@section('content')

<header class="row no-gutters mb-4">
  <div class="col-lg-8 mb-2">
    <h5>Parties</h5>
  </div>
  <div class="col-lg-3 p-0 mb-2">
    <div class="input-group">
      <span class="input-group-text bg-white">
        <i class="fa fa-search text-primary"></i>
      </span>
      <select id="party_type" class="form-control">
        <option value="" selected>Choose...</option>
        @foreach ($types as $key => $type)
        <option value="{{ $key }}">{{ $type }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-lg-1">
    @can('create', App\Models\Party::class)
    <a href="{{ route('parties.create') }}" class="btn btn-primary">Party</a>
    @endcan
  </div>
</header>

<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="parties">
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Category</th>
        <th>Type</th>
        <th>City</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>
</section>

@endsection


@push('scripts')
<script>
  $(document).ready(() => {

      $('select').selectize();

      let table = $('table#parties').DataTable({
            processing: true,
            serverSide: true,
            order: [[0,'desc']],
            ajax: '{{ route('parties.index') }}',
            columns: [
              { data: 'id', visible: false, searchable: false },
              { data: 'name', name: 'name' },
              { data: 'category.name', name: 'category.name' },
              { data: 'type', name: 'type', visible: false },
              { data: 'city.name', name: 'city.name'},
              { data: 'action', 'orderable': false, searchable: false}
          ],
      });
      
      $('#party_type').change(function(){
          table.column(3).search($(this).val()).draw();
      });

    });   
</script>
@endpush