

<a href="{{ route('orders.show', ['order' => $order]) }}" title='View' class="btn btn-sm text-primary"><i class="fa fa-eye"></i></a>

@can('update', $order)
@if($direct != 1)
<a href="{{ route('orders.transfer.create', ['order' => $order]) }}" title='Transfer' class="btn btn-sm text-primary">
    <i class="fa fa-shopping-cart"></i>
</a>
@endif
@endcan

@can('update', $order)
<a href="{{ route('orders.edit', ['order' => $order]) }}" title='Edit' class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>
@endcan
<a href="{{ route('orders.transfer.create', ['order' => $order,'return' => true]) }}" title='Return' class="btn btn-sm text-primary"><i class="fa fa-undo"></i></a>
<?php  
$received_exist = DB::table('order_items')->where('order_id',$order->id)->where('received_quantity','>',0)->first(); 
?>
@if(empty($received_exist))
<form class="d-inline-block" action="{{ route('orders.destroy', ['order' => $order]) }}" method="POST"
onsubmit="return confirm('Are You Sure?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm"><i class="fa fa-trash text-primary"></i></button>
</form>
@else

@endif
<!-- @can('delete', $order)
@endcan -->