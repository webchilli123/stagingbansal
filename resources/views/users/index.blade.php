@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5>Users</h5>
  @can('create', App\Models\User::class)
   <a href="{{ route('users.create') }}" class="btn btn-primary">Users</a>
  @endcan
</header>

<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="users">
    <thead>
      <tr>
        <th>Id</th>
        <th>Username</th>
        <th>Role</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>
</section>

@endsection


@push('scripts')
<script>
  $(document).ready(() => {

        $('table#users').DataTable({
             processing: true,
             serverSide: true,
             order: [[0,'desc']],
             ajax: '{{ route('users.index') }}',
             columns: [
                { data: 'id', visible: false, searchable: false },
                { data: 'username', name: 'username' },
                { data: 'role.name', name: 'role.name' },
                { data: 'action', 'orderable': false, searchable: false}
            ],
        });
    });   
</script>
@endpush