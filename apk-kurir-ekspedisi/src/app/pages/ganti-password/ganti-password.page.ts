import { CommonModule } from '@angular/common';
import { HttpErrorResponse } from '@angular/common/http';
import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import {
  IonButton,
  IonContent,
  IonHeader,
  IonInput,
  IonSpinner,
  IonTitle,
  IonToolbar,
  LoadingController,
  ToastController,
} from '@ionic/angular';

import { Api } from '../../services/api';

@Component({
  selector: 'app-ganti-password',
  templateUrl: './ganti-password.page.html',
  styleUrls: ['./ganti-password.page.scss'],
  imports: [
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonInput,
    IonButton,
    IonSpinner,
    CommonModule,
    FormsModule,
  ],
})
export class GantiPasswordPage {
  // Dependensi halaman disiapkan melalui injection agar komponen tetap standalone.
  private readonly api = inject(Api);
  private readonly router = inject(Router);
  private readonly loadingController = inject(LoadingController);
  private readonly toastController = inject(ToastController);

  passwordLama = '';
  passwordBaru = '';
  passwordKonfirmasi = '';
  isSubmitting = false;

  async gantiPassword(): Promise<void> {
    // Validasi lokal sebelum mengirim permintaan penggantian password.
    if (!this.passwordLama || !this.passwordBaru || !this.passwordKonfirmasi) {
      await this.showToast('Semua kolom password wajib diisi.', 'warning');
      return;
    }

    // Pemeriksaan panjang dan kesamaan password dilakukan sebelum mengirim ke API.
    if (this.passwordBaru.length < 8) {
      await this.showToast('Password baru minimal 8 karakter.', 'warning');
      return;
    }

    if (this.passwordBaru !== this.passwordKonfirmasi) {
      await this.showToast('Konfirmasi password tidak sama.', 'warning');
      return;
    }

    this.isSubmitting = true;
    const loading = await this.loadingController.create({
      message: 'Mengganti password...',
      spinner: 'crescent',
    });
    await loading.present();

    this.api.gantiPassword(
      this.passwordLama,
      this.passwordBaru,
      this.passwordKonfirmasi,
    ).subscribe({
      next: async (response) => {
        await loading.dismiss();
        await this.showToast(response.message, 'success');
        await this.router.navigateByUrl('/daftar-tugas', { replaceUrl: true });
      },
      error: async (error: HttpErrorResponse) => {
        await loading.dismiss();
        this.isSubmitting = false;

        const message = error.error?.errors?.password_lama?.[0]
          ?? error.error?.errors?.password_baru?.[0]
          ?? error.error?.message
          ?? 'Password gagal diganti.';

        await this.showToast(message, 'danger');
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
