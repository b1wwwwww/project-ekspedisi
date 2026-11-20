import { CommonModule } from '@angular/common';
import type { HttpErrorResponse } from '@angular/common/http';
import { Component, inject, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {
  IonBackButton,
  IonButton,
  IonButtons,
  IonContent,
  IonHeader,
  IonRefresher,
  IonRefresherContent,
  IonSpinner,
  IonTitle,
  IonToolbar,
  ToastController,
} from '@ionic/angular';

import { Api, CodBelumDisetor } from '../../services/api';

@Component({
  selector: 'app-rekap-cod',
  templateUrl: './rekap-cod.page.html',
  styleUrls: ['./rekap-cod.page.scss'],
  imports: [
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonButtons,
    IonBackButton,
    IonButton,
    IonRefresher,
    IonRefresherContent,
    IonSpinner,
    CommonModule,
  ],
})
export class RekapCodPage implements OnInit {
  // Rekap COD dihitung oleh API hanya untuk paket milik kurir yang sedang login.
  private readonly api = inject(Api);
  private readonly router = inject(Router);
  private readonly toastController = inject(ToastController);

  rekap: CodBelumDisetor | null = null;
  isLoading = true;
  errorMessage = '';

  constructor() {}

  ngOnInit() {
    this.loadRekap();
  }

  loadRekap(event?: CustomEvent): void {
    this.isLoading = event ? false : true;
    this.errorMessage = '';

    this.api.getCodBelumDisetor().subscribe({
      next: (response) => {
        this.rekap = response;
        this.isLoading = false;
        this.completeRefresh(event);
      },
      error: async (error: HttpErrorResponse) => {
        this.isLoading = false;
        this.errorMessage = error.status === 0
          ? 'Tidak dapat terhubung ke server.'
          : 'Rekap COD gagal dimuat.';
        await this.showToast(this.errorMessage, 'danger');
        this.completeRefresh(event);
      },
    });
  }

  kembali(): void {
    void this.router.navigateByUrl('/daftar-tugas');
  }

  private completeRefresh(event?: CustomEvent): void {
    if (event) {
      const refresher = event.target as HTMLIonRefresherElement;
      refresher.complete();
    }
  }

  private async showToast(message: string, color: 'danger'): Promise<void> {
    const toast = await this.toastController.create({
      message,
      duration: 2500,
      position: 'top',
      color,
    });
    await toast.present();
  }
}
