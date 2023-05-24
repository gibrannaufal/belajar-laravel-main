import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';
@Injectable({
  providedIn: 'root'
})
export class VoucherService {

  constructor(private landaService: LandaService) { }
  getVoucher(arrParameter) {
    return this.landaService.DataGet('/v1/voucher', arrParameter );
  }

  getVoucherById(voucher) {
      return this.landaService.DataGet('/v1/voucher/' + voucher);
  }

  createVoucher(payload) {
      return this.landaService.DataPost('/v1/voucher', payload);
  }

  updateVoucher(payload) {
      return this.landaService.DataPut('/v1/voucher', payload);
  }

  deleteVoucher(voucher) {
    return this.landaService.DataDelete('/v1/voucher/' + voucher);
  }

  changeStatus(voucher) {
    return this.landaService.DataPut('/v1/voucher/' + voucher);
  }
}
