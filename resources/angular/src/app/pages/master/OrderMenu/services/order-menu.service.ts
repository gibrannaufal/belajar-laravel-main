import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
  providedIn: 'root'
})
export class OrderMenuService {

  constructor(private landaService: LandaService) { }

    getOrderMenu(arrParameter) {
        return this.landaService.DataGet('/v1/OrderMenu', arrParameter );
    }
    generatePDF(arrParameter) {
      return this.landaService.GenPdf('/v1/generatePDF', arrParameter );
    }
    exportExcel(arrParameter) {
      return this.landaService.GenPdf('/v1/generateExcel', arrParameter );
    }
}

