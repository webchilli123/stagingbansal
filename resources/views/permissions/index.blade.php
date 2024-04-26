@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5>Permissions</h5>
  @can('create', App\Models\Permission::class)
  <a href="{{ route('permissions.create') }}" class="btn btn-primary">Permission</a>
  @endcan
</header>

<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="permissions">
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

        $('table#permissions').DataTable({
             processing: true,
             serverSide: true,
             order: [[0,'desc']],
             ajax: '{{ route('permissions.index') }}',
             columns: [
                { data: 'id', visible: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'action', 'orderable': false, searchable: false}
            ],
        });
    });   
</script>
@endpush