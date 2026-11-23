import { CommonModule } from '@angular/common';
import type { HttpErrorResponse } from '@angular/common/http';
import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import {
  IonButton,
  IonContent,
  IonInput,
  IonNote,
  IonSpinner,
  LoadingController,
  ToastController,
} from '@ionic/angular';
import { timeout, TimeoutError } from 'rxjs';

import { Api } from '../../services/api';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
  imports: [
    IonContent,
    IonInput,
    IonNote,
    IonButton,
    IonSpinner,
    CommonModule,
    FormsModule,
  ],
})
export class LoginPage {
  // Mengambil service API, router, loading, dan toast sebagai dependensi halaman standalone.
  private readonly api = inject(Api);
  private readonly router = inject(Router);
  private readonly loadingController = inject(LoadingController);
  private readonly toastController = inject(ToastController);

  // URL terakhir ditampilkan kembali agar bisa diganti tanpa menginstal APK baru.
  serverUrl = this.api.getApiUrl();
  // Menandai bahwa alamat server berhasil disimpan.
  serverSaved = false;
  // Menyimpan pesan jika alamat server tidak valid.
  serverError = '';
  kodeKurir = '';
  password = '';
  showPassword = false;
  isSubmitting = false;
  errorMessage = '';

  constructor() {}

  togglePasswordVisibility(): void {
    this.showPassword = !this.showPassword;
  }

  // Validasi dan simpan alamat sebelum dipakai oleh semua permintaan API.
  saveServerUrl(): boolean {
    // Hapus pesan dari percobaan penyimpanan sebelumnya.
    this.serverError = '';
    // Sembunyikan tanda berhasil sampai alamat baru divalidasi.
    this.serverSaved = false;

    // Tolak alamat yang tidak memakai format HTTP(S) yang didukung.
    if (!this.api.setApiUrl(this.serverUrl)) {
      // Beri contoh format yang bisa dimasukkan pengguna.
      this.serverError = 'Masukkan alamat server yang valid, misalnya http://192.168.1.10:8000/api.';
      return false;
    }

    // Tampilkan kembali alamat yang sudah dirapikan oleh service API.
    this.serverUrl = this.api.getApiUrl();
    // Tampilkan bahwa alamat berhasil disimpan di perangkat.
    this.serverSaved = true;

    return true;
  }

  async login(): Promise<void> {
    // Simpan perubahan alamat terlebih dahulu agar login selalu menuju server yang dipilih.
    if (!this.saveServerUrl()) {
      return;
    }

    // Hilangkan spasi di awal atau akhir kode kurir.
    const kodeKurir = this.kodeKurir.trim();

    // Mencegah permintaan kosong dan memberikan umpan balik langsung kepada kurir.
    if (!kodeKurir || !this.password) {
      // Beri tahu pengguna bahwa kedua kolom login harus diisi.
      await this.showToast('Kode kurir dan password wajib diisi.', 'warning');
      return;
    }

    // Nonaktifkan tombol selama proses login berjalan.
    this.isSubmitting = true;
    // Hapus pesan error login sebelumnya.
    this.errorMessage = '';
    // Buat indikator proses agar pengguna tahu permintaan sedang dikirim.
    const loading = await this.loadingController.create({
      message: 'Memproses login...',
      spinner: 'crescent',
    });
    // Tampilkan indikator sebelum menghubungi server.
    await loading.present();

    // Validasi sederhana di sisi aplikasi sebelum meminta token login ke server.
    this.api.login(kodeKurir, this.password).pipe(timeout(15000)).subscribe({
      next: async (response) => {
        // Token disimpan lokal agar interceptor dapat melampirkannya pada request berikutnya.
        this.api.setToken(response.token);
        // Tutup indikator setelah server mengembalikan respons berhasil.
        await loading.dismiss();
        // Tampilkan nama kurir sebagai konfirmasi bahwa login berhasil.
        await this.showToast(`Selamat datang, ${response.kurir.nama}`, 'success');

        // Arahkan kurir mengganti password jika akun masih memakai password awal.
        if (response.wajib_ganti_password) {
          await this.router.navigateByUrl('/ganti-password', { replaceUrl: true });
          return;
        }

        // Tampilkan daftar tugas jika kurir tidak perlu mengganti password.
        await this.router.navigateByUrl('/daftar-tugas', { replaceUrl: true });
      },
      error: async (error: HttpErrorResponse | TimeoutError) => {
        // Tutup indikator dan aktifkan kembali tombol saat login gagal.
        await loading.dismiss();
        this.isSubmitting = false;

        // Bedakan pesan waktu habis, kegagalan jaringan, dan pesan dari server.
        const displayMessage = error instanceof TimeoutError
          ? 'Server tidak merespons. Periksa koneksi jaringan dan alamat API.'
          : error.status === 0
            ? 'Tidak dapat terhubung ke server. Pastikan server Laravel aktif dan IP API benar.'
            : error.error?.message;
          // Ambil pesan validasi kode kurir atau password bila tersedia dari server.
        const fieldMessage = error instanceof TimeoutError
          ? null
          : error.error?.errors?.kode_kurir?.[0]
            ?? error.error?.errors?.password?.[0];

          // Pilih pesan terbaik yang tersedia untuk ditampilkan pada halaman.
        this.errorMessage = displayMessage || fieldMessage || 'Login gagal. Periksa koneksi jaringan.';
          // Tampilkan pesan kegagalan sebagai notifikasi.
        await this.showToast(
          displayMessage || fieldMessage || 'Login gagal. Periksa koneksi jaringan.',
          'danger',
        );
      },
      complete: () => {
        // Pastikan tombol kembali aktif setelah permintaan selesai.
        this.isSubmitting = false;
      },
    });
  }

  private async showToast(message: string, color: 'success' | 'warning' | 'danger'): Promise<void> {
    const toast = await this.toastController.create({
      message,
      duration: 2500,
      position: 'top',
      color,
    });
    await toast.present();
  }
}
