import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LandaService } from 'src/app/core/services/landa.service';
import Swal from 'sweetalert2';
import { DiskonService } from '../../services/diskon.service';
import { UserService } from '../../../users/services/user-service.service';
import { DataTableDirective } from 'angular-datatables';
import { ViewChild } from '@angular/core';
import { PromoService } from '../../../promo/services/promo.service';

@Component({
  selector: 'app-daftar-diskon',
  templateUrl: './daftar-diskon.component.html',
  styleUrls: ['./daftar-diskon.component.scss']
})
export class DaftarDiskonComponent implements OnInit {

  listCustomer: [];
    titleModal: string;
    modelId: number;
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;
    promo: any[];
    dataSend: {
      id_diskon: number,
      id_promo: number,
      id_user: number,
      status: number,
    };
    imageSource:any;
    filter: {
        nama: ''
      };
    thead: any;
    constructor(
        private diskonService: DiskonService,
        private promoService: PromoService,
        private userService: UserService,
        private landaService: LandaService,
        private modalService: NgbModal
    ) { }

    ngOnInit(): void {
      this.filter = {
        nama: '',
      
      };
        this.getUser();
        this.getPromo();
        
    }
   
    getPromo() {
      this.promoService.getPromo([]).subscribe((res: any) => {
         // untuk mengambil getPromo dengan type diskon saja 
        this.promo = res.data.list.filter((promo: any) => promo.type === 'diskon');
        
      }, err => {
        console.log(err);
      });
    }

    getUser() {
      this.dtOptions = {
        serverSide: true,
        processing: true,
        ordering: false,
        pageLength: 4,
        // menambah thead karena thead tidak bisa menggunakan ngfor thead berisi promo dari
        // getPromo
        initComplete: (settings: any, json: any) => {
          const thead = document.querySelector('thead tr') as HTMLTableSectionElement;
          const noHeader = document.createElement('th');
          noHeader.innerHTML = 'No';
          thead.insertBefore(noHeader, thead.firstChild);
          for (let i = 0; i < this.promo.length; i++) {
            const promoHeader = document.createElement('th');
            promoHeader.innerHTML = this.promo[i].nama;
            promoHeader.style.textAlign = 'center';
            thead.appendChild(promoHeader);
          }
          thead.style.textAlign = 'center';
        },
        
        ajax: (dtParams: any, callback) => {
          const params = {
            nama: this.filter.nama,
            itemperpage: 4,
            per_page: dtParams.length,
            page: (dtParams.start / dtParams.length) + 1,
          };
                  
          this.diskonService.getDiskon(params).subscribe((res: any) => {
            const { list, meta } = res.data;
             
            let number = dtParams.start + 1;
            list.forEach(val => {
              val.no = number++;
              // mengecek user bila diskon null maka masukan promo dengan status 0 untuk sementara
              if (!val.diskon) {
                val.diskon = this.promo.map(promoItem => {
                  return { id_diskon: 0, id_promo: promoItem.id_promo, status: 0, id_user: val.id };
                });
              }else {
                // mengecek user bila ada diskon yang sudah terisi status 1 maka promo yang
                // belum tercentang maka dibuatkan diskon dengan status 0 dan dengan id_promo
                // yang belum tercentang untuk sementara
                this.promo.forEach(promoItem => {
                  const diskonItem = val.diskon.find((diskon: any) => diskon.id_promo === promoItem.id_promo);
                  if (!diskonItem) {
                    val.diskon.push({ id_diskon: 0, id_promo: promoItem.id_promo, status: 0, id_user: val.id });
                  }
                });
              }
            });

            
            this.listCustomer = list;
            this.getTotal(list);
            
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

    getTotal(list) {
      const tfoot = document.querySelector('tfoot tr') as HTMLTableSectionElement;

       tfoot.innerHTML = ''; // reset tfoot
        const totalHeader = document.createElement('th');
        totalHeader.innerHTML = 'Total';
        totalHeader.style.textAlign = 'center';
        totalHeader.colSpan = 2; 
        tfoot.appendChild(totalHeader);
      
      for (let i = 0; i < this.promo.length; i++) {
        let totalStatus1 = 0;
        for (let j = 0; j < list.length; j++) {
          const val = list[j];
          if (val.diskon) {
            val.diskon.forEach((diskon) => {
              if (diskon.status === 1 && diskon.id_promo === this.promo[i].id_promo) {
                totalStatus1++;
              
              }
            });
          }
      
        }

        const countHeader = document.createElement('th');
        countHeader.innerHTML = `${totalStatus1}`;
        countHeader.style.textAlign = 'center';
        tfoot.appendChild(countHeader);
      }
      
    }


    createDiskon(userId, promoId, diskonId,status) {
      
         this.dataSend = {
                id_diskon: diskonId,
                id_promo: promoId,
                id_user: userId,
                status: status,
              };

        Swal.fire({
            title: 'Apakah kamu yakin akan mencentang ini ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#34c38f',
            cancelButtonColor: '#f46a6a',
            confirmButtonText: 'Ya, centang data ini !',
        }).then((result) => {
            if (result.value && diskonId === 0) {
             
                this.diskonService.createDiskon(this.dataSend).subscribe((res: any) => {
                    this.landaService.alertSuccess('Berhasil', res.message);
                    this.reloadDataTable();
                }, err => {
                    console.log(err);
                });
            }else {
              this.diskonService.updateDiskon(this.dataSend).subscribe((res: any) => {
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
