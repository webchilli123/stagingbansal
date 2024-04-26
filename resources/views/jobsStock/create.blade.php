@extends('layouts.dashboard')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<form action="{{ route('jobs.store') }}" method="POST" class='stockform'>
	@csrf
<section class="row">
    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Party Name.</label>
       <select name="party_name"  class="form-control select2 party_id" required>
        <option selected value="">Choose...</option>
            @foreach($parties as $type)
               <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
            @endforeach
        </select>
         @error('party_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Item Name</label>
        <select name="item_name"  class="form-control select2 item_id">
       <option selected value="">Choose...</option>
            @foreach($items as $type)
               <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 col-lg-6 mb-3">
        <label for="" class="form-label">Quantity</label>
        <input type="text" class="form-control" name="quantity">
       @error('quantity')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 col-lg-6 mb-3">
        <label for="" class="form-label">Type</label>
        <select name="quantity_acc" class="form-control">
            <option value="receivable">receivable</option>
            <option value="payable">payable</option>
        </select>
       @error('quantity_acc')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 col-lg-6 mb-3">
        <label for="" class="form-label">Amount</label>
        <div class="input-group">
        
        <input type="text" class="form-control" name="amount" >
    </div>
    </div>
        <div class="col-md-6 col-lg-6 mb-3">
        <label for="" class="form-label">Type</label>
        <select name="amount_acc" class="form-control">
            <option value="payable">payable</option>
            <option value="receivable">receivable</option>
        </select>
       @error('quantity_acc')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    
</section>
<button type="submit" class="btn btn-primary mb-5 submitStock" value="submit">Submit</button>
</form>





<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="parties">
    <thead>
      <tr>
        <th>Party Name</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Amount</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>


@foreach($getdeta as $deta)

            <tr>
                <td>{{ $deta->party ? $deta->party->name : '' }}</td>
                <td>{{ $deta->getItem ? $deta->getItem->name : '' }}</td>
                <td>{{ $deta->quantity }} {{ $deta->quantity_acc }}</td>
                <td>{{ $deta->amount }} {{ $deta->amount_acc }}</td>
                <td><button class='btn btn-primary delete_entry' data-id='{{$deta->id ?? ""}}'><i class='fa fa-trash'></i></button></td>
            </tr>
@endforeach

    </tbody>
  </table>


</section>

@endsection



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
 $('select').selectize();

$('.delete_entry').click(function(e){
    var id = $(this).data('id');
    if(id){
        swal({
            title: "Are you sure !",
            text: "Do you want to delete the stock entry",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((response) => { 
            if (response) {
                $.ajax({
                    type: "get",
                    url: "{{url('deleteExistStock')}}",
                    data: {id:id},
                    dataType: 'json',  
                    success: function(data) {
                        swal("Opening Stock deleted successfully"); 
                        window.location.reload();
                    }
                });
            } 
        }); 
        
    }
});


$('.submitStock').click(function(e){
    e.preventDefault();
    var party_id = $('.party_id').val();
    var item_id =  $('.item_id').val();
    $.ajax({
        type: "get",
        url: "{{url('isExistStock')}}",
        data: {party_id:party_id,item_id:item_id},
        dataType: 'json',  
        success: function(data) {
            if(data.status == true){ 
                swal({
                    title: "Item with party name exist !",
                    text: "Do you want to update existing item stock",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((response) => {
                    if (response) {
                        $('.stockform').submit();
                    } 
                }); 
            }else{
                $('.stockform').submit();
            }
        }
    });
});
</script>
@endpush