@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<table>
    <thead>
      <tr>
        <th>No</th><th>Nama</th><th>Tanggal</th><th>Total Harga</th>
      </tr>
    </thead>
    <tbody>
      @foreach($orders as $order)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $order->nama_pemesan }}</td>
        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
        <td>{{ number_format($order->total_harga,0,',','.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  

@endsection
