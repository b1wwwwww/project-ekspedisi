import type { Routes } from '@angular/router';

import { authGuard } from './guards/auth-guard';

export const routes: Routes = [
  { path: '', redirectTo: 'login', pathMatch: 'full' },
  {
    path: 'login',
    loadComponent: () => import('./pages/login/login.page').then((m) => m.LoginPage),
  },
  {
    path: 'daftar-tugas',
    loadComponent: () => import('./pages/daftar-tugas/daftar-tugas.page').then((m) => m.DaftarTugasPage),
    canActivate: [authGuard],
  },
  {
    path: 'detail-tugas/:id',
    loadComponent: () => import('./pages/detail-tugas/detail-tugas.page').then((m) => m.DetailTugasPage),
    canActivate: [authGuard],
  },
  {
    path: 'ganti-password',
    loadComponent: () => import('./pages/ganti-password/ganti-password.page').then((m) => m.GantiPasswordPage),
    canActivate: [authGuard],
  },
  {
    path: 'rekap-cod',
    loadComponent: () => import('./pages/rekap-cod/rekap-cod.page').then((m) => m.RekapCodPage),
    canActivate: [authGuard],
  },
];