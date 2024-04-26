@extends('layouts.dashboard')
@section('content')

<header class="row no-gutters mb-4">
  <div class="col-lg-7 mb-2">
    <h5>Order Transports</h5>
  </div>
  <div class="col-lg-5 mb-2">
    <form action="{{ route('order-transports.index') }}" method="GET" class="d-flex align-items-center">
      <div class="input-group">
        <span class="input-group-text">Party</span>
        <select name="party_id" class="form-control">
          <option value="" selected>Choose...</option>
          @foreach ($parties as $key => $party)
          <option value="{{ $key }}" {{  request('party_id') == $key ? 'selected' : '' }}>{{ $party }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-primary ms-1">
        <i class="fa fa-search"></i>
      </button>
    </form>
  </div>
</header>

@if(isset($orders))
<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="parties">
    <thead>
      <tr>
        <th>Order No.</th>
        <th>Transport Name</th>
        <th>Phone No.</th>
        <th>Bilty No.</th>
        <th>Vechile No.</th>
        <th>Transport Date</th>
        <th>Narration</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($orders as $order)
       @foreach ($order->transferTransactions as $transaction)
        <tr>
            <td>
                <a href="{{ route('orders.show', ['order' => $order]) }}" target="_blank">
                    SO-{{ $order->order_number }}
                </a>
                <td>{{ $transaction->transport->name  ?? '' }}</td>
                <td>{{ $transaction->transport->phone_number ?? '' }}</td>
                <td>{{ $transaction->bilty_number }}</td>
                <td>{{ $transaction->vehicle_number }}</td>
                <td>{{ isset($transaction->transport_date) ? $transaction->transport_date->format('d M, Y') : '' }}</td>
                <td>
                  <button type="button" class="btn btn-sm" 
                    data-bs-toggle="tooltip" title="{{ $transaction->narration ?? 'NOT GIVEN' }}">
                    <i class="fa fa-info-circle text-primary"></i>
                  </button>
                </td>
            </td>
        </tr>
       @endforeach
      @endforeach
    </tbody>
  </table>
  {{ $orders->links() }}
</section>
@endif

@endsection

@push('scripts')
<script>
  $(document).ready(() => {
    $('select').selectize();

    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
     });

  });   
</script>
@endpush