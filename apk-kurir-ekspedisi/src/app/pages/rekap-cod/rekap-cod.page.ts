import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonContent, IonHeader, IonTitle, IonToolbar } from '@ionic/angular';

@Component({
  selector: 'app-rekap-cod',
  templateUrl: './rekap-cod.page.html',
  styleUrls: ['./rekap-cod.page.scss'],
  imports: [IonContent, IonHeader, IonTitle, IonToolbar, CommonModule, FormsModule],
})
export class RekapCodPage implements OnInit {
  constructor() {}

  ngOnInit() {}
}
