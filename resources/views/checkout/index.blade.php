@extends('layouts.app')

@section('content')
<section class="max-w-4xl mx-auto p-6">
  <h2 class="text-xl font-semibold mb-6">Checkout</h2>

  @if(count($cart) > 0)
    <table class="w-full text-left border-collapse">
      <thead>
        <tr>
          <th class="pb-2 border-b">Produk</th>
          <th class="pb-2 border-b">Harga</th>
          <th class="pb-2 border-b">Jumlah</th>
          <th class="pb-2 border-b">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($cart as $id => $item)
        <tr>
          <td class="py-2">{{ $item['name'] }}</td>
          <td class="py-2">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
          <td class="py-2">{{ $item['quantity'] }}</td>
          <td class="py-2">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="font-semibold border-t">
          <td colspan="3" class="pt-4">Total</td>
          <td class="pt-4">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
      </tbody>
    </table>

    <div class="mt-6">
      <a href="#" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Lanjut Bayar</a>
    </div>
  @else
    <p class="text-center text-gray-500">Keranjang kamu kosong.</p>
  @endif
</section>
@endsection
