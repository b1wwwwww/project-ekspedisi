import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonContent, IonHeader, IonTitle, IonToolbar } from '@ionic/angular';

@Component({
  selector: 'app-detail-tugas',
  templateUrl: './detail-tugas.page.html',
  styleUrls: ['./detail-tugas.page.scss'],
  imports: [IonContent, IonHeader, IonTitle, IonToolbar, CommonModule, FormsModule],
})
export class DetailTugasPage implements OnInit {
  constructor() {}

  ngOnInit() {}
}
