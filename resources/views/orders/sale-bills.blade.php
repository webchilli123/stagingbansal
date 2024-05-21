@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5>Sale Bills</h5>
  @can('create', App\Models\Order::class)
    <a href="{{ route('bill.sale.create') }}" class="btn btn-primary">Create Sale Bill</a>
  @endcan
</header>

@include('orders.accordion-bills')

<section class="table-responsive-sm rounded mb-5">
  <table class="table table-striped table-borderless border" id="bills">
    <thead>
      <tr>
        <th></th>
        <th></th>
        <th>Bill ID</th>
        <th>Party Name</th>
        <th>Created  On</th>
        <th></th>
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

       let table = $('table#bills').DataTable({
              processing: true,
              serverSide: true,
              order: [[5,'desc']],
              responsive:{ details: false },
              ajax : '{{ route("order.sale.bills") }}',
              columns: [
                  {
                    className: 'details-control',
                    orderable: false,
                    searchable: false,
                    data: null,
                    defaultContent: ''
                  }, 
                  { data: 'id', visible: false, searchable: false },
                  { data: 'bill_id', name: 'bill_id' },
                  { data: 'party.name', name: 'party.name'},
                  { data: 'created_at', name: 'created_at' },
                  { data: 'party_id', name: 'party_id', visible: false },
                  { data: 'action', 'orderable': false, searchable: false,}
              ],
          });

                      
        $('#order_type').change(function(){
            table.column(3).search($(this).val()).draw();
        });

        $('#entry_type').change(function(){
            table.column(9).search($(this).val()).draw();
        });
  

        $('table#orders tbody').on('click', 'td.details-control', function () {
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
                    <td class="fw-bold">Narration</td>
                    <td>${ d.narration }</td>
                  </tr>
              </table>`;
        }


    });   
</script>
@endpush