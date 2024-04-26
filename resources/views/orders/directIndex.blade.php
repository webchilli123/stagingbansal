@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5>Orders</h5>
  @can('create', App\Models\Order::class)
    <a href="{{ route('direct.sale.create') }}" class="btn btn-primary">Order</a>
  @endcan
</header>

@include('orders.accordion')

<section class="table-responsive-sm rounded mb-5">
  <table class="table table-striped table-borderless border" id="orders">
    <thead>
      <tr>
        <th></th>
        <th>Id</th>
        <th>Order No.</th>
        <th></th>
        <th>Status</th>
        <th>Order Date</th>
        <th>Due Date</th>
        <th>Party</th>
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

       let table = $('table#orders').DataTable({
              processing: true,
              serverSide: true,
              order: [[5,'desc']],
              responsive:{ details: false },
              ajax : '{{ route('direct.sale.listing') }}',
              columns: [
                  {
                    className: 'details-control',
                    orderable: false,
                    searchable: false,
                    data: null,
                    defaultContent: ''
                  }, 
                  { data: 'id', visible: false, searchable: false },
                  { data: 'order_number', name: 'order_number' },
                  { data: 'type', name: 'type', visible: false },
                  { data: 'status', name: 'status' },
                  { data: 'order_date', name: 'order_date' },
                  { data: 'due_date', name: 'due_date' },
                  { data: 'party.name', name: 'party.name'},
                  { data: 'party_id', name: 'party_id', visible: false },
                  { data: 'action', 'orderable': false, searchable: false}
              ],
          });

                      
        $('#order_type').change(function(){
            table.column(3).search($(this).val()).draw();
        });

        $('#party_id').change(function(){
            table.column(8).search($(this).val()).draw();
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