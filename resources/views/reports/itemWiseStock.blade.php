@extends('layouts.dashboard')
@section('content')

@include('reports.itemWiseSearch')

@if(isset($transactions))

<section class="table-responsive-md rounded mb-5">
    <table class="table table-striped table-borderless border" id="example">
        <thead>
            <tr>
                <th>Date</th>
                <th>Party Name</th>
                <th>Op Stock</th>
                <th>Receive(DR)</th>
                <th>Send(CR)</th>
                <th>Waste</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @php
            $party_id = request('party_id');
            $balance = 0;
            $prev_party_id = 0;
            $oldid = '';
            @endphp
            @if(!empty($transactions))
            @foreach ($transactions as $k=>$transaction)
            <?php



            $showBalance = 0;
            $mainbalance = 0;
            $i = 0;
            ?>
            @foreach ($transaction as $k=>$tr)
            <?php
            $showOP = false;
            if (!empty($tr->party->id) && $tr->party->id != $oldid) {
                $showOP = true;
                $oldid = $tr->party->id;
            }
            $waste = abs((float)$tr->waste * (float)$tr->quantity / 100);
            if ( $showOP == true && $mainbalance == 0) {
                $mainbalance = (float)$tr->openingQty ?? 0;
                $showBalance = $mainbalance + (float)$tr->quantity - (float)$waste;
            }elseif($showOP == false){
                $mainbalance = (float)$showBalance;
                if ($tr->type == 'receive' || $tr->type == 'purchase') {
                    $showBalance = (float)$mainbalance + (float)$tr->quantity - (float)$waste;
                } elseif ($tr->type == 'sale' || $tr->type == 'transfer' || $tr->type == 'used') {
                    $showBalance = (float)$mainbalance + (float)$tr->quantity - (float)$waste;
                }
            }
            ?>
            <tr>
                <td>  {{$tr->created_at ? $tr->created_at->format('d M, Y') : ''}}</td>
                <!-- <td>
                    @if($tr->worker)
                        {{$tr->worker->name}}
                    @elseif($tr->party)
                    {{$tr->party->name}}
                    @endif
                </td> -->
                <td>
                        @if($tr->worker)
                        {{$tr->worker->name}}
                        @elseif($tr->party)
                        {{$tr->party->name}}
                        @endif

                    <span class="badge ms-1 {{$tr->type == 'receive'|| $tr->type == 'purchase' || $tr->type == 'return' ? 'bg-success' : 'bg-primary'}}">
                        @if($tr->type == 'receive'|| $tr->type == 'purchase' || $tr->type == 'return')
                            Receive
                        @elseif($tr->type == 'sale'  || $tr->type == 'transfer'  || $tr->type == 'used')
                            Send
                        @endif
                    </span>

                        <?php
                        $itemName = \Illuminate\Support\Facades\DB::table('items')->select('id', 'name')->where('id', $tr->item_id)->first();
                        ?>
                    <p style="max-width:24rem;" class="overflow-auto mb-1">
                        <a href="{{route('transfer-transactions.index', ['item_id' => $itemName->id, 'party_id' => $tr->party->id])}}"> {{$itemName->name ?? ''}} </a>
                    </p>

                </td>

                <td>{{ !empty($tr->openingQty) && $showOP == true ? $tr->openingQty : '-'}}</td>
                <td>{{$tr->type == 'receive'|| $tr->type == 'purchase' || $tr->type == 'return' ? abs($tr->quantity) : '0'}}</td>
                <td>{{$tr->type == 'sale'  || $tr->type == 'transfer'  || $tr->type == 'used' ? abs($tr->quantity) : '0'}}</td>
                <td>{{abs((int)$tr->waste * (int)$tr->quantity / 100)}}</td>
                <td>{{($showBalance)}}  </td>
            </tr>
            @endforeach
            @endforeach
            @endif
        </tbody>
    </table>

</section>
@endif

@endsection

@push('scripts')
<script>
    $(document).ready(() => {
        $('select').selectize();
        $('#example').DataTable({
            // data: name,
            deferRender: true,
            // scrollY:        200,
            scrollCollapse: true,
            scroller: true,
            info: false,
            "bPaginate": false
        });
    });
</script>
@endpush
