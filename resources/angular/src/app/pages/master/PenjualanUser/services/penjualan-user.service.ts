import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
  providedIn: 'root'
})
export class PenjualanUserService {

  constructor(private landaService: LandaService) { }

  getPenjualanUser(arrParameter) {
      return this.landaService.DataGet('/v1/PenjualanUser', arrParameter );
  }
  
  generatePDF(arrParameter) {
    return this.landaService.GenPdf('/v1/exportPDF', arrParameter );
  }
  exportExcel(arrParameter) {
    return this.landaService.GenPdf('/v1/exportExcel', arrParameter );
  }
}
