@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Transfer</h5>
    <div>
        @if (request('sender_id'))
        <a href="{{ route('transfers.create') }}" class="btn btn-sm btn-primary me-1" title="refresh">
            <i class="fa fa-redo"></i>
        </a>
        @endif
        <a href="{{ route('transfers.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
</header>

<form action="{{ route('transfers.store') }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
        <div class="col-md-6 mb-3">
            <label for="" class="form-label">From Location (Sender)</label>
            <select name="sender_id" id="sender_id" class="form-control" required>
                <option selected value="" disabled>Choose...</option>
                @foreach($parties as $key => $party)
                <option {{ old('sender_id') == $key || request('sender_id') == $key ? 'selected' : '' }} value="{{ $key }}">
                    {{ $party}}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="" class="form-label">To Location (Receiver)</label>
            <select name="receiver_id" class="form-control" required>
                <option selected value="" disabled>Choose...</option>
                @foreach($parties as $key => $party)
                <option {{ old('receiver_id') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $party}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="" class="form-label">For Process</label>
            <select name="process_id" class="form-control" required>
                <option selected value="" disabled>Choose...</option>
                @foreach($processes as $key => $process)
                <option {{ old('process_id') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $process }}</option>
                @endforeach
            </select>
        </div>

    </section>
    <!-- <form >
                            <div class="input-group"  >
                            <input type="text" class="form-control" placeholder="Search this blog">
                            <div class="input-group-append col-md-6">
                              <button class="btn btn-secondary" type="button">
                                <i class="fa fa-search"></i>
                              </button>
                            </div>
                          </div>
                        </form> -->
    @if (isset($available_items))
    <section class="table-responsive-lg rounded mt-3">
        <table class="table table-bordered align-middle" id="example">
            <thead>
                <tr>
                    <th  >Item </th>
                    <th>Avaiable Quantity (Kg)</th>
                    <th>Send Quantity (Kg)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($available_items as $available_item)
                @if (!empty($available_item->name))
                <?php  
                $plusrecord = $available_item->total; 
                ?>
                @if($plusrecord > 0) 
                <tr>
                    <td style="min-width: 12rem;">
                        {{ $available_item->name }} 
                    </td>
                    <td> 
                        {{ $available_item->total ?? '0'}} 
                    </td>
                    <td> 
                        <div class="input-group">
                            <div class="input-group-text">
                                <input type="checkbox" name="send_items_id[]" value="{{$available_item->id}}" class="form-check-input">
                            </div>
                            <input type="number" name="send_quantities[]" step="0.01" min="1" class="form-control" data-item-id="{{$available_item->id}}" max="{{ $available_item->total }}" disabled>
                        </div> 
                    </td>
                </tr>
                @endif
                @endif
                @endforeach
            </tbody>
        </table>
    </section>
    @endif

    {{-- @if (isset($available_items))    
        <section class="table-responsive-lg rounded mt-3">
            <table class="table table-bordered align-middle" id="transfer" style="min-width: 50rem;">
                <thead>
                    <tr>
                        <th>Item 
                            <form>
                            <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search this blog">
                            <div class="input-group-append col-md-6">
                              <button class="btn btn-secondary" type="button">
                                <i class="fa fa-search"></i>
                              </button>
                            </div>
                          </div>
                        </form>
                        </th>
                        <th>Avaiable Quantity (Kg)</th>
                        <th>Send Quantity (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($available_items as $available_item)
                    <tr>
                        <td style="min-width: 12rem;">{{ $available_item->item_name }}</td>
                        <td>{{ $available_item->total_quantity }}</td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-text">
                                  <input type="checkbox" name="send_items_id[]" value="" class="form-check-input">
                                </div>
                                <input type="number" name="send_quantities[]" step="0.01" min="1" class="form-control" 
                                data-item-id=""
                                max="{{ $available_item->total_quantity }}" disabled>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @endif --}}

    <div class="mb-3">
        <label for="" class="form-label">Narration</label>
        <textarea name="narration" id="narration" cols="30" rows="5" class="form-control">{{ old('narration') }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary mb-5">Transfer</button>

</form>
@endsection


@push('scripts')
<script>
    const url = `{{ route('transfers.create') }}`; 
    $(document).ready(() => {


        $('#example').DataTable({
            // data: name,
            deferRender: true,
            // scrollY:        200,
            scrollCollapse: true,
            scroller: true,
            info: false,
            // "bPaginate": false
            "lengthMenu": [20, 40, 60, 80, 100],
        "pageLength": 20
        });

        $('select').selectize();


        /*  if($('#sender_id').val() == ''){
         } */
        $('#sender_id').on('change', function() {
            var hr = `${url}?sender_id=${$(this).val()}`;
            window.location.href = hr;
        });

        // $(`[name='send_items_id[]']`).click(function() {
        $(document).on('change', `#example input[name='send_items_id[]']`, function() { 
            if ($(this).is(':checked')) {
                $(this).parent().next('input').prop('disabled', false);
                $(this).parent().next('input').prop('required', true);
            } else {
                $(this).parent().next('input').prop('disabled', true);
                $(this).parent().next('input').prop('required', false);
                $(this).parent().next('input').val('');
            }

        });

        // check stock
        $(document).on('change', `input[name='send_quantities[]']`, function() {

            if ($('[name=sender_id]').val().length == 0) {
                alert('Choose a Sender Party.');
                $(this).val('');
            }

            let quantity = $(this).val();
            let party_id = $('select[name=sender_id]').val();
            let item_id = $(this).attr('data-item-id'); 
            if (quantity.length > 0 && item_id.length > 0) {
                getItemData(item_id, quantity, party_id, $(this));
            }
        });



    });

    function getItemData(item, quantity, party_id, quantitEl) {

        $.ajax({
            url: `{{ route('stock.check') }}`,
            dataType: 'json',
            data: {
                item_id: item,
                current_quantity: quantity,
                party_id: party_id
            }

        }).done((data) => {
            if (parseFloat(data.total_quantity) < parseFloat(quantity)) {
                alert(`Stock of ${ data.item.name } is ${ data.total_quantity} in ${ data.party.name }.`);
                quantitEl.val('');
                return;
            }

        }).fail((error) => console.log(error));

    }
</script>
<script>
    $(document).ready(function() {
        var data = "{{$getItem ?? ''}}"
        // console.log(data)
       
    });
</script>

@endpush