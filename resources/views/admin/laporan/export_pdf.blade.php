@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

  <h2>Laporan Order ({{ ucfirst($range) }})</h2>
  <p>{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
  <table>
    <thead>
      <tr><th>No</th><th>Nama</th><th>Tanggal</th><th>Total Harga</th></tr>
    </thead>
    <tbody>
      @foreach($orders as $order)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $order->nama_pemesan }}</td>
        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
        <td>Rp {{ number_format($order->total_harga,0,',','.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body></html>

@endsection
