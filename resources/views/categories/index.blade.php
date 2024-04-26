@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-categories-center mb-4">
  <h5>Categories</h5>
  @can('create', App\Models\Category::class)
  <a href="{{ route('categories.create') }}" class="btn btn-primary">Category</a>
  @endcan
</header>

<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="categories">
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

        $('table#categories').DataTable({
             processing: true,
             serverSide: true,
             order: [[0,'desc']],
             ajax: '{{ route('categories.index') }}',
             columns: [
                { data: 'id', visible: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'action', 'orderable': false, searchable: false}
            ],
        });
    });   
</script>
@endpush