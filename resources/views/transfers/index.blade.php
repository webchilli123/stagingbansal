@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5>Transfers </h5>
  @can('create', App\Models\Transfer::class)
  <div>
    <a href="{{ route('transfers.create') }}?sender_id=4029" class="btn btn-primary me-1">
     <i class="fa fa-arrow-up me-1"></i> Transfer
    </a>
    <a href="{{ route('transfers.receive.create') }}" class="btn btn-success text-white">
      <i class="fa fa-arrow-down me-1"></i> Receive
    </a>
  </div>
  @endcan
</header>

<section class="table-responsive-md rounded mb-5">
 
  <table class="table table-striped table-borderless border" id="transfers">
    <thead>
      <tr>
        <th></th>
        <th>Id</th>
        <th>Transfer No.</th>
        <th>Transfer Date</th>
        <th>Receive</th>
        <th>From Party</th>
        <th>To Party</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>
</section>

@endsection


@push('scripts')
<script>
  $(document).ready(() => {

      let table =  $('table#transfers').DataTable({
             processing: true,
             serverSide: true,
             order: [[1,'desc']],
             ajax: '{{ route('transfers.index') }}',
             columns: [
                {
                  className: 'details-control',
                  orderable: false,
                  searchable: false,
                  data: null,
                  defaultContent: ''
                },
                { data: 'id', visible: false, searchable: false },
                { data: 'transfer_number', name: 'transfer_number' },
                { data: 'transfer_date', name: 'transfer_date' },
                { data: 'is_receive', name: 'is_receive' },
                { data: 'sender.name', name: 'sender.name' },
                { data: 'receiver.name', name: 'receiver.name' },
                { data: 'action', 'orderable': false, searchable: false}

            ]
        });

        $('table#transfers tbody').on('click', 'td.details-control', function () {
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