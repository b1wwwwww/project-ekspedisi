import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonContent, IonHeader, IonTitle, IonToolbar } from '@ionic/angular';

@Component({
  selector: 'app-ganti-password',
  templateUrl: './ganti-password.page.html',
  styleUrls: ['./ganti-password.page.scss'],
  imports: [IonContent, IonHeader, IonTitle, IonToolbar, CommonModule, FormsModule],
})
export class GantiPasswordPage implements OnInit {
  constructor() {}

  ngOnInit() {}
}
