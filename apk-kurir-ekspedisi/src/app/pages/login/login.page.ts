import { CommonModule } from '@angular/common';
import type { HttpErrorResponse } from '@angular/common/http';
import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import {
  IonButton,
  IonContent,
  IonInput,
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

  kodeKurir = '';
  password = '';
  showPassword = false;
  isSubmitting = false;
  errorMessage = '';

  constructor() {}

  togglePasswordVisibility(): void {
    this.showPassword = !this.showPassword;
  }

  async login(): Promise<void> {
    const kodeKurir = this.kodeKurir.trim();

    // Mencegah permintaan kosong dan memberikan umpan balik langsung kepada kurir.
    if (!kodeKurir || !this.password) {
      await this.showToast('Kode kurir dan password wajib diisi.', 'warning');
      return;
    }

    this.isSubmitting = true;
    this.errorMessage = '';
    const loading = await this.loadingController.create({
      message: 'Memproses login...',
      spinner: 'crescent',
    });
    await loading.present();

    // Validasi sederhana di sisi aplikasi sebelum meminta token login ke server.
    this.api.login(kodeKurir, this.password).pipe(timeout(15000)).subscribe({
      next: async (response) => {
        // Token disimpan lokal agar interceptor dapat melampirkannya pada request berikutnya.
        this.api.setToken(response.token);
        await loading.dismiss();
        await this.showToast(`Selamat datang, ${response.kurir.nama}`, 'success');

        if (response.wajib_ganti_password) {
          await this.router.navigateByUrl('/ganti-password', { replaceUrl: true });
          return;
        }

        await this.router.navigateByUrl('/daftar-tugas', { replaceUrl: true });
      },
      error: async (error: HttpErrorResponse | TimeoutError) => {
        await loading.dismiss();
        this.isSubmitting = false;

        const validationMessage = error instanceof TimeoutError
          ? 'Server tidak merespons. Periksa koneksi jaringan dan alamat API.'
          : error.error?.message;
        const fieldMessage = error instanceof TimeoutError
          ? null
          : error.error?.errors?.kode_kurir?.[0]
            ?? error.error?.errors?.password?.[0];

        this.errorMessage = validationMessage || fieldMessage || 'Login gagal. Periksa koneksi jaringan.';
        await this.showToast(
          validationMessage || fieldMessage || 'Login gagal. Periksa koneksi jaringan.',
          'danger',
        );
      },
      complete: () => {
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
