@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5>Staff Types</h5>
  <a href="{{ route('staff-types.create') }}" class="btn btn-primary">Staff Type</a>
</header>

<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="types">
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>
</section>

@endsection


@push('scripts')
<script>
  $(document).ready(() => {

        $('table#types').DataTable({
             processing: true,
             serverSide: true,
             order: [[0,'desc']],
             ajax: `{{ route('staff-types.index') }}`,
             columns: [
                { data: 'id', visible: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'action', 'orderable': false, searchable: false}
            ],
        });
    });   
</script>
@endpush