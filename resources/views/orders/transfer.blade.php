@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
    <h5>
        @if ($order->type == App\Models\Order::SALE)
          Sale Items - {{ $order->order_number }}
        @else
          Purchase Items - {{ $order->order_number }}
        @endif
    </h5>
    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('orders.transfer.store', ['order' => $order]) }}" method="POST"
    onsubmit="return confirm('Are you sure?');">
    @csrf
    <div class="row">
        <div class="col-lg-6 mb-3">
            <label for="" class="form-label">Party Name</label>
            <div class="form-control">{{ $order->party->name }}</div>
        </div>

        <div class="col-lg-6 mb-3">
            <label for="" class="form-label">Order Date</label>
            <div class="form-control">{{ $order->order_date->format('d M, Y') }}</div>
        </div>

        <div class="col-lg-6 mb-3">
            <label for="" class="form-label">Due Date</label>
            <p class="form-control">{{ $order->due_date->format('d M, Y') }}</p>
        </div>

        <div class="col-md-6 mb-3">
            <label for="" class="form-label">
                {{ $order->type == App\Models\Order::SALE ? 'Sale' : 'Purchase' }} Date
            </label>
            <input type="date" name="payment_date" class="form-control" required>
        </div>

    </div>

    @include('orders.transfer-items')
    @if(!$isreturn)

    {{-- transport information --}}
    @if ($order->type == App\Models\Order::SALE)
       <h6 class="border-bottom pb-2 fw-bold mb-3">
           <i class="fa fa-circle text-success me-1"></i> Transportation Information
        </h6>
        <div class="row mb-2">
           <div class="col-lg-6 mb-3">
              <label for="" class="form-label">Transport Name</label>
               <select name="transport_id" class="form-control">
                    <option selected value="">Choose...</option>
                    @foreach($transports as $id => $transport)
                        <option {{ old('transport_id') == $id ? 'selected' : '' }} value="{{ $id }}">
                            {{ $transport }}
                        </option>
                    @endforeach
               </select>
           </div>
           <div class="col-lg-6 mb-3">
                <label for="" class="form-label">Bilty No.</label>
                <input type="text" name="bilty_number" class="form-control">
            </div>
            <div class="col-lg-6 mb-3">
                <label for="" class="form-label">Vehicle No.</label>
                <input type="text" name="vehicle_number" class="form-control">
            </div>
            <div class="col-lg-6 mb-3">
                <label for="" class="form-label">Transport Date</label>
                <input type="date" name="transport_date" class="form-control">
            </div>
        </div>
    @endif
    <h6 class="border-bottom pb-2 fw-bold mb-3">
        <i class="fa fa-circle text-success me-1"></i> Narration
    </h6>
    <div class="mb-3">
        <label for="" class="form-label">Narration (Order)</label>
        <textarea name="narration" id="narration" cols="30" rows="5" class="form-control">{{ $order->narration }}</textarea>
    </div>

    <div class="mb-3">
        <label for="" class="form-label">Narration (Voucher)</label>
        <textarea name="voucher_narration" id="voucher_narration" cols="30" rows="5" class="form-control">{{ old('voucher_narration') }}</textarea>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Narration (WhatsApp)</label>
        <textarea name="wa_narration" id="wa_narration" cols="30" rows="5" class="form-control">{{ $order->wa_narration ? $order->wa_narration : '' }}</textarea>
    </div>
@endif
    <button type="submit" class="btn btn-primary mb-5">
        @if($isreturn)
            Return
        @else
        {{ $order->type == App\Models\Order::SALE  ? 'Send' : 'Receive' }}
        @endif
    </button>

</form>

@endsection

@push('scripts')
<script>

$(document).ready(()=>{

    $('[name=transport_id]').selectize();

    $(`[name='items_id[]']`).click(function(){

      if($(this).is(':checked')){
        $(this).parent().next('input').prop('disabled', false);
        $(this).parent().next('input').prop('required', true);
      }else{
        $(this).parent().next('input').prop('disabled', true);
        $(this).parent().next('input').prop('required', false);
        $(this).parent().next('input').val('');

      }

    });

    $('[name=gst_amount]').on('input', setPaymentAmount);
    $('[name=extra_charges]').on('input', setPaymentAmount);


    // check stock
    $('table#transfer tbody').on('change',`input[name='current_quantities[]']`,function(){



        let orderType = '{{ $order->type }}';
        let sale = '{{ App\Models\Order::SALE }}';

        let quantity = $(this).val();
        let max = $(this).attr('max');
        var isreturn = "{{$isreturn ?? ''}}";
        if(isreturn){
            if(quantity > parseInt(max)){
                alert( `You can select only ${max}`);
                $(this).val('');
                return;
            }else{
                setAmount();
                setPaymentAmount();
                return;
            }
        }
        setAmount();
        setPaymentAmount();
        if(orderType == sale && quantity.length > 0){
            $.ajax({
                url : `{{ route('stock.check') }}`,
                dataType : 'json',
                data : { item_id : $(this).attr('data-item-id'), current_quantity :quantity }
            }).done((data)=>{
                if(parseFloat(data.total_quantity) < parseFloat(quantity)){
                    alert(`Stock of ${ data.item.name } is ${ data.total_quantity} in Self Store.`);
                    $(this).val('');
                    return;
                }else{
                    if(parseFloat(quantity) > data.total_quantity){
                        alert( `You can select only ${data.total_quantity}`);
                        $(this).val('');
                        return;
                    }
                }

            }).fail((error)=> console.log(error));

        }
    });


});

function setPaymentAmount(){

    let payment_amount = parseFloat($('[name=amount]').val())
            + parseFloat($('[name=gst_amount]').val())
            + parseFloat($('[name=extra_charges]').val());

    $('[name=payment_amount]').val(payment_amount.toFixed(2));

}

function setAmount(){

    let total_amount = 0;

    $(`input[name='current_quantities[]']`).each(function(index, el){
        let rate = $(this).attr('data-item-rate');
        let amount =  rate * $(this).val();
        total_amount = total_amount + amount;
    });

    $('[name=amount]').val(total_amount.toFixed(2));
}

</script>
@endpush
