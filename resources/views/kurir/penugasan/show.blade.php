@extends('layouts.kurir')

@section('title', 'Detail Paket')

@section('content')

<div class="mb-6">
    <a href="{{ route('kurir.penugasan.index') }}"
        class="text-sm font-medium text-slate-500 hover:text-[#0A3656]">
        ← Kembali ke Penugasan
    </a>

    <h1 class="mt-3 text-2xl font-bold text-[#0A3656]">
        Detail Paket
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Informasi lengkap paket dengan nomor resi {{ $paket->no_resi }}.
    </p>
</div>

<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    <div class="mb-6 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-5">
        <div>
            <p class="text-sm text-slate-500">Nomor Resi</p>
            <h2 class="mt-1 text-lg font-bold text-[#0A3656]">
                {{ $paket->no_resi }}
            </h2>
        </div>

        <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">
            {{ $paket->status }}
        </span>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <div>
            <h3 class="mb-4 font-bold text-[#0A3656]">
                Informasi Pengiriman
            </h3>

            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-slate-500">Tanggal Masuk</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $paket->tanggal_masuk }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Kota Tujuan</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $paket->kota_tujuan }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Layanan</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $paket->layanan }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Berat Tagih</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $paket->berat_tagih }} kg
                    </p>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-4 font-bold text-[#0A3656]">
                Informasi Penerima
            </h3>

            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-slate-500">Nama Penerima</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $paket->penerima_nama }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Nomor Telepon</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $paket->penerima_telepon }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Alamat Penerima</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $paket->penerima_alamat }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Nilai COD</p>
                    <p class="mt-1 font-medium text-slate-800">
                        Rp {{ number_format($paket->nilai_cod ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>


        @if ($paket->status === 'Dikirim')

    <div class="mt-8 border-t border-slate-200 pt-6 md:col-span-2">

        <h3 class="mb-2 text-lg font-bold text-[#0A3656]">
            Aksi Pengiriman
        </h3>

        <p class="mb-5 text-sm text-slate-500">
            Perbarui status setelah melakukan proses pengiriman paket.
        </p>

        <form action="{{ route('kurir.penugasan.update-status', $paket->id) }}"
              method="POST">

            @csrf

            <div class="mb-5">
                <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                    Status Pengiriman
                </label>

                <select name="status" id="status"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-[#0A3656] focus:outline-none">

                    <option value="">Pilih Status</option>
                    <option value="Terkirim">Terkirim</option>
                    <option value="Gagal Kirim">Gagal Kirim</option>

                </select>

                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="catatan" class="mb-2 block text-sm font-medium text-slate-700">
                    Catatan Pengiriman
                </label>

                <textarea name="catatan" id="catatan" rows="3"
                          placeholder="Tuliskan catatan jika pengiriman gagal..."
                          class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-[#0A3656] focus:outline-none">{{ old('catatan') }}</textarea>

                @error('catatan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="rounded-lg bg-[#0A3656] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#164B70]">
                Simpan Status
            </button>

        </form>

    </div>

    @else

        <div class="mt-8 rounded-lg bg-slate-50 p-4 md:col-span-2">
            <p class="text-sm font-medium text-slate-600">
                Paket ini sudah memiliki status {{ $paket->status }}.
                Tidak ada perubahan status yang dapat dilakukan.
            </p>
        </div>

    @endif

    </div>

</div>

@endsection