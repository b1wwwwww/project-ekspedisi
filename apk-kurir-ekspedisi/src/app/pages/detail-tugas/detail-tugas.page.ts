import { CommonModule } from '@angular/common';
import type { HttpErrorResponse } from '@angular/common/http';
import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import {
  IonBackButton,
  IonButton,
  IonButtons,
  IonContent,
  IonHeader,
  IonSpinner,
  IonTextarea,
  IonTitle,
  IonToolbar,
  LoadingController,
  ToastController,
} from '@ionic/angular';

import { Api, PaketDetail } from '../../services/api';

@Component({
  selector: 'app-detail-tugas',
  templateUrl: './detail-tugas.page.html',
  styleUrls: ['./detail-tugas.page.scss'],
  imports: [
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonBackButton,
    IonButton,
    IonButtons,
    IonTextarea,
    IonSpinner,
    CommonModule,
    FormsModule,
  ],
})
export class DetailTugasPage {
  // Resolve the package ID from the route and use server authorization for ownership checks.
  private readonly route = inject(ActivatedRoute);
  private readonly router = inject(Router);
  private readonly api = inject(Api);
  private readonly loadingController = inject(LoadingController);
  private readonly toastController = inject(ToastController);

  paket: PaketDetail | null = null;
  catatan = '';
  isLoading = true;
  isUpdating = false;
  errorMessage = '';

  constructor() {
    const packageId = this.route.snapshot.paramMap.get('id');

    if (!packageId) {
      this.errorMessage = 'ID paket tidak ditemukan.';
      this.isLoading = false;
      return;
    }

    this.loadDetail(packageId);
  }

  loadDetail(packageId: string): void {
    this.api.getPaketDetail(packageId).subscribe({
      next: (response) => {
        this.paket = response.data;
        this.isLoading = false;
      },
      error: async (error: HttpErrorResponse) => {
        this.isLoading = false;
        this.errorMessage = error.status === 403
          ? 'Paket ini bukan milik akun kurir Anda.'
          : error.status === 404
            ? 'Paket tidak ditemukan.'
            : 'Detail paket gagal dimuat.';
        await this.showToast(this.errorMessage, 'danger');
      },
    });
  }

  async ubahStatus(status: 'Terkirim' | 'Gagal Kirim'): Promise<void> {
    if (!this.paket) {
      return;
    }

    if (status === 'Gagal Kirim' && !this.catatan.trim()) {
      await this.showToast('Catatan kegagalan wajib diisi.', 'warning');
      return;
    }

    this.isUpdating = true;
    const loading = await this.loadingController.create({
      message: 'Memperbarui status...',
      spinner: 'crescent',
    });
    await loading.present();

    this.api.updateStatusPaket(
      this.paket.id,
      status,
      status === 'Gagal Kirim' ? this.catatan.trim() : undefined,
    ).subscribe({
      next: async (response) => {
        await loading.dismiss();
        await this.showToast(response.message, 'success');
        await this.router.navigateByUrl('/daftar-tugas', { replaceUrl: true });
      },
      error: async (error: HttpErrorResponse) => {
        await loading.dismiss();
        this.isUpdating = false;

        const message = error.error?.errors?.catatan?.[0]
          ?? error.error?.message
          ?? 'Status paket gagal diperbarui.';

        await this.showToast(message, 'danger');
      },
      complete: () => {
        this.isUpdating = false;
      },
    });
  }

  kembali(): void {
    void this.router.navigateByUrl('/daftar-tugas');
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
