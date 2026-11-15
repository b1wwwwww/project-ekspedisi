<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Pelanggan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4">
                    Daftar Pelanggan
                </h3>

                <form action="{{ route('pelanggan.index') }}" method="GET" class="mb-4 flex gap-2">
                    <input 
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama atau nomor telepon..."
                        class="border-gray-300 rounded-md w-full"
                        >

                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                        >
                        Cari
                    </button>

                    <a
                        href="{{ route('pelanggan.index') }}"
                        class="bg-gray-200 px-4 py-2 rounded-md hover:bg-gray-300"
                        >
                        Reset
                    </a>
                </form>

                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">ID</th>
                            <th class="border p-2">Nama</th>
                            <th class="border p-2">Telepon</th>
                            <th class="border p-2">Alamat</th>
                            <th class="border p-2">Total Kiriman</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pelanggan as $item)
                            <tr>
                                <td class="border p-2">{{ 'CUST-' . str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="border p-2">{{ $item->nama }}</td>
                                <td class="border p-2">{{ $item->telepon }}</td>
                                <td class="border p-2">
                                    {{ $item->alamat ?? '-' }}
                                </td>
                                <td class="border p-2">{{ $item->paket_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border p-3 text-center">
                                    Belum ada data pelanggan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $pelanggan->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>