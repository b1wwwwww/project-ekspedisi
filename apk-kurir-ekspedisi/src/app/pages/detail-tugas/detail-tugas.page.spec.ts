import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DetailTugasPage } from './detail-tugas.page';

describe('DetailTugasPage', () => {
  let component: DetailTugasPage;
  let fixture: ComponentFixture<DetailTugasPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(DetailTugasPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
