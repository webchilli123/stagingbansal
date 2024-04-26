<section class="table-responsive-lg rounded mt-lg-3">
    <table class="table table-bordered" id="order" style="min-width: 50rem;">
        <thead>
            <tr>
                <th>Item</th>
                @if (isset($order))
                <th>Received</th>
                @endif
                <th>
                    Total Quantity (Kg)</th>
                <th>Rate</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($order))
                @foreach ($order->orderItems as $k => $orderItem)
                <tr>
                    <input type="hidden" name="old_id[]" value="{{ $orderItem->id ?? ''}}" >
                    <td style="min-width: 18rem;" class='existitem'>
                        <select name="items[]" id="section" class="form-control section">
                            <option value="" selected>Choose...</option>
                            @foreach ($items as $key => $item)
                            <option value="{{ $key }}" {{ $key == $orderItem->item_id  ? 'selected' : ''}}>
                                {{ $item }}
                            </option>
                            @endforeach
                        </select>
                        <div class="error-container">
                        </div>
                    </td>
                    <td>{{$orderItem->received_quantity ?? 0}}</td>
                    <td>
                        <?php
                        $left_quantity_min = (int)$orderItem->ordered_quantity - (int)$orderItem->received_quantity;
                        $left_quantity = (int)$orderItem->ordered_quantity;
                          ?>
                        <input type="number" name="quantities[]" min='{{ $orderItem->received_quantity ?? 0 }}' value="{{ $left_quantity ?? 0 }}" step=".01" class="form-control checkmin" required>
                    </td>
                    <td>
                        <input type="number" name="rates[]" value="{{ $orderItem->rate }}" step=".0001" class="form-control" required>
                    </td>
                    <td class='d-flex'>
                        <input type="number" name="total_prices[]" value="{{ $orderItem->total_price }}" step=".0001" class="form-control" required>
                        @if($orderItem->received_quantity == 0)
                        <button class="btn btn-danger remove-row-selected" data-id='{{$orderItem->id ?? ""}}'  >
                            <i class="fa fa-times"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td style="min-width: 18rem;">
                        <select name="items[]" id="section" class="form-control section">
                            <option value="" selected>Choose...</option>
                            @foreach ($items as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>

                    </td>
                    <td>
                        <input type="number" name="quantities[]" step=".01" class="form-control" required>
                    </td>
                    <td>
                        <input type="number" name="rates[]" step=".0001" class="form-control" required>
                    </td>
                    <td>
                        <input type="number" name="total_prices[]" step=".0001" class="form-control" required>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</section>
