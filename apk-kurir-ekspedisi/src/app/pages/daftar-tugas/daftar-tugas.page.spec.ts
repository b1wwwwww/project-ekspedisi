import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DaftarTugasPage } from './daftar-tugas.page';

describe('DaftarTugasPage', () => {
  let component: DaftarTugasPage;
  let fixture: ComponentFixture<DaftarTugasPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(DaftarTugasPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
