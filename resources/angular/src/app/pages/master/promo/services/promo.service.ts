import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
  providedIn: 'root'
})
export class PromoService {

  constructor(private landaService: LandaService) { }
  
  getPromo(arrParameter) {
    return this.landaService.DataGet('/v1/promo', arrParameter );
  }
  getPromoById(promo) {
      return this.landaService.DataGet('/v1/promo/' + promo);
  }
  createPromo(payload) {
    return this.landaService.DataPost('/v1/promo', payload);
  }
  updatepromo(payload) {
    return this.landaService.DataPut('/v1/promo', payload);
  }

  deletePromo(promo) {
    return this.landaService.DataDelete('/v1/promo/' + promo);
  }
}
