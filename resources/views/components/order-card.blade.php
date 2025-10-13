<div class="mb-6 bg-white border border-gray-200 rounded-xl shadow-md p-5 space-y-4 text-sm sm:text-base transition hover:shadow-lg">
    {{-- Form update status --}}
    <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
        @csrf
        @method('PUT')

        <div class="flex items-center justify-between mb-3">
            <span class="font-semibold text-gray-700">Order #{{ $order->id }} - {{ $order->nama }}</span>

            {{-- ✅ Jika status masih "dipesan", tampilkan tombol tandai diterima --}}
            @if ($order->status_order === 'dipesan')
                <input type="hidden" name="status_order" value="diterima">
                <button type="submit"
                    class="px-4 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition font-medium text-sm">
                    Tandai Diterima
                </button>

            {{-- ✅ Jika status sudah diterima, tampilkan tombol Cetak Struk --}}
            @elseif ($order->status_order === 'diterima')
                <div class="flex items-center gap-2">
                    <button disabled
                        class="px-4 py-2 bg-gray-400 text-white rounded-lg text-sm cursor-not-allowed"
                        title="Sudah diterima">
                        ✅ Sudah Diterima
                    </button>

                    {{-- Tombol Cetak Struk --}}
                    <a href="{{ route('orders.print', $order->id) }}"
                       target="_blank"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                       🧾 Cetak Struk
                    </a>
                </div>
            @endif
        </div>

        {{-- Detail order --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
                <p><span class="font-semibold text-gray-600">Nama:</span> {{ $order->nama }}</p>
                <p><span class="font-semibold text-gray-600">Alamat:</span> #{{ $order->detail_alamat }}</p>
                <p><span class="font-semibold text-gray-600">Metode:</span> {{ ucfirst($order->payment_method) }}</p>
                <p><span class="font-semibold text-gray-600">Midtrans ID:</span> {{ $order->midtrans_order_id ?? '-' }}</p>
            </div>
            <div class="space-y-1">
                <p><span class="font-semibold text-gray-600">Status Bayar:</span> {{ ucfirst($order->payment_status) }}</p>
            </div>
        </div>
    </form>

    {{-- Tabel produk --}}
    <div class="overflow-x-auto mt-3">
        <table class="w-full text-sm border border-gray-200 rounded-lg">
            <thead class="bg-amber-100 text-amber-700 font-semibold">
                <tr>
                    <th class="p-2 border text-left">Produk</th>
                    <th class="p-2 border text-center">Qty</th>
                    <th class="p-2 border text-right">Harga</th>
                    <th class="p-2 border text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-2 border">{{ $item->produk }}</td>
                        <td class="p-2 border text-center">{{ $item->quantity }}</td>
                        <td class="p-2 border text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="p-2 border text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Total --}}
    <div class="flex justify-between items-center mt-3 border-t pt-3 text-sm sm:text-base font-semibold text-gray-700">
        <span>Total:</span>
        <span>
            Rp {{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}
        </span>
    </div>

    {{-- Hapus --}}
    <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
        onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');" class="mt-3 text-right">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium text-sm">
            Hapus
        </button>
    </form>
</div>
