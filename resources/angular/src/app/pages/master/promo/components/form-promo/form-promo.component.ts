import { Component, EventEmitter, Input, OnInit, Output, SimpleChange } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';
import { PromoService } from '../../services/promo.service';

@Component({
  selector: 'app-form-promo',
  templateUrl: './form-promo.component.html',
  styleUrls: ['./form-promo.component.scss']
})
export class FormPromoComponent implements OnInit {

  @Input() promoId: number;
  @Output() afterSave  = new EventEmitter<boolean>();
  mode: string;
  formModel : {
      fotoUrl: string,
      foto: string,
      id_promo: number,
      nama: string,
      type: string,
      diskon: number,
      nominal: number, 
      syarat_ketentuan: string,
      kadaluarsa: number,
      promoHuruf:number
  }
 
  constructor(
      private PromoService: PromoService,
      private landaService: LandaService,
      
  ) {}

  ngOnInit(): void {
    
  }
  ngOnChanges(changes: SimpleChange) {
    this.emptyForm();
 
  }
  resetForm() {
    if (this.formModel.type === 'voucher') {
      this.formModel.diskon = null;
    } else if (this.formModel.type === 'diskon') {
      this.formModel.nominal = null;
    }
  }
  emptyForm() {
      this.mode = 'add';
      this.formModel = {
          fotoUrl: '',
          foto: '',
          id_promo: 0,
          nama: '',
          type: 'diskon',
          diskon: null,
          nominal: null,
          syarat_ketentuan: '',
          kadaluarsa: null,
          promoHuruf: null
      }
      if (this.promoId > 0) {
        this.mode = 'edit';
        this.getPromo(this.promoId);
    }
      
  }

  save() {
    if(this.mode == 'add') {
      this.PromoService.createPromo(this.formModel).subscribe((res : any) => {
          this.landaService.alertSuccess('Berhasil', res.message);
          this.afterSave.emit();
      }, err => {
          this.landaService.alertError('Mohon Maaf', err.error.errors);
      });
    } else {
        this.PromoService.updatepromo(this.formModel).subscribe((res : any) => {
            this.landaService.alertSuccess('Berhasil', res.message);
            this.afterSave.emit();
        }, err => {
            this.landaService.alertError('Mohon Maaf', err.error.errors);
        });
    }
  }
  getPromo(promoId) {
    this.PromoService.getPromoById(promoId).subscribe((res: any) => {
        this.formModel = res.data;
    }, err => {
        console.log(err);
    });
  }
  getCroppedImage($event) {
      this.formModel.foto = $event;
     }
  }
