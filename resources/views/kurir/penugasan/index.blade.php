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
                                        <button type="button"
                                                onclick="bukaModalTerkirim(
                                                    '{{ route('kurir.penugasan.update-status', $item->id) }}',
                                                    '{{ addslashes($item->no_resi) }}'
                                                )"
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

                <label for="template-gagal-kirim"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Pilih Alasan <span class="text-red-600">*</span>
                </label>

                <select id="template-gagal-kirim"
                        class="mb-4 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-red-500 focus:ring-2 focus:ring-red-100">
                    <option value="">Pilih template alasan...</option>
                    <option value="Penerima tidak berada di alamat saat pengiriman.">
                        Penerima tidak berada di alamat
                    </option>
                    <option value="Nomor penerima tidak dapat dihubungi.">
                        Nomor penerima tidak dapat dihubungi
                    </option>
                    <option value="Alamat penerima tidak dapat ditemukan.">
                        Alamat penerima tidak ditemukan
                    </option>
                    <option value="Paket ditolak oleh penerima.">
                        Paket ditolak oleh penerima
                    </option>
                    <option value="Penerima meminta pengiriman ulang pada waktu lain.">
                        Pengiriman dijadwalkan ulang
                    </option>
                </select>

                <label for="catatan-gagal-kirim"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Alasan atau Catatan Tambahan <span class="text-red-600">*</span>
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

<div id="modal-terkirim"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-800">
                Konfirmasi Paket Terkirim
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Paket
                <span id="terkirim-resi" class="font-semibold text-[#0A3656]"></span>
            </p>
        </div>

        <form id="form-terkirim" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="status" value="Terkirim">

            <div>
                <label for="catatan-terkirim"
                        class="mb-2 block text-sm font-semibold text-slate-700">
                    Catatan Pengiriman
                </label>

                <select id="template-catatan-terkirim"
                        class="mb-2 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                    <option value="">Pilih template catatan...</option>
                    <option value="Paket berhasil diterima oleh penerima.">
                        Paket berhasil diterima oleh penerima
                    </option>
                    <option value="Paket telah diserahkan kepada penerima di lokasi tujuan.">
                        Diserahkan di lokasi tujuan
                    </option>
                    <option value="Paket diterima oleh keluarga penerima.">
                        Diterima oleh keluarga penerima
                    </option>
                    <option value="Paket diterima oleh petugas/security di lokasi.">
                        Diterima oleh petugas atau security
                    </option>
                </select>

                <textarea id="catatan-terkirim"
                            name="catatan"
                            rows="4"
                            maxlength="1000"
                            required
                            placeholder="Contoh: Paket diterima oleh penerima dengan baik"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100"></textarea>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button"
                        onclick="tutupModalTerkirim()"
                        class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">
                    Batal
                </button>

                <button type="submit"
                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                    Konfirmasi Terkirim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // MODAL STATUS GAGAL KIRIM
    window.bukaModalGagalKirim = function (actionUrl, nomorResi) {
        const modal = document.getElementById('modal-gagal-kirim');
        const form = document.getElementById('form-gagal-kirim');
        form.action = actionUrl;
        document.getElementById('gagal-kirim-resi').textContent = nomorResi;
        document.getElementById('catatan-gagal-kirim').value = '';
        document.getElementById('gagal-kirim-error').classList.add('hidden');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('template-gagal-kirim').focus();
        document.getElementById('template-gagal-kirim').value = '';
        document.getElementById('catatan-gagal-kirim').value = '';
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

    document.getElementById('template-gagal-kirim')
    .addEventListener('change', function () {
        if (this.value) {
            document.getElementById('catatan-gagal-kirim').value = this.value;
        }
    });



    // MODAL STATUS TERKIRIM

    window.bukaModalTerkirim = function (actionUrl, nomorResi) {
    const modal = document.getElementById('modal-terkirim');
    const form = document.getElementById('form-terkirim');
    const catatan = document.getElementById('catatan-terkirim');
    const template = document.getElementById('template-catatan-terkirim');

    form.action = actionUrl;
    document.getElementById('terkirim-resi').textContent = nomorResi;
    catatan.value = '';
    template.value = '';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    };

    window.tutupModalTerkirim = function () {
        const modal = document.getElementById('modal-terkirim');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    document.getElementById('template-catatan-terkirim')
        .addEventListener('change', function () {
            if (this.value) {
                document.getElementById('catatan-terkirim').value = this.value;
            }
        });

    document.getElementById('form-terkirim')
        .addEventListener('submit', function (event) {
            const catatan = document.getElementById('catatan-terkirim');

            if (!catatan.value.trim()) {
                event.preventDefault();
                catatan.focus();
            }
        });
</script>

@endsection