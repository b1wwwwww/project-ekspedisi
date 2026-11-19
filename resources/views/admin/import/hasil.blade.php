@extends('layouts.admin')

@section('title', 'Hasil Impor Pelanggan')

@section('content')
    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#0A3656]">Hasil Impor Pelanggan</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan file CSV yang baru diproses.</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @foreach ([
                'Pelanggan' => $hasilPelanggan,
                'Kurir' => $hasilKurir,
                'Paket historis' => $hasilPaketHistoris,
            ] as $namaData => $hasil)
                <section class="{{ $loop->first ? '' : 'mt-6 border-t border-slate-100 pt-6' }}">
                    <h2 class="text-lg font-bold text-[#0A3656]">{{ $namaData }}</h2>

                    <div class="mt-3 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Total baris data</p>
                            <p class="mt-1 text-2xl font-bold text-[#0A3656]">{{ $hasil['total'] }}</p>
                        </div>

                        <div class="rounded-lg bg-emerald-50 p-4">
                            <p class="text-sm text-emerald-700">Berhasil diimpor</p>
                            <p class="mt-1 text-2xl font-bold text-emerald-700">{{ $hasil['berhasil'] }}</p>
                        </div>
                    </div>

                    {{-- Alasan ditampilkan agar admin dapat memperbaiki baris CSV yang tidak masuk. --}}
                    @if ($hasil['dilewati'] !== [])
                        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4">
                            <h3 class="font-semibold text-amber-800">Baris yang dilewati</h3>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-amber-800">
                                @foreach ($hasil['dilewati'] as $alasan)
                                    <li>{{ $alasan }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (($hasil['peringatan'] ?? []) !== [])
                        <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-4">
                            <h3 class="font-semibold text-blue-800">Catatan impor</h3>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-blue-800">
                                @foreach ($hasil['peringatan'] as $peringatan)
                                    <li>{{ $peringatan }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </section>
            @endforeach

            <div class="mt-8 flex justify-end border-t border-slate-100 pt-5">
                <a href="{{ route('admin.import.index') }}" class="rounded-lg bg-[#0A3656] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#082b45]">
                    Impor File Lain
                </a>
            </div>
        </div>
    </div>
@endsection
