@extends('layouts.dashboard')
@section('content')

<header class="row no-gutters mb-4">
  <div class="col-lg-7 mb-2">
    <h5>Narration Wise Stock</h5>
  </div>
  <div class="col-lg-12 mb-2">
    <form action="{{ route('narration.index') }}" method="GET" class="d-flex align-items-center">
      <div class="input-group">
        <span class="input-group-text">From Date</span>
        <select name="from_date" class="form-control">
          <option value="" selected>Choose...</option>
          @foreach ($items as $key => $transaction)
          <option value="{{ $transaction }}" {{  request('transaction_date') == $key ? 'selected' : '' }}>{{ $transaction }}</option>
          @endforeach
        </select>
      </div>
        <div class="input-group">
        <span class="input-group-text">To Date</span>
        <select name="to_date" class="form-control">
          <option value="" selected>Choose...</option>
          @foreach ($items as $key => $transaction)
          <option value="{{ $transaction }}" {{  request('transaction_date') == $key ? 'selected' : '' }}>{{ $transaction }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-primary ms-1">
        <i class="fa fa-search"></i>
      </button>
    </form>
  </div>
</header>

@if(isset($transactions))
<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="parties">
    <thead>
      <tr>
        <th>Transaction Date</th>
        <th>Debitor Name</th>
         <th>Creditor Type</th>
        <th>Whatsapp Narration</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($transactions as $transaction)
      <tr>

		
        <td>{{$transaction->transaction_date}}</td>
        <td>{{$transaction->GetpartyAgain ? $transaction->GetpartyAgain->name : ''}}</td>
        <td>{{$transaction->Getparty ? $transaction->Getparty->name : ''}}</td>
        <td>{{$transaction->wa_narration}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $transactions->links() }}
</section>
@endif

@endsection

@push('scripts')
<script>
  $(document).ready(() => {
    $('select').selectize();
  });   
</script>
@endpush