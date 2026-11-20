import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import type { Observable } from 'rxjs';

import { environment } from '../../environments/environment';

const TOKEN_KEY = 'kurir_token';

export interface LoginResponse {
    token: string;
    wajib_ganti_password: boolean;
    kurir: { id: number; kode_kurir: string; nama: string };
}

export interface Paket {
    id: number;
    no_resi: string;
    penerima_nama: string;
    penerima_alamat: string;
    kota_tujuan: string;
    layanan: string;
    nilai_cod: number;
    status: string;
    jumlah_gagal: number;
}

export interface PaketDetail extends Paket {
    penerima_telepon: string;
    pengirim: string;
    riwayat: { status: string; catatan: string | null; waktu: string }[];
}

export interface CodBelumDisetor {
    total_belum_disetor: number;
    jumlah_paket: number;
    data: { id: number; no_resi: string; penerima_nama: string; kota_tujuan: string; nilai_cod: number }[];
}

@Injectable({
    providedIn: 'root',
})
export class Api {
    private http = inject(HttpClient);
    private baseUrl = environment.apiUrl;

  // --- Token ---
    getToken(): string | null {
        return localStorage.getItem(TOKEN_KEY);
    }
    setToken(token: string): void {
        localStorage.setItem(TOKEN_KEY, token);
    }
    clearToken(): void {
        localStorage.removeItem(TOKEN_KEY);
    }
    isLoggedIn(): boolean {
        return !!this.getToken();
    }

    // --- Auth ---
    login(kode_kurir: string, password: string): Observable<LoginResponse> {
        return this.http.post<LoginResponse>(`${this.baseUrl}/login`, { kode_kurir, password });
    }
    logout(): Observable<{ message: string }> {
        return this.http.post<{ message: string }>(`${this.baseUrl}/logout`, {});
    }
    gantiPassword(password_lama: string, password_baru: string, password_baru_confirmation: string) {
        return this.http.post<{ message: string }>(`${this.baseUrl}/ganti-password`, {
        password_lama,
        password_baru,
        password_baru_confirmation,
        });
    }

    // --- Paket ---
    getPaketList(): Observable<{ data: Paket[] }> {
        return this.http.get<{ data: Paket[] }>(`${this.baseUrl}/paket`);
    }
    getPaketDetail(id: number | string): Observable<{ data: PaketDetail }> {
        return this.http.get<{ data: PaketDetail }>(`${this.baseUrl}/paket/${id}`);
    }
    updateStatusPaket(id: number | string, status: string, catatan?: string) {
        return this.http.patch<{ message: string; data: Paket }>(`${this.baseUrl}/paket/${id}/status`, {
        status,
        catatan,
        });
    }

    // --- COD ---
    getCodBelumDisetor(): Observable<CodBelumDisetor> {
        return this.http.get<CodBelumDisetor>(`${this.baseUrl}/cod/belum-disetor`);
    }
}