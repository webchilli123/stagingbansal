@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5>Employee Attendances</h5>
  @can('create', App\Models\Category::class)
  <a href="{{ route('employee-attendances.create') }}" class="btn btn-primary">Attendance</a>
  @endcan
</header>

<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="attendances">
    <thead>
      <tr>
        <th>Id</th>
        <th>Employee Name</th>
        <th>Attendance Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>
</section>

@endsection

@push('scripts')
<script>
  $(document).ready(() => {

        $('table#attendances').DataTable({
             processing: true,
             serverSide: true,
             order: [[0,'desc']],
             ajax: `{{ route('employee-attendances.index') }}`,
             columns: [
                { data: 'id', visible: false, searchable: false },
                { data: 'employee.name', name: 'employee.name' },
                { data: 'attendance_date', name: 'attendance_date' },
                { data: 'start_time', name: 'start_time' },
                { data: 'end_time', name: 'end_time' },
                { data: 'action', 'orderable': false, searchable: false}
            ],
        });
    });   
</script>
@endpush