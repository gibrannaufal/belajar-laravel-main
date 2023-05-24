import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LandaService } from 'src/app/core/services/landa.service';
import Swal from 'sweetalert2';
import { PromoService } from '../../services/promo.service';
import { DataTableDirective } from 'angular-datatables';
import { ViewChild } from '@angular/core';

@Component({
  selector: 'app-daftar-promo',
  templateUrl: './daftar-promo.component.html',
  styleUrls: ['./daftar-promo.component.scss']
})
export class DaftarPromoComponent implements OnInit {
    promoId: number;
    listPromo: [];
    titleModal: string;
    modelId: number;
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;

    imageSource:any;
    filter: {
        nama: ''
      };

    constructor(
        private promoService: PromoService,
        private landaService: LandaService,
        private modalService: NgbModal
    ) { }

    ngOnInit(): void {
      this.filter = {
        nama: '',
      
      };
        this.getPromo();
    }
    getPromo() {
        this.dtOptions = {
            serverSide: true,
            processing: true,
            ordering: false,
            pageLength: 4,
            ajax: (dtParams: any, callback) => {
              const params = {
                nama: this.filter.nama,
                itemperpage: 4,
                per_page: dtParams.length,
                page: (dtParams.start / dtParams.length) + 1,
              };
              
              this.promoService.getPromo(params).subscribe((res: any) => {
                const { list, meta } = res.data;
         
                let number = dtParams.start + 1;
                list.forEach(val => {
                  val.no = number++;
                });
                this.listPromo  = list;
         
                callback({
                  recordsTotal: meta.total,
                  recordsFiltered: meta.total,
                  data: [],
                });
         
              }, (err: any) => {
                console.log(err);
              });
            },
          };
    }
    trackByIndex(index, list): any {
      return list.id;
    }
 
    createPromo(modal) {
        this.titleModal = 'Tambah Promo';
        this.modelId = 0;
        this.modalService.open(modal, { size: 'lg', backdrop: 'static' });
    }

    updatePromo(modal, promoModel) {
        this.titleModal = 'Edit promo: ' + promoModel.nama;
        this.modelId = promoModel.id_promo;
        this.modalService.open(modal, { size: 'lg', backdrop: 'static' });
    }

    deletePromo(promoId) {
        Swal.fire({
            title: 'Apakah kamu yakin ?',
            text: 'promo tidak dapat dipakai jika dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#34c38f',
            cancelButtonColor: '#f46a6a',
            confirmButtonText: 'Ya, Hapus data ini !',
        }).then((result) => {
            if (result.value) {
                this.promoService.deletePromo(promoId).subscribe((res: any) => {
                    this.landaService.alertSuccess('Berhasil', res.message);
                    this.reloadDataTable();
                }, err => {
                    console.log(err);
                });
            }
        });
    }
    reloadDataTable(): void {
      this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
        dtInstance.draw();
      });
     }

}
