import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
  providedIn: 'root'
})
export class DashboardService {

  constructor(private landaService: LandaService) { }

  getDashboard(arrParameter) {
      return this.landaService.DataGet('/v1/Dashboard', arrParameter );
  }
  
}
