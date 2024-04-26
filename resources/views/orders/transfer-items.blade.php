<section class="table-responsive rounded mt-lg-2 mb-4">
    <table class="table table-bordered" id="transfer" style="min-width: 50rem;">
        <thead>
            <tr>
                <th>Item</th>
                <th>Rate</th>
                <th>Quantity (Ordered)</th>
                <th>Quantity ({{ $order->type == App\Models\Order::SALE ? 'Sent' : 'Received' }})</th>
                <th>{{ ucfirst($order->type) }} Quantity ( @if($isreturn) Return @else Current @endif)</th>
                @if($order->type == \App\Models\Order::PURCHASE)
                <th>Transfer To</th>
                @endif
            </tr>
        </thead>
        <tbody>
        @if($isreturn)
        <input type="hidden" name="type" value="return">
        @endif
            @foreach ($order->orderItems as $orderItem)
            <tr>
                <td style="min-width: 10rem;">{{ $orderItem->item->name ?? "" }}</td>
                <td>{{ $orderItem->rate }}</td>
                <td>{{ $orderItem->ordered_quantity }}</td>
                <td>{{ $orderItem->received_quantity }}</td>
                <td>
                    <div class="input-group">
                          <input type="hidden" name="rate[]" value="{{ $orderItem->rate ?? '' }}" class="form-check-input">
                        <div class="input-group-text">
                          <input type="checkbox" name="items_id[]" value="{{ $orderItem->id }}" class="form-check-input">
                        </div>
                        @if($isreturn)
                        <input type="text" name="current_quantities[]" class="form-control"
                        data-item-id="{{ $orderItem->item_id }}"
                        data-item-rate="{{ $orderItem->rate }}"
                        min="1"
                        max="{{$orderItem->received_quantity ?? ''}}"
                         disabled>
                        @else
                        {{-- receiving or sending quantity --}}
                        <input type="text" name="current_quantities[]" class="form-control"
                        data-item-id="{{ $orderItem->item_id }}"
                        data-item-rate="{{ $orderItem->rate }}"
                        min="1" max={{ $orderItem->ordered_quantity - $orderItem->received_quantity }}
                         disabled>
                         @endif
                    </div>
                </td>
                @if($order->type == \App\Models\Order::PURCHASE)
                    <td width="30%">
                        <div class="input-group">
                            <span class="input-group-text">Party</span>
                            <select name="transfer_to" class="form-control" required>
                                <option disabled selected>Choose...</option>
                                @foreach($parties as $party)
                                    <option value="{{$party->id}}">{{$party->name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </td>
                @endif
            </tr>
            @if ($loop->last)
                <tr class="fw-bold">
                    <td colspan="3"></td>
                    <td>Amount</td>
                    <td>
                        <input type="number" step="0.001" readonly name="amount" value="0" class="form-control" required>
                    </td>
                </tr>
                <tr class="fw-bold">
                    <td colspan="3"></td>
                    <td>GST Amount</td>
                    <td>
                        <input type="number" step="0.001" name="gst_amount" value="0" class="form-control" required>
                    </td>
                </tr>
                <tr class="fw-bold">
                    <td colspan="3"></td>
                    <td>Extra Charges</td>
                    <td>
                        <input type="number" step="0.001" name="extra_charges" value="0" class="form-control" required>
                    </td>
                </tr>
                <tr class="fw-bold">
                    <td colspan="3"></td>
                    <td>Payment Amount</td>
                    <td>
                        <input type="number" step="0.001" readonly name="payment_amount" value="0" class="form-control" required>
                    </td>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</section>
