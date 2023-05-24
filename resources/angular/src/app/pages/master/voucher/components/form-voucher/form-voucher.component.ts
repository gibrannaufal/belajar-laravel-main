import { Component, 
  EventEmitter, Input, 
  OnInit, Output, 
  SimpleChange } from '@angular/core';

import { LandaService } from 'src/app/core/services/landa.service';
import { VoucherService } from '../../services/voucher.service';
import { CustomerService } from '../../../customers/services/customer.service';
import { PromoService } from '../../../promo/services/promo.service';

@Component({
  selector: 'app-form-voucher',
  templateUrl: './form-voucher.component.html',
  styleUrls: ['./form-voucher.component.scss']
})
export class FormVoucherComponent implements OnInit {

    @Input() voucherId: number;
    @Output() afterSave  = new EventEmitter<boolean>();
    mode: string;
    customer: [];
    voucher: [];
    promo: any[];
    
    totalNominal: number = 0;
    formModel : {
        fotoUrl: string,
        foto: string,
        id: number,
        id_promo: number,
        id_customer: number,
        periode_mulai: Date,
        periode_selesai: string,
        customer: string,
        catatan: string, 
        jumlah: number,
        status: number,
        nominal: number

    }

    constructor(
        private VoucherService: VoucherService,
        private promoService: PromoService,
        private customerService: CustomerService,
        private landaService: LandaService
    ) {}

    ngOnInit(): void {
        this.getCustomer();
        this.getPromo();
    }
    
    ngOnChanges(changes: SimpleChange) {
        this.emptyForm();
    }

    emptyForm() {
        this.mode = 'add';
        this.formModel = {
            fotoUrl: '',
            foto: '',
            id: 0,
            id_promo: null,
            id_customer: null,
            periode_mulai: null,
            periode_selesai: '',
            customer: '',
            catatan: '', 
            jumlah: 1,
            status: 1,
            nominal: null
        }
        this.totalNominal = 0; // reset the total nominal value
        if (this.voucherId > 0) {
            this.mode = 'edit';
            this.getVoucherByid(this.voucherId);
        }
    }
    getCustomer() {
        this.customerService.getCustomers([]).subscribe((res: any) => {
            this.customer = res.data.list;
        }, err => {
            console.log(err);
        })
    }
   
    getPromo() {
        this.promoService.getPromo([]).subscribe((res: any) => {
            this.promo = res.data.list.filter((promo: any) => promo.type === 'voucher');
        }, err => {
            console.log(err);
        });
    }
    formAutoFill() {
        for (let i = 0; i < this.promo.length; i++) {
            if (this.promo[i]['id_promo'] == this.formModel.id_promo ) {
            // Menghitung nominal menurut jumlah yang diInputkan 
            const defaultNominal = this.promo[i]['nominal'];
            const devideJum = defaultNominal * this.formModel.jumlah;
            this.formModel.nominal = devideJum;

            // menjumlah periode selesai sesuai kadaluarsa promo
          
            // konversi nilai kadaluarsa dalam jumlah hari ke dalam milidetik
                const kadaluarsaHari = this.promo[i]['kadaluarsa']; 
                const kadaluarsaMilidetik = kadaluarsaHari * 24 * 60 * 60 * 1000;
                
                if (this.formModel.periode_mulai) {
                    // buat objek Date berdasarkan nilai periode_mulai
                    const periodeMulai = new Date(this.formModel.periode_mulai);
                                  
                    // tambahkan nilai kadaluarsa dalam milidetik ke tanggal periode_mulai
                    const periodeSelesai = new Date(periodeMulai.getTime() + kadaluarsaMilidetik);
                               
                    // format tanggal periode_selesai sesuai dengan kebutuhan Anda
                    const tanggalSelesai = periodeSelesai.toISOString().substring(0, 10);
                    this.formModel.periode_selesai = tanggalSelesai;
                  } else {
                    // buat  periode selesai null saat periode mulai belum diisi
                    this.formModel.periode_selesai = null;
                  }

              break; 
            }
             
          }
    }
    
    getVoucherByid(voucherid) {
        this.VoucherService.getVoucherById(voucherid).subscribe((res: any) => {
            this.formModel = res.data;
        }, err => {
            console.log(err);
        });
    }
    save() {
        if(this.mode == 'add') {
            this.VoucherService.createVoucher(this.formModel).subscribe((res : any) => {
                this.landaService.alertSuccess('Berhasil', res.message);
                this.afterSave.emit();
            }, err => {
                this.landaService.alertError('Mohon Maaf', err.error.errors);
            });
        } else {
            this.VoucherService.updateVoucher(this.formModel).subscribe((res : any) => {
                this.landaService.alertSuccess('Berhasil', res.message);
                this.afterSave.emit();
            }, err => {
                this.landaService.alertError('Mohon Maaf', err.error.errors);
            });
        }
    }

    getCroppedImage($event) {
        this.formModel.foto = $event;
       }

}
