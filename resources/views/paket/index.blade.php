<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Paket
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">
                        Data Paket Ekspedisi
                    </h3>

                    <a href="{{ route('paket.create') }}"
                        class="px-4 py-2 bg-blue-600 text-black rounded-md">
                        + Tambah Paket
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">

                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-3">No</th>
                                <th class="border p-3">No. Resi</th>
                                <th class="border p-3">Penerima</th>
                                <th class="border p-3">Kota Tujuan</th>
                                <th class="border p-3">Berat Tagih</th>
                                <th class="border p-3">Ongkir</th>
                                <th class="border p-3">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- Digunakan untuk mengulang setiap data paket. --}}
                            @forelse ($paket as $index => $item)
                                <tr>
                                    <td class="border p-3 text-center">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $item->no_resi }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $item->penerima_nama }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $item->kota_tujuan }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $item->berat_tagih }} kg
                                    </td>

                                    <td class="border p-3">
                                        Rp{{ number_format($item->ongkir, 0, ',', '.') }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $item->status }}
                                    </td>
                                </tr>

                            {{-- Bagian ini ditampilkan jika belum ada paket di database. --}}
                            @empty
                                <tr>
                                    <td colspan="7" class="border p-4 text-center">
                                        Belum ada data paket.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>