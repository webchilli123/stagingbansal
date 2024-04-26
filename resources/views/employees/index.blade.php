@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5>Employees</h5>
  <a href="{{ route('employees.create') }}" class="btn btn-primary">Employee</a>
</header>

<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="employees">
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Designation</th>
        <th>Staff Type</th>
        <th>Rate</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>
</section>

@endsection


@push('scripts')
<script>
  $(document).ready(() => {

        $('table#employees').DataTable({
             processing: true,
             serverSide: true,
             order: [[0,'desc']],
             ajax: '{{ route("employees.index") }}',
             columns: [
                { data: 'id', visible: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'designation.name', name: 'designation.name' },
                { data: 'staff_type.name', name: 'staff_type.name' },
                { data: 'rate', name: 'rate' },
                { data: 'action', 'orderable': false, searchable: false}
            ],
        });
    });   
</script>
@endpush