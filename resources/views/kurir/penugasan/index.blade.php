@extends('layouts.kurir')

@section('title', 'Penugasan Paket')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#0A3656]">
        Penugasan Paket
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Daftar paket yang ditugaskan kepada Anda.
    </p>
</div>

<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="mb-5">
        <h2 class="text-base font-bold text-[#0A3656]">
            Daftar Paket
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Berikut paket yang perlu Anda tangani.
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">No. Resi</th>
                    <th class="px-4 py-3">Penerima</th>
                    <th class="px-4 py-3">Kota Tujuan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse ($paket as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-4 font-semibold text-[#0A3656]">
                            {{ $item->no_resi }}
                        </td>

                        <td class="px-4 py-4">
                            {{ $item->penerima_nama }}
                        </td>

                        <td class="px-4 py-4">
                            {{ $item->kota_tujuan }}
                        </td>

                        <td class="px-4 py-4">
                            {{ $item->status }}
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                @if ($item->status === 'Dikirim')
                                    <form action="{{ route('kurir.penugasan.update-status', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah paket ini sudah berhasil diterima?')">
                                        @csrf
                                        <input type="hidden" name="status" value="Terkirim">
                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-700">
                                            Tandai Terkirim
                                        </button>
                                    </form>

                                    <form action="{{ route('kurir.penugasan.update-status', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah pengiriman paket ini gagal?')">
                                        @csrf
                                        <input type="hidden" name="status" value="Gagal Kirim">
                                        <input type="hidden" name="catatan" value="Pengiriman gagal, perlu penjadwalan ulang.">
                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-700">
                                            Gagal Kirim
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('kurir.penugasan.show', $item->id) }}"
                                   class="inline-flex min-w-[105px] items-center justify-center rounded-lg border border-[#0A3656] bg-white px-3 py-2 text-xs font-semibold text-[#0A3656] transition hover:bg-[#0A3656] hover:text-white">
                                    Lihat Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-400">
                            Belum ada paket yang ditugaskan kepada Anda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection