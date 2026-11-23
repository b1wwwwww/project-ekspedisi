import { TestBed } from '@angular/core/testing';
import { beforeEach, describe, expect, it } from 'vitest';

import { Api } from './api';

describe('Layanan API', () => {
  let service: Api;

  beforeEach(() => {
    // Setiap test dimulai tanpa alamat tersimpan dari test sebelumnya.
    localStorage.removeItem('kurir_api_url');
    TestBed.configureTestingModule({});
    service = TestBed.inject(Api);
  });

  it('berhasil dibuat', () => {
    expect(service).toBeTruthy();
  });

  it('menyimpan alamat API yang sudah dinormalisasi di perangkat', () => {
    expect(service.setApiUrl(' http://10.0.2.2:8000/ ')).toBe(true);
    expect(service.getApiUrl()).toBe('http://10.0.2.2:8000/api');
  });

  it('menolak alamat API yang tidak didukung atau tidak valid', () => {
    expect(service.setApiUrl('ftp://example.com')).toBe(false);
    expect(service.setApiUrl('http://example.com/other-path')).toBe(false);
    expect(service.getApiUrl()).toBe('');
  });
});
