@extends('layouts.dashboard')
@section('content')


{{-- make public storage link --}}
@if(!file_exists(public_path('storage')))
<div class="alert border d-flex align-items-center justify-content-between py-2 mb-4">
   <span>
      <i class="fa fa-link bg-primary text-white rounded p-1 me-1"></i> Storage link not found.
   </span>
   <a href="{{ route('storage.link.create') }}" class="btn btn-primary">Create</a>
</div>
@endif


<section class="mb-2">
   <div class="row">
      <div class="col-md-6 col-lg-4">
         @can('viewAny', App\Models\Party::class)
         <x-card title="parties" url="{{ route('parties.index') }}" icon="fa-users" color="success" />
         @endcan
      </div>
      <div class="col-md-6 col-lg-4">
         @can('viewAny', App\Models\Order::class)
         <x-card title="orders" url="{{ route('orders.index') }}" icon="fa-store" color="info" />
         @endcan
      </div>
      <div class="col-md-6 col-lg-4">
         @can('viewAny', App\Models\Transfer::class)
         <x-card title="transfers" url="{{ route('transfers.index') }}" icon="fa-exchange-alt" color="warning" />
         @endcan
      </div>
   </div>
</section>

@can('create', App\Models\Transaction::class)
<section class="mb-2">
   <div class="row">
      <div class="col-md-6 col-lg-4">
         <x-card title="account vouchers" url="{{ route('account-vouchers.create') }}" icon="fa-user-friends" color="danger" />
      </div>
      <div class="col-md-6 col-lg-4">
         <x-card title="stock vouhers" url="{{ route('stock-vouchers.create') }}" icon="fa-money-check" color="primary" />
      </div>
      <div class="col-md-6 col-lg-4">
         <x-card title="ledger" url="{{ route('ledger.index') }}" icon="fa-book" color="indigo" />
      </div>
   </div>
</section>
@endcan

<section class="row">
   <div class="col-lg-6">
      <div class="table-responsive-md">
         <table class="table table-sm mb-4 table-bordered">
            <thead>
               <tr class="text-center">
                  <th colspan="4" class="text-uppercase text-secondary fw-normal">
                     <i class="fa fa-circle text-danger me-1"></i> Sale Orders
                  </th>
               </tr>
               <tr>
                  <th class="fw-normal text-secondary">Order No.</th>
                  <th class="fw-normal text-secondary">Due Date</th>
                  <th class="fw-normal text-secondary">Party</th>
                  <th class="fw-normal text-secondary">Status</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($sale_orders as $order)
               <tr>
                  <td class="ps-2">{{ $order->order_number }}</td>
                  <td>{{ $order->due_date->format('d M, Y') }}</td>
                  <td>{{ Str::limit($order->party->name, 20) }}</td>
                  <td>{{ ucwords($order->status) }}</td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
   <div class="col-lg-6">
      <div class="table-responsive-md">
         <table class="table table-sm table-bordered">
            <thead>
               <tr class="text-center">
                  <th colspan="4" class="text-uppercase text-secondary fw-normal">
                     <i class="fa fa-circle text-success me-1"></i> Purchase Orders
                  </th>
               </tr>
               <tr>
                  <th class="fw-normal text-secondary ps-2">Order No.</th>
                  <th class="fw-normal text-secondary">Due Date</th>
                  <th class="fw-normal text-secondary">Party</th>
                  <th class="fw-normal text-secondary">Status</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($purchase_orders as $order)
               <tr>
                  <td class="ps-2">{{ $order->order_number }}</td>
                  <td>{{ $order->due_date->format('d M, Y') }}</td>
                  <td>{{ Str::limit($order->party->name, 20) }}</td>
                  <td>{{ ucwords($order->status) }}</td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>

    <!-- charts -->

    <div class="container mt-5">
      <div class="row justify-content-around">
         <div class="col-sm-4">
            <div class="card custom-card">
               <div class="card-body">
                  <h5 class="card-title mb-3">Sale Order</h5>
                  <canvas id="saleOrder" class="chart-canvas"></canvas>
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="card custom-card">
               <div class="card-body">
                  <h5 class="card-title mb-3">Purchase Order</h5>
                  <canvas id="purchaseOrder" class="chart-canvas"></canvas>
               </div>
            </div>
         </div>
      </div>
   </div><br>


   <!-- charts end -->

   <div class="my-4"></div>

<!-- funnel chart -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-6">
            @include('widgets.funnel-chart')
        </div>
    </div>
</div>
<!-- funnel chart end -->

   </div>

   
  

</section>

@endsection
@push('scripts')
<script>
   var ctx = document.getElementById('saleOrder').getContext('2d');
   var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
         labels: ['Pending', 'Completed', 'In Complete'],
         datasets: [{
            data: @json($saleOutput),
            backgroundColor: [
               'rgba(255, 99, 132, 0.7)',
               'rgba(54, 162, 235, 0.7)',
               'rgba(255, 206, 86, 0.7)',
               'rgba(75, 192, 192, 0.7)',
               'rgba(153, 102, 255, 0.7)',
            ],
            borderColor: [
               'rgba(255, 99, 132, 1)',
               'rgba(54, 162, 235, 1)',
               'rgba(255, 206, 86, 1)',
               'rgba(75, 192, 192, 1)',
               'rgba(153, 102, 255, 1)',
            ],
            borderWidth: 1
         }]
      },
   });

   var ctx = document.getElementById('purchaseOrder').getContext('2d');
   var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
         labels: ['Pending', 'Completed', 'In Complete'],
         datasets: [{
            data: @json($purchaseOutput),
            backgroundColor: [
               'rgba(255, 99, 132, 0.7)',
               'rgba(54, 162, 235, 0.7)',
               'rgba(255, 206, 86, 0.7)',
               'rgba(75, 192, 192, 0.7)',
               'rgba(153, 102, 255, 0.7)',
            ],
            borderColor: [
               'rgba(255, 99, 132, 1)',
               'rgba(54, 162, 235, 1)',
               'rgba(255, 206, 86, 1)',
               'rgba(75, 192, 192, 1)',
               'rgba(153, 102, 255, 1)',
            ],
            borderWidth: 1
         }]
      },
   });
</script>
@endpush