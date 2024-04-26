@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5>Salary Vouchers</h5>
  @can('create', App\Models\Category::class)
  <a href="{{ route('salary-vouchers.create') }}" class="btn btn-primary">Voucher</a>
  @endcan
</header>

<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="vouchers">
    <thead>
      <tr>
        <th></th>
        <th>Id</th>
        <th>Voucher No.</th>
        <th>Voucher Date</th>
        <th>Employee</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>
</section>

@endsection

@push('scripts')
<script>
  $(document).ready(() => {

      let table =  $('table#vouchers').DataTable({
             processing: true,
             serverSide: true,
             order: [[0,'desc']],
             ajax: `{{ route('salary-vouchers.index') }}`,
             columns: [
                {
                  className: 'details-control',
                  orderable: false,
                  searchable: false,
                  data: null,
                  defaultContent: ''
                }, 
                { data: 'id', visible: false, searchable: false },
                { data: 'voucher_number', name: 'voucher_number' },
                { data: 'voucher_date', name: 'voucher_date' },
                { data: 'employee.name', name: 'employee.name' },
                { data: 'action', 'orderable': false, searchable: false}
            ],
        });

        $('table#vouchers tbody').on('click', 'td.details-control', function () {
            let tr = $(this).closest('tr');
            let row = table.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });

        function format (d) {

          return `<table class="table table-borderless">
                  <tr>   
                    <td>Description</td>
                    <td>${ d.description }</td>
                  </tr>
              </table>`;
        }
    });   
</script>
@endpush