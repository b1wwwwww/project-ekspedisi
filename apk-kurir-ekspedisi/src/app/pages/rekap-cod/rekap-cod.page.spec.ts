import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RekapCodPage } from './rekap-cod.page';

describe('RekapCodPage', () => {
  let component: RekapCodPage;
  let fixture: ComponentFixture<RekapCodPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(RekapCodPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
