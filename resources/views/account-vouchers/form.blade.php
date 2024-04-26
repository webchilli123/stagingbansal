@csrf
<section class="row">
    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Voucher Date</label>
        <input type="date" class="form-control" name="transaction_date" 
        value="{{ $transaction->transaction_date ?? old('transaction_date') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="" class="form-label">To Party</label>
        <div class="input-group">
            <span class="input-group-text">DR</span>
            <select name="dr_party" id="to_party" class="form-control" required>
                @if(isset($transaction))
                @foreach($parties as $key => $party)
                <option value="{{ $key }}" {{ $transaction->debitor_id == $key ? 'selected' : '' }}>
                    {{ $party }}
                </option>
                @endforeach
                @else
                <option selected value="">Choose...</option>
                @foreach($parties as $key => $party)
                <option {{ old('dr_party') == $key ? 'selected' : '' }} value="{{ $key }}">
                    {{ $party }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="" class="form-label">By Party</label>
        <div class="input-group">
            <span class="input-group-text">CR</span>
            <select name="cr_party" id="cr_party" class="form-control" required>
                @if(isset($transaction))
                @foreach($parties as $key => $party)
                <option value="{{ $key }}" {{ $transaction->creditor_id == $key ? 'selected' : '' }}>
                    {{ $party }}
                </option>
                @endforeach
                @else
                <option selected value="">Choose...</option>
                @foreach($parties as $key => $party)
                <option {{ old('cr_party') == $key ? 'selected' : '' }} value="{{ $key }}">
                    {{ $party }}
                </option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    
    @if($mode == 'create')
    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Item</label>
        <select name="item" id="item" class="form-control mb-1" required>
            @if($mode == 'create')
                <option selected value="">Choose...</option>
                @foreach($items as $item)
                <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Rate</label>
        <input type="text" class="form-control" name="rate" value="{{ old('rate') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Quantity</label>
        <input type="text" class="form-control" name="quantity" value="{{ old('qunatity') }}">
    </div>
    @endif

    <div class="{{ $mode == 'create' ? 'col-md-6 col-lg-12' : 'col-md-6' }} mb-3">
        <label for="" class="form-label">Amount</label>
        <input type="text" class="form-control" name="amount" value="{{  $amount ?? old('amount') }}" required>
    </div>

</section>

@if($mode == 'edit' && isset($transaction->order_id))
    <div class="mb-3">
        <label for="" class="form-label">{{ ucfirst($transaction->order->type) }} Order No.</label>
        <div class="form-control">{{ $transaction->order->order_number }}</div>
    </div>
@endif

@if($mode == 'edit' && isset($transaction->transfer_id))
    <div class="mb-3">
        <label for="" class="form-label">Transfer No.</label>
        <div class="form-control">{{ $transaction->transfer->transfer_number }}</div>
    </div>
@endif

<div class="mb-3">
    <label for="" class="form-label">Narration</label>
    <textarea name="narration" id="narration" cols="30" rows="5"
    class="form-control">{{ $transaction->narration ?? old('narration') }}</textarea>
</div>

<button type="submit" class="btn btn-primary mb-5">{{ $mode == 'create' ? 'Save' : 'Edit' }}</button>

@push('scripts')

<script>
    $(document).ready(()=>{
       $('select').selectize();


       $('input[name=rate]').change(function(){

            let amount = $(this).val() * $('input[name=quantity]').val();
            console.log(amount);
            $('input[name=amount]').val(amount);
       });

       $('input[name=quantity]').change(function(){
            let amount = $(this).val() * $('input[name=rate]').val();
            $('input[name=amount]').val(amount);
       });

   });
</script>

@endpush