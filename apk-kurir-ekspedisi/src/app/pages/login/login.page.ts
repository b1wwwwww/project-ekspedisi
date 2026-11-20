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
  // Inject Angular and Ionic services so the page stays standalone.
  private readonly api = inject(Api);
  private readonly router = inject(Router);
  private readonly loadingController = inject(LoadingController);
  private readonly toastController = inject(ToastController);

  kodeKurir = '';
  password = '';
  showPassword = false;
  isSubmitting = false;

  constructor() {}

  togglePasswordVisibility(): void {
    this.showPassword = !this.showPassword;
  }

  async login(): Promise<void> {
    const kodeKurir = this.kodeKurir.trim();

    // Prevent empty requests and give immediate feedback to the courier.
    if (!kodeKurir || !this.password) {
      await this.showToast('Kode kurir dan password wajib diisi.', 'warning');
      return;
    }

    this.isSubmitting = true;
    const loading = await this.loadingController.create({
      message: 'Memproses login...',
      spinner: 'crescent',
    });
    await loading.present();

    this.api.login(kodeKurir, this.password).subscribe({
      next: async (response) => {
        // The interceptor uses this token for every authenticated API request.
        this.api.setToken(response.token);
        await loading.dismiss();
        await this.showToast(`Selamat datang, ${response.kurir.nama}`, 'success');

        if (response.wajib_ganti_password) {
          await this.router.navigateByUrl('/ganti-password', { replaceUrl: true });
          return;
        }

        await this.router.navigateByUrl('/daftar-tugas', { replaceUrl: true });
      },
      error: async (error: HttpErrorResponse) => {
        await loading.dismiss();
        this.isSubmitting = false;

        const validationMessage = error.error?.message;
        const fieldMessage = error.error?.errors?.kode_kurir?.[0]
          ?? error.error?.errors?.password?.[0];

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
