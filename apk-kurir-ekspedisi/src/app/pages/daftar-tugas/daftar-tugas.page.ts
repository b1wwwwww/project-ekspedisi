import { CommonModule } from '@angular/common';
import type { HttpErrorResponse } from '@angular/common/http';
import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import {
  IonButton,
  IonContent,
  IonHeader,
  IonRefresher,
  IonRefresherContent,
  IonSpinner,
  IonTitle,
  IonToolbar,
  ToastController,
} from '@ionic/angular';

import { Api, Paket } from '../../services/api';

@Component({
  selector: 'app-daftar-tugas',
  templateUrl: './daftar-tugas.page.html',
  styleUrls: ['./daftar-tugas.page.scss'],
  imports: [
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonButton,
    IonRefresher,
    IonRefresherContent,
    IonSpinner,
    CommonModule,
  ],
})
export class DaftarTugasPage {
  // API hanya mengembalikan paket Dikirim milik akun kurir yang sedang login.
  private readonly api = inject(Api);
  private readonly router = inject(Router);
  private readonly toastController = inject(ToastController);

  paketList: Paket[] = [];
  isLoading = true;
  errorMessage = '';

  constructor() {
    this.loadPaket();
  }

  loadPaket(event?: CustomEvent): void {
    this.isLoading = event ? false : true;
    this.errorMessage = '';

    this.api.getPaketList().subscribe({
      next: (response) => {
        this.paketList = response.data;
        this.isLoading = false;
        this.errorMessage = '';
        this.completeRefresh(event);
      },
      error: async (error: HttpErrorResponse) => {
        this.isLoading = false;
        this.errorMessage = error.status === 0
          ? 'Tidak dapat terhubung ke server.'
          : 'Daftar tugas gagal dimuat.';
        await this.showToast(this.errorMessage, 'danger');
        this.completeRefresh(event);
      },
    });
  }

  bukaDetail(paket: Paket): void {
    void this.router.navigate(['/detail-tugas', paket.id]);
  }

  bukaRekapCod(): void {
    void this.router.navigateByUrl('/rekap-cod');
  }

  bukaGantiPassword(): void {
    void this.router.navigateByUrl('/ganti-password');
  }

  logout(): void {
    // Token server dihapus melalui API, lalu token lokal selalu dibersihkan saat logout.
    this.api.logout().subscribe({
      next: async (response) => {
        this.api.clearToken();
        await this.showToast(response.message, 'success');
        await this.router.navigateByUrl('/login', { replaceUrl: true });
      },
      error: async () => {
        this.api.clearToken();
        await this.showToast('Anda telah keluar dari aplikasi.', 'success');
        await this.router.navigateByUrl('/login', { replaceUrl: true });
      },
    });
  }

  private completeRefresh(event?: CustomEvent): void {
    if (event) {
      const refresher = event.target as HTMLIonRefresherElement;
      refresher.complete();
    }
  }

  private async showToast(message: string, color: 'success' | 'danger'): Promise<void> {
    const toast = await this.toastController.create({
      message,
      duration: 2500,
      position: 'top',
      color,
    });
    await toast.present();
  }
}
