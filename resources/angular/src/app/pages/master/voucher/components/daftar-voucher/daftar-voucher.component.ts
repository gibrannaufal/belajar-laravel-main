import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LandaService } from 'src/app/core/services/landa.service';
import Swal from 'sweetalert2';
import { VoucherService } from '../../services/voucher.service';
import { DataTableDirective } from 'angular-datatables';
import { ViewChild } from '@angular/core';
@Component({
    selector: 'voucher-daftar',
    templateUrl: './daftar-voucher.component.html',
    styleUrls: ['./daftar-voucher.component.scss']
})
export class DaftarVoucherComponent implements OnInit {

    voucherId: number;
    listVoucher: [];
    titleModal: string;
    modelId: number;
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;

    imageSource:any;
    filter: {
        customer: ''
      };

    constructor(
        private voucherService: VoucherService,
        private landaService: LandaService,
        private modalService: NgbModal
    ) { }

    ngOnInit(): void {
      this.filter = {
        customer: '',
      
      };
        this.getVoucher();
    }
    getVoucher() {
        this.dtOptions = {
            serverSide: true,
            processing: true,
            ordering: false,
            pageLength: 4,
            ajax: (dtParams: any, callback) => {
              const params = {
                customer: this.filter.customer,
                itemperpage: 4,
                per_page: dtParams.length,
                page: (dtParams.start / dtParams.length) + 1,
              };
              
              this.voucherService.getVoucher(params).subscribe((res: any) => {
                const { list, meta } = res.data;
         
                let number = dtParams.start + 1;
                list.forEach(val => {
                  val.no = number++;
                });
                this.listVoucher  = list;
         
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
 
    createVoucher(modal) {
        this.titleModal = 'Tambah Voucher';
        this.modelId = 0;
        this.modalService.open(modal, { size: 'lg', backdrop: 'static' });
    }

    updateVoucher(modal, VoucherModel) {
      this.titleModal = 'Edit Voucher: ' + VoucherModel.customer;
      this.modelId = VoucherModel.id_voucher;
      this.modalService.open(modal, { size: 'lg', backdrop: 'static' });
    }

    updateStatus(id) {
      Swal.fire({
        title: 'Apakah kamu yakin ?',
        text: 'voucher akan di non-aktifkan ',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#34c38f',
        cancelButtonColor: '#f46a6a',
        confirmButtonText: 'Ya, non-aktifkan  data ini !',
      }).then((result) => {
          if (result.value) {
              this.voucherService.changeStatus(id).subscribe((res: any) => {
                  this.landaService.alertSuccess('Berhasil', res.message);
                  this.reloadDataTable();
              }, err => {
                  console.log(err);
              });
          }
      });
    }

    deleteVoucher(voucherId) {
        Swal.fire({
          title: 'Apakah kamu yakin ?',
          text: 'voucher tidak dapat dipakai jika dihapus',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#34c38f',
          cancelButtonColor: '#f46a6a',
          confirmButtonText: 'Ya, Hapus data ini !',
      }).then((result) => {
          if (result.value) {
              this.voucherService.deleteVoucher(voucherId).subscribe((res: any) => {
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
