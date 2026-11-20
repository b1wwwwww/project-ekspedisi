import type { HttpErrorResponse, HttpInterceptorFn } from '@angular/common/http';
import { inject } from '@angular/core';
import { Router } from '@angular/router';
import { catchError, throwError } from 'rxjs';

import { Api } from '../services/api';

export const authInterceptor: HttpInterceptorFn = (req, next) => {
  const api = inject(Api);
  const router = inject(Router);
  const token = api.getToken();

  // Hanya tempelkan header Authorization kalau ada token tersimpan.
  // Request /login sendiri tidak butuh token, jadi aman kalau token null.
  if (!token) {
    return next(req);
  }

  const reqWithAuth = req.clone({
    setHeaders: {
      Authorization: `Bearer ${token}`,
    },
  });

  return next(reqWithAuth).pipe(
    catchError((error: HttpErrorResponse) => {
      // Jika token sudah tidak valid (misalnya expired), maka API biasanya
      // mengembalikan response dengan status 401 Unauthorized. Kita bisa
      // menangkapnya di sini dan melakukan tindakan yang sesuai, seperti
      // menghapus token dan mengarahkan pengguna kembali ke halaman login.

      if (error.status === 401) {
        api.clearToken();
        void router.navigateByUrl('/login', { replaceUrl: true });
      }

      return throwError(() => error);
    }),
  );
};