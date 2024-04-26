@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
  <h5><b> Update Logs </b> - Order Number : {{$id}}</h5>
</header>


<section class="table-responsive-sm rounded mb-5">
  <table class="table table-striped table-borderless border" id="orders">
    <thead>
      <tr>
        <th>#</th>
        <th>Updated By</th>
        <th>Updated Time</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($logs as $value => $log)
            <tr>
                <td>{{++$value}}</td>
                <td>{{$log->user->username}}</td>
                <td>{{date('d M, Y (h:i A)',strtotime($log->created_at))}}</td>
            </tr>
        @endforeach
    </tbody>
  </table>
</section>

@endsection
