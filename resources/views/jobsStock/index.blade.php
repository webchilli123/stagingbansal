@extends('layouts.dashboard')
@section('content')
  <div class="col-lg-12 mb-2">
    
      

          <a href="{{route('jobs.create')}}"><button style="float:right;" class="btn btn-primary">Add</button></a>

  </div>
<header class="row no-gutters mb-4">
  <div class="col-lg-7 mb-2">
    <h5>Item Stock</h5>
  </div>

</header>


<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="parties">
    <thead>
      <tr>
        <th>Party Name</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>




    </tbody>
  </table>


</section>


@endsection


@push('scripts')
<script>
  $(document).ready(() => {
    $('select').selectize();
  });   
</script>
@endpush