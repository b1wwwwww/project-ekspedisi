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
                                          method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Terkirim">
                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-700">
                                            Tandai Terkirim
                                        </button>
                                    </form>

                                    <button type="button"
                                            onclick="bukaModalGagalKirim('{{ route('kurir.penugasan.update-status', $item->id) }}', '{{ $item->no_resi }}')"
                                            class="inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-700">
                                        Gagal Kirim
                                    </button>
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

<div id="modal-gagal-kirim" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-800">Konfirmasi Gagal Kirim</h2>
            <p class="mt-1 text-sm text-slate-500">Paket <span id="gagal-kirim-resi" class="font-semibold text-[#0A3656]"></span></p>
        </div>

        <form id="form-gagal-kirim" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="status" value="Gagal Kirim">

            <div>
                <label for="catatan-gagal-kirim" class="mb-2 block text-sm font-semibold text-slate-700">
                    Alasan gagal kirim <span class="text-red-600">*</span>
                </label>
                <textarea id="catatan-gagal-kirim" name="catatan" rows="4" required maxlength="1000"
                          placeholder="Contoh: Penerima tidak berada di alamat saat pengiriman"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-red-500 focus:ring-2 focus:ring-red-100"></textarea>
                <p id="gagal-kirim-error" class="mt-1 hidden text-xs font-medium text-red-600">Alasan gagal kirim wajib diisi.</p>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="tutupModalGagalKirim()" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">Batal</button>
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Konfirmasi Gagal Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.bukaModalGagalKirim = function (actionUrl, nomorResi) {
        const modal = document.getElementById('modal-gagal-kirim');
        const form = document.getElementById('form-gagal-kirim');
        form.action = actionUrl;
        document.getElementById('gagal-kirim-resi').textContent = nomorResi;
        document.getElementById('catatan-gagal-kirim').value = '';
        document.getElementById('gagal-kirim-error').classList.add('hidden');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('catatan-gagal-kirim').focus();
    };

    window.tutupModalGagalKirim = function () {
        const modal = document.getElementById('modal-gagal-kirim');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    document.getElementById('form-gagal-kirim').addEventListener('submit', function (event) {
        const catatan = document.getElementById('catatan-gagal-kirim');
        if (!catatan.value.trim()) {
            event.preventDefault();
            document.getElementById('gagal-kirim-error').classList.remove('hidden');
            catatan.focus();
        }
    });
</script>

@endsection