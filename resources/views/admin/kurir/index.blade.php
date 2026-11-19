@extends(auth()->user()->role === 'manajer' ? 'layouts.manajer' : 'layouts.admin')

@section('title', 'Data Kurir')
@section('breadcrumb', 'Data Kurir')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#0A3656]">Data Kurir</h1>
                <p class="mt-1 text-sm text-slate-500">Pantau, edit, aktifkan, atau nonaktifkan akun Kurir.</p>
            </div>

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.kurir.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0A3656] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#082b45]">
                    <span class="text-lg leading-none">+</span>
                    Tambah Kurir
                </a>
            @endif
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-base font-bold text-[#0A3656]">Daftar Akun Kurir</h2>
            </div>

            <div class="p-5">
                <form action="{{ route('admin.kurir.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Cari nama, kode Kurir, atau nomor telepon..."
                           class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2] sm:w-96">
                    <button type="submit" class="rounded-lg bg-[#0A3656] px-5 py-2.5 text-sm font-semibold text-white">Cari</button>
                    <a href="{{ route('admin.kurir.index') }}"
                       class="rounded-lg bg-slate-100 px-5 py-2.5 text-center text-sm font-semibold text-slate-600">Reset</a>
                </form>
            </div>

            @if (session('success'))
                <div class="mx-5 mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Kode Kurir</th>
                            <th class="px-5 py-3 font-semibold">Nama</th>
                            <th class="px-5 py-3 font-semibold">Telepon</th>
                            <th class="px-5 py-3 text-center font-semibold">Jumlah Paket</th>
                            <th class="px-5 py-3 text-center font-semibold">Status</th>
                            @if (auth()->user()->role === 'admin')
                                <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($kurir as $item)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-4 font-bold text-[#0A3656]">{{ $item->kode_kurir }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $item->pengguna->nama }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $item->pengguna->telepon ?? '-' }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex min-w-8 justify-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-[#1D70A2]">
                                        {{ $item->paket_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if ($item->aktif)
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Nonaktif</span>
                                    @endif
                                </td>
                                @if (auth()->user()->role === 'admin')
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.kurir.edit', $item) }}"
                                               class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                                                Edit
                                            </a>

                                            <form id="form-status-{{ $item->id }}"
                                                  action="{{ route('admin.kurir.toggle-status', $item) }}"
                                                  method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button"
                                                        onclick="bukaModalStatusKurir('{{ $item->id }}', '{{ addslashes($item->kode_kurir) }}', {{ $item->aktif ? 'true' : 'false' }})"
                                                        class="rounded-lg px-3 py-2 text-xs font-semibold text-white transition {{ $item->aktif ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                                    {{ $item->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada akun Kurir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 p-4">
                {{ $kurir->links() }}
            </div>
        </div>
    </div>

    @if (auth()->user()->role === 'admin')
        <div id="modal-status-kurir"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <div class="mb-5 flex items-start gap-4">
                    <div id="status-kurir-icon" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                        <svg id="status-kurir-icon-svg" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z" />
                        </svg>
                    </div>
                    <div>
                        <h2 id="status-kurir-title" class="text-lg font-bold text-slate-800">Konfirmasi Status Kurir</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            <span id="status-kurir-message">Apakah Anda yakin ingin mengubah status akun Kurir ini?</span>
                            <span id="status-kurir-kode" class="block font-bold text-[#0A3656]"></span>
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                    <button type="button"
                            onclick="tutupModalStatusKurir()"
                            class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">
                        Batal
                    </button>
                    <button id="status-kurir-submit"
                            type="button"
                            onclick="konfirmasiStatusKurir()"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Nonaktifkan
                    </button>
                </div>
            </div>
        </div>

        <script>
            let formStatusKurirAktif = null;

            window.bukaModalStatusKurir = function (kurirId, kodeKurir, sedangAktif) {
                formStatusKurirAktif = document.getElementById('form-status-' + kurirId);

                const modal = document.getElementById('modal-status-kurir');
                const title = document.getElementById('status-kurir-title');
                const message = document.getElementById('status-kurir-message');
                const kode = document.getElementById('status-kurir-kode');
                const submit = document.getElementById('status-kurir-submit');
                const icon = document.getElementById('status-kurir-icon');

                kode.textContent = 'Kode Kurir: ' + kodeKurir;

                if (sedangAktif) {
                    title.textContent = 'Nonaktifkan Akun Kurir';
                    message.textContent = 'Kurir tidak lagi dapat mengakses akun dan menerima penugasan.';
                    submit.textContent = 'Ya, Nonaktifkan';
                    submit.className = 'rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700';
                    icon.className = 'flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600';
                } else {
                    title.textContent = 'Aktifkan Akun Kurir';
                    message.textContent = 'Kurir akan kembali dapat mengakses akun dan menerima penugasan.';
                    submit.textContent = 'Ya, Aktifkan';
                    submit.className = 'rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700';
                    icon.className = 'flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600';
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            window.tutupModalStatusKurir = function () {
                const modal = document.getElementById('modal-status-kurir');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                formStatusKurirAktif = null;
            };

            window.konfirmasiStatusKurir = function () {
                if (formStatusKurirAktif) {
                    formStatusKurirAktif.submit();
                }
            };

            document.getElementById('modal-status-kurir')?.addEventListener('click', function (event) {
                if (event.target === this) {
                    tutupModalStatusKurir();
                }
            });
        </script>
    @endif
@endsection
