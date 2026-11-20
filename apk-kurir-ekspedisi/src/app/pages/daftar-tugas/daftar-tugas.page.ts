import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonContent, IonHeader, IonTitle, IonToolbar } from '@ionic/angular';

@Component({
  selector: 'app-daftar-tugas',
  templateUrl: './daftar-tugas.page.html',
  styleUrls: ['./daftar-tugas.page.scss'],
  imports: [IonContent, IonHeader, IonTitle, IonToolbar, CommonModule, FormsModule],
})
export class DaftarTugasPage implements OnInit {
  constructor() {}

  ngOnInit() {}
}
