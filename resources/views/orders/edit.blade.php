@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-xl sm:text-2xl font-bold mb-6">Edit Pesanan</h1>

    <form action="{{ route('orders.update', $order->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium text-sm text-gray-700">Nama</label>
            <input type="text" value="{{ $order->nama }}" class="w-full px-4 py-2 border rounded bg-gray-100" readonly>
        </div>

        <div>
            <label class="block font-medium text-sm text-gray-700">Meja</label>
            <input type="text" value="{{ $order->meja }}" class="w-full px-4 py-2 border rounded bg-gray-100" readonly>
        </div>

        <div>
            <label class="block font-medium text-sm text-gray-700">Status Pembayaran</label>
            <select name="payment_status" class="w-full px-4 py-2 border rounded">
                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>

        <div>
            <label class="block font-medium text-sm text-gray-700">Status Pesanan</label>
            <select name="status_order" class="w-full px-4 py-2 border rounded">
                <option value="dipesan" {{ $order->status_order === 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                <option value="diterima" {{ $order->status_order === 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="diproses" {{ $order->status_order === 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ $order->status_order === 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('orders.index') }}" class="mr-2 px-4 py-2 bg-gray-300 rounded text-sm hover:bg-gray-400">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
