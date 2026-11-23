import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import type { Observable } from 'rxjs';

import { environment } from '../../environments/environment';

const TOKEN_KEY = 'kurir_token';
const API_URL_KEY = 'kurir_api_url';

export interface LoginResponse {
    // Token Sanctum dipakai untuk mengakses endpoint yang memerlukan login.
    token: string;
    // Menandakan apakah kurir harus mengganti password awal.
    wajib_ganti_password: boolean;
    // Identitas kurir yang dikirim server setelah login berhasil.
    kurir: { id: number; kode_kurir: string; nama: string };
}

export interface Paket {
    // ID database dan nomor resi paket.
    id: number;
    no_resi: string;
    // Informasi penerima yang dibutuhkan pada daftar tugas.
    penerima_nama: string;
    penerima_alamat: string;
    kota_tujuan: string;
    // Layanan, nilai COD, status saat ini, dan jumlah percobaan gagal.
    layanan: string;
    nilai_cod: number;
    status: string;
    jumlah_gagal: number;
}

export interface PaketDetail extends Paket {
    // Data tambahan yang hanya ditampilkan pada halaman detail paket.
    penerima_telepon: string;
    pengirim: string;
    // Riwayat perubahan status dari server, berurutan berdasarkan waktu.
    riwayat: { status: string; catatan: string | null; waktu: string }[];
}

export interface CodBelumDisetor {
    // Jumlah uang COD dan jumlah paket yang belum dicatat setorannya.
    total_belum_disetor: number;
    jumlah_paket: number;
    // Rincian paket COD belum disetor milik kurir yang sedang login.
    data: { id: number; no_resi: string; penerima_nama: string; kota_tujuan: string; nilai_cod: number }[];
}

// Menyediakan satu pintu untuk penyimpanan token dan komunikasi dengan API Kurir.
@Injectable({
    providedIn: 'root',
})
export class Api {
    // HttpClient mengirim permintaan HTTP ke server Laravel.
    private http = inject(HttpClient);
    // Alamat API disimpan di perangkat supaya ganti jaringan tidak perlu build APK ulang.
    private get baseUrl(): string {
        return this.getApiUrl();
    }

    // Gunakan alamat tersimpan; bila belum diatur, gunakan nilai bawaan lingkungan.
    getApiUrl(): string {
        // Pakai alamat tersimpan; bila belum ada, ambil alamat bawaan lingkungan.
        return localStorage.getItem(API_URL_KEY) ?? environment.apiUrl;
    }

    // Simpan alamat yang sudah dinormalisasi agar akhiran endpoint selalu konsisten.
    setApiUrl(value: string): boolean {
        // Rapikan dan periksa alamat sebelum menyimpannya.
        const normalizedUrl = this.normalizeApiUrl(value);

        // Jangan mengganti alamat tersimpan jika nilai baru tidak valid.
        if (!normalizedUrl) {
            return false;
        }

        // Simpan alamat pada perangkat agar tetap tersedia setelah aplikasi ditutup.
        localStorage.setItem(API_URL_KEY, normalizedUrl);

        return true;
    }

    // Terima URL HTTP(S) dengan path kosong atau /api, lalu pastikan hasil berakhir /api.
    private normalizeApiUrl(value: string): string | null {
        let parsedUrl: URL;

        try {
            // URL bawaan browser memeriksa format alamat dan memisahkan komponennya.
            parsedUrl = new URL(value.trim());
        } catch {
            // Alamat yang tidak dapat dibaca sebagai URL tidak boleh digunakan.
            return null;
        }

        // Hilangkan garis miring akhir agar pemeriksaan path konsisten.
        const path = parsedUrl.pathname.replace(/\/+$/, '');

        // Batasi protokol dan path, serta tolak kredensial, query, dan fragmen URL.
        if (
            !['http:', 'https:'].includes(parsedUrl.protocol) ||
            parsedUrl.username !== '' ||
            parsedUrl.password !== '' ||
            parsedUrl.search !== '' ||
            parsedUrl.hash !== '' ||
            (path !== '' && path !== '/api')
        ) {
            return null;
        }

        // Semua endpoint aplikasi berada di bawah prefix /api.
        return `${parsedUrl.origin}/api`;
    }

    // Token disimpan lokal dan dilampirkan otomatis oleh interceptor pada request terproteksi.
    getToken(): string | null {
        // Ambil token login yang tersimpan untuk dipakai interceptor.
        return localStorage.getItem(TOKEN_KEY);
    }
    setToken(token: string): void {
        // Simpan token yang diberikan server setelah login berhasil.
        localStorage.setItem(TOKEN_KEY, token);
    }
    clearToken(): void {
        // Hapus token saat logout atau ketika server menolak autentikasi.
        localStorage.removeItem(TOKEN_KEY);
    }
    isLoggedIn(): boolean {
        // Pengguna dianggap login jika token masih tersimpan.
        return !!this.getToken();
    }

    // --- Auth ---
    // Kirim kode kurir dan password untuk meminta token akses dari server.
    login(kode_kurir: string, password: string): Observable<LoginResponse> {
        return this.http.post<LoginResponse>(`${this.baseUrl}/login`, { kode_kurir, password });
    }
    // Hapus token aktif melalui endpoint logout server.
    logout(): Observable<{ message: string }> {
        return this.http.post<{ message: string }>(`${this.baseUrl}/logout`, {});
    }
    // Kirim password lama dan baru untuk memperbarui password kurir.
    gantiPassword(password_lama: string, password_baru: string, password_baru_confirmation: string): Observable<{ message: string }> {
        return this.http.post<{ message: string }>(`${this.baseUrl}/ganti-password`, {
        password_lama,
        password_baru,
        password_baru_confirmation,
        });
    }

    // --- Paket ---
    // Ambil daftar paket yang sedang ditugaskan kepada kurir.
    getPaketList(): Observable<{ data: Paket[] }> {
        return this.http.get<{ data: Paket[] }>(`${this.baseUrl}/paket`);
    }
    // Ambil data lengkap dan riwayat satu paket berdasarkan ID.
    getPaketDetail(id: number | string): Observable<{ data: PaketDetail }> {
        return this.http.get<{ data: PaketDetail }>(`${this.baseUrl}/paket/${id}`);
    }
    // Kirim perubahan status paket dan catatan pengiriman ke server.
    updateStatusPaket(id: number | string, status: string, catatan?: string): Observable<{ message: string; data: Paket }> {
        return this.http.patch<{ message: string; data: Paket }>(`${this.baseUrl}/paket/${id}/status`, {
        status,
        catatan,
        });
    }

    // --- COD ---
    // Ambil daftar serta total COD terkirim yang belum dicatat setorannya.
    getCodBelumDisetor(): Observable<CodBelumDisetor> {
        return this.http.get<CodBelumDisetor>(`${this.baseUrl}/cod/belum-disetor`);
    }
}