<a href="{{ route('salary-vouchers.show', ['voucher' => $voucher]) }}" class="btn btn-sm text-primary"><i class="fa fa-eye"></i></a>

{{--<a href="{{ route('salary-vouchers.edit', ['voucher' => $voucher]) }}" 
    class="btn btn-sm text-primary"><i class="fa fa-cog"></i></a>--}}

<form class="d-inline-block" action="{{ route('salary-vouchers.destroy', ['voucher' => $voucher]) }}" method="POST"
 onsubmit="return confirm('Are You Sure?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm"><i class="fa fa-trash-alt text-primary"></i></button>
</form>