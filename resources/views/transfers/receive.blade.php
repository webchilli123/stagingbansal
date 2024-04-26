@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>Transfer <span class="bg-primary text-white px-1 rounded h6 ms-1">Receive</span></h5>
    <div>
        @if (request('sender_id'))
            <a href="{{ route('transfers.receive.create') }}" class="btn btn-sm btn-primary me-1" title="refresh">
                <i class="fa fa-redo"></i>
            </a>
        @endif
        <a href="{{ route('transfers.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
</header>

<form action="{{ route('transfers.receive.store') }}" method="POST" 
    onsubmit="return confirm('Are you sure?');">
    @csrf
    <section class="row">
        <div class="col-md-6 mb-3">
            <label for="" class="form-label">Transfer No.</label>
            <div class="form-control">{{ $transfer_number }}</div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="" class="form-label">Transfer Date</label>
            <input type="date" class="form-control" name="transfer_date" value="{{ old('transfer_date') ?? date('Y-m-d') }}" required>
        </div>
        
        {{-- <div class="col-md-6 mb-3">
            <label for="" class="form-label">Order No. (Material Source)</label>
            <select name="order_id" class="form-control">
                <option selected value="" disabled>Choose...</option>
                @foreach($orders as $order)
                   <option {{ old('order_id') == $order->id ? 'selected' : '' }} value="{{ $order->id }}">
                    PO-{{ $order->order_number }} | {{ $order->party->name }}
                </option>
                @endforeach
            </select>
        </div> --}}

        {{-- <div class="col-md-6 mb-3">
            <label for="" class="form-label">For Process</label>
            <select name="process_id" class="form-control" required>
                <option selected value="" disabled>Choose...</option>
                @foreach($processes as $key => $process)
                   <option {{ old('process_id') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $process }}</option>
                @endforeach
            </select>
        </div> --}}

        <div class="col-md-6 mb-3">
            <label for="" class="form-label">From Location (Sender)</label>
            <select name="sender_id" class="form-control" required>
                <option selected value="" disabled>Choose...</option>
                @foreach($parties as $key => $party)
                   @if($key !== App\Models\Party::SELF_STORE)
                    <option {{ old('sender_id') == $key || request('sender_id') == $key ? 'selected' : '' }} value="{{ $key }}">
                       {{ $party}}
                    </option>
                   @endif
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label for="" class="form-label">To Location (Receiver)</label>
            <select class="form-control">
                <option selected value="" disabled>Self Store</option>
            </select>
        </div>

    </section>
    
    @if (isset($available_items))    
        <section class="table-responsive-md rounded mt-3">
            <table class="table table-bordered align-middle dTable" id="receive" style="min-width: 50rem;">
                <thead class="small">
                    <tr>
                        <th style="min-width: 10rem;">Item</th>
                        <th style="min-width: 8rem;">Avaiable QTY (Kg)</th>
                        <th style="min-width: 12rem;">Item To Receive</th>
                        <th style="min-width: 6rem;">Rate</th>
                        <th style="min-width: 8rem;">Receive QTY (Current)</th>
                        <th style="min-width: 8rem;">Receive QTY <br>(+ Waste)</th>
                        <th style="min-width: 6rem;">Waste %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($available_items as $k=> $available_item)
                    @if(!empty($available_item->total))   
                    <tr>
                        <td>{{ $available_item->name ?? '' }}</td>
                        <td  data-item-id="0">{{ $available_item->total }}</td>
                     <td  class="text-start">
                            <select name="receive_item_id[]" class="form-control" disabled> 
                                <option value="" selected disabled>Select</option>
                                @foreach ($items as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.0001" name="rates[]" class="form-control" disabled>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-text">
                                  <input type="checkbox" name="items_id[]" value="{{ $available_item->item_id }}" class="form-check-input">
                                </div>
                                <input type="number" name="receive_quantities[]" step="0.01" class="form-control" 
                                
                                 data-item-id="{{ $available_item->item_id }}" disabled>
                            </div>
                        </td>
                        <td>
                            <input type="number" name="receive_quantities_with_waste[]" step="0.01"
                            class="form-control" disabled>
                        </td>
                    <!--     max="{{ $available_item->total }}"  -->
                        <td>
                            <input type="number" name="wastes[]" step="0.01" class="form-control" disabled>
                        </td>
                    </tr>
                    @endif
                    @if ($k == count($available_items) - 1)
                        <tr>
                            <td ></td>
                            <td ></td>
                            <td ></td>
                            <td class="fw-bold">Payment Amount</td>
                            <td ></td>
                            <td ></td>
                            <td >
                                <input type="number" step="0.001" name="payment_amount"
                                 value="0" class="form-control" readonly>
                            </td>
                        </tr>
                    @endif 
                   
                    @endforeach
                </tbody>
            </table>
        </section>
    @endif

    <div class="mb-3">
        <label for="" class="form-label">Narration</label>
        <textarea name="narration" id="narration" cols="30" rows="5"
            class="form-control">{{ old('narration') }}</textarea>
    </div>
    
    <button type="submit" class="btn btn-primary mb-5">Receive</button>
    
</form>
@endsection


@push('scripts')
<script>
  
  $(document).ready(()=>{
   

    
    $('.dTa ble ').DataTable({
            // data: name,
            deferRender: true,
            scrollX:100,
            scrollCollapse: true,
            scroller: true,
            info: false,
            //  "bPaginate": false
            // "lengthMenu": [10,20, 40, 60, 80, 100],
        "pageLength": 10
        });

    $('select').selectize();
    
    const url = `{{ route('transfers.receive.create') }}`;

    $('select[name=sender_id]').on('change', function(){
       window.location = `${url}?sender_id=${$(this).val()}`;
    });

    // check stock
    $('table#receive tbody').on('change',`input[name='receive_quantities[]']`,function(){
        
        let quantity = $(this).val();
        let party_id = $('select[name=sender_id]').val();
        let item_id = $(this).attr('data-item-id');
        if(quantity.length > 0 && item_id.length > 0){
           getItemData(item_id, quantity, party_id ,$(this));
        }
    });
    
    $('table#receive tbody').on('input',`input[name='wastes[]']`,function(){

        let quantityEl = $(this).parent().parent().find(`input[name='receive_quantities[]']`);
        quantityEl.val() ?  quantityEl.val() : quantityEl.val(0);
        let quantity =  $(this).val() / 100  * quantityEl.val() +  parseInt(quantityEl.val());
        $(this).parent().parent().find(`input[name='receive_quantities_with_waste[]']`).val(quantity);
    });

    $('table#receive tbody').on('input', `input[name='receive_quantities[]']`, function(){

        let wasteEl = $(this).parent().parent().parent().find(`input[name='wastes[]']`);
        wasteEl.val() ?  wasteEl.val() : wasteEl.val(0);
        let quantity =  $(this).val() / 100  * wasteEl.val() + parseInt($(this).val());
        $(this).parent().parent().parent().find(`input[name='receive_quantities_with_waste[]']`).val(quantity);
        setPaymentAmount();
    });

    $('table#receive tbody').on('input',`input[name='rates[]']`, setPaymentAmount);


    $(`[name='items_id[]']`).click(function(){
        
        const rowEl = $(this).parent().parent().parent().parent();

        if($(this).is(':checked')){
          rowEl.find('input[type=number]').prop('disabled', false);
          rowEl.find('input[type=number]').prop('required', true);
          
          let selectEl = rowEl.find('select')[0];
          selectEl.selectize.enable();

        }else{
            rowEl.find('input[type=number]').prop('disabled', true);
            rowEl.find('input[type=number]').prop('required', false);
            rowEl.find('input[type=number]').val('');
            
            let selectEl = rowEl.find('select')[0];
            selectEl.selectize.disable();
        }

    });

  });


  
  function getItemData(item, quantity, party_id ,quantitEl){
    $.ajax({
            url : `{{ route('stock.checkClient') }}`,
            dataType : 'json',
            quantity: $(this).find('select[name=receive_item_id]').text(),
            data : { item_id : item, current_quantity : quantity, party_id : party_id }
        }).done((data)=>{
            if (data.getop_stock == null) {
             var total = data.total_quantity;
                 
            }else{
                var a = parseInt(data.getop_stock.quantity);
                  var b = parseInt(data.total_quantity);
                  var total = a+b; 
            }
         
            if(parseFloat(total) < parseFloat(quantity)){
                alert(`Stock of ${ data.item.name } is ${ total} in ${ data.party.name }.`);
                quantitEl.val('');
                return;
            }

        }).fail((error)=> console.log(error));

    }

    function setPaymentAmount(){

        let payment_amount = 0;

        $(`input[name='receive_quantities[]']`).each(function(index, el){
            let rateEl = $(this).parent().parent().parent().find(`input[name='rates[]']`);
            let amount =  rateEl.val() * $(this).val();
            payment_amount = payment_amount + amount;
        });

        $('[name=payment_amount]').val(payment_amount.toFixed(2));

    }
  
</script>

@endpush