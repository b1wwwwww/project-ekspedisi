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
  IonRefresher,
  IonRefresherContent,
} from '@ionic/angular';
import { timeout, TimeoutError } from 'rxjs';

import { Api } from '../../services/api';
import type { PaketDetail } from '../../services/api';

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
    IonRefresher,
    IonRefresherContent,
    CommonModule,
    FormsModule,
  ],
})
export class DetailTugasPage {
  // ID paket dibaca dari routing, sedangkan hak akses kepemilikan tetap diperiksa server.
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

  loadDetail(packageId: string, event?: CustomEvent): void {
    this.api.getPaketDetail(packageId).pipe(timeout(8000)).subscribe({
      next: (response) => {
        this.paket = response.data;
        this.isLoading = false;
        this.completeRefresher(event);
      },
      error: async (error: HttpErrorResponse | TimeoutError) => {
        this.isLoading = false;
        this.errorMessage = error instanceof TimeoutError
          ? 'Server tidak merespons dalam 8 detik. Tekan Coba Lagi atau periksa jaringan.'
          : error.status === 0
            ? 'Tidak dapat terhubung ke server. Pastikan server aktif dan IP API benar.'
            : error.status === 423
              ? 'Silakan ganti password terlebih dahulu.'
              : error.status === 401
                ? 'Sesi login berakhir. Silakan login kembali.'
                : error.status === 403
                  ? 'Paket ini bukan milik akun kurir Anda.'
                  : error.status === 404
                    ? 'Paket tidak ditemukan.'
                    : 'Detail paket gagal dimuat.';
        await this.showToast(this.errorMessage, 'danger');
        this.completeRefresher(event);
      },
    });
  }

  async ubahStatus(status: 'Terkirim' | 'Gagal Kirim'): Promise<void> {
    if (!this.paket) {
      return;
    }

    if (status === 'Gagal Kirim' && !this.catatan.trim()) {
      // Catatan kegagalan wajib ada agar aturan pengiriman gagal dapat dicatat dengan jelas.
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

  muatUlang(event?: CustomEvent): void {
    const packageId = this.route.snapshot.paramMap.get('id');
    if (!packageId) {
      this.errorMessage = 'ID paket tidak ditemukan.';
      return;
    }

    this.isLoading = event ? false : true;
    this.errorMessage = '';
    this.loadDetail(packageId, event);
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

  private completeRefresher(event?: CustomEvent): void {
    if (event) {
      const refresher = event.target as HTMLIonRefresherElement;
      refresher.complete();
    }
  }
}
