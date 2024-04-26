@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>Add Sale/Purchase Order</h5>
    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{route('direct.sale.store')}}" method="POST">
    @csrf
    <section class="row">
        <div class="col-md-6 mb-3">
            <label for="" class="form-label">Order No.</label>
            <input type="text" value="{{$ordeCount + 1}}" name="order_number" class="form-control" readonly></input>
        </div>
        <div class="col-md-6 mb-3">
            <label for="" class="form-label">Order Type</label>
            <select name="type" id="orderType" class="form-control" required>
                <option selected value="">Choose...</option>
                <option value="sale">Sale</option>
                <option value="purchase">Purchase</option>
            </select>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <label for="" class="form-label">Order Date</label>
            <input type="date" class="form-control" name="order_date" value="" required>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <label for="" class="form-label">Date</label>
            <input type="date" class="form-control" name="due_date" value="" required>
        </div>

        <div class="col-md-12 col-lg-6 mb-3">
            <label for="" class="form-label">Party</label>
            <div class="input-group">
                <span class="input-group-text">DR | CR </span>
                <select name="party_id" id="party" class="form-control" required>
                    <option selected value="">Choose...</option>
                    @foreach($parties as $id => $party)
                    <option value="{{ $id }}">
                        {{ $party }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>
    @include('orders.order-items')
    <div id="transportation" style="display: none;">
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
    </div>

    <footer class="d-flex justify-content-between mt-3 mt-lg-0 mb-4">
        <button class="btn btn-primary" id="add-row">
            <span class="fa fa-plus"></span>
        </button>
        <button class="btn btn-danger" id="remove-row">
            <i class="fa fa-times"></i>
        </button>

    </footer>

    <div class="mb-3">
        <label for="" class="form-label">Narration</label>
        <textarea name="narration" id="narration" cols="30" rows="5" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">whatsApp Narration</label>
        <textarea name="wa_narration" id="wa_narration" cols="30" rows="5" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary mb-5">Save</button>


    @push('scripts')

    <script>
        function enableSelectize() {
            $('table#order tbody').find('select').selectize({
                sortField: 'text'
            });
        }

        $(document).ready(() => {

            $('select').selectize();

            $('table#order tbody').on('input', `input[name='quantities[]']`, function() {

                let rateEl = $(this).parent().parent().find(`input[name='rates[]']`);

                rateEl.val() ? rateEl.val() : rateEl.val(0);

                let total = $(this).val() * rateEl.val();

                $(this).parent().parent().find(`input[name='total_prices[]']`).val(total.toFixed(2));

            });


            $('table#order tbody').on('input', `input[name='rates[]']`, function() {

                let quantityEl = $(this).parent().parent().find(`input[name='quantities[]']`);

                quantityEl.val() ? quantityEl.val() : quantityEl.val(0);

                let total = $(this).val() * quantityEl.val();

                $(this).parent().parent().find(`input[name='total_prices[]']`).val(total.toFixed(2));

            });

            $('#add-row').click(function(e) {

                e.preventDefault();

                $('table#order tbody tr:last').find('select').each(function(el) {
                    let value = $(this).val();
                    $(this)[0].selectize.destroy();
                    $(this).val(value);
                });

                $('table#order tbody tr:last').clone()
                    .appendTo('table#order tbody')
                    .find('[name]').val('');
                var remove_received = "{{!empty($order) ? '1' : '0' }}";
                if (remove_received == '1') {
                    $('table#order tbody tr:last').find('td').eq(1).html('');
                    $('table#order tbody tr:last').find('td').eq(2).find('input').attr('min', 0);
                }
                enableSelectize();
            });


            // remove last row
            $('#remove-row').on('click', (e) => {
                e.preventDefault();
                if ($('table#order tbody tr:last').find('td').eq(2).find('input').attr('min') == 0) {
                    if ($('.existitem').length > 1) {
                        $('table#order tbody tr:last').remove();
                    } else {
                        alert('Atleast one item is required');
                    }
                } else {
                    alert('You cannot remove item in process');
                }
            });
            $(document).on('click', '.remove-row-selected', function(e) {
                e.preventDefault();
                if ($(this).parent().parent().find('td').eq(2).find('input').attr('min') == 0) {
                    if ($('.existitem').length > 1) {
                        $(this).parent().parent().parent().append('<input type="hidden" name="deleted_items[]" value="' + $(this).data('id') + '">');
                        $(this).parent().parent().remove();
                    } else {
                        alert('Atleast one item is required');
                    }
                } else {
                    alert('You cannot remove item in process');
                }
            });
            $(document).on('change', '.checkmin', function(e) {
                if (parseInt($(this).attr('min')) > parseInt($(this).val())) {
                    alert('You have to select atleast ' + $(this).attr('min'));
                    $(this).val('');
                }
            });


        });

        $(document).ready(function() {
            // Event delegation to handle change on dynamically added rows
            $(document).on('change', 'select.section', function() {
                // Reset previous error messages
                // $(this).closest('.error-container').find('.error-message').remove();

                // $('.error-message').remove();

                // Get all selected values
                var selectedValues = $('select.section').map(function() {
                    return $(this).val();
                }).get();

                // Check for duplicate values
                var hasDuplicates = new Set(selectedValues).size !== selectedValues.length;

                if (hasDuplicates) {
                    alert("Duplicate values are not allowed.");

                    // $(this).closest('.error-container').append('<div class="error-message">Duplicate values are not allowed.</div>');
                }
            });
        });

        $('#orderType').on('change', function() {
            var selectedValue = $(this).val();
            
            if(selectedValue == 'sale'){
                $('#transportation').css('display', 'block');
            }else{
                $('#transportation').css('display', 'none');
            }
        });
    </script>

    @endpush

</form>
@endsection