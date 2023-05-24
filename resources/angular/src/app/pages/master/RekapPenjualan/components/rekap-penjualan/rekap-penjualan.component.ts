import { Component, OnInit, ViewChild } from '@angular/core';
import { NgbCalendar, NgbDate, NgbDateParserFormatter, NgbDateStruct, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LandaService } from 'src/app/core/services/landa.service';
import { RekapPenjualanService } from '../../services/rekap-penjualan.service';
import { DataTableDirective } from 'angular-datatables';


@Component({
  selector: 'app-rekap-penjualan',
  templateUrl: './rekap-penjualan.component.html',
  styleUrls: ['./rekap-penjualan.component.scss']
})
export class RekapPenjualanComponent implements OnInit {

  model: NgbDateStruct;
  listPenjualan= [];
  titleCard: string;
  modelId: number;
  isOpenForm: boolean = false;
  filter = {
      nama: ''
  };
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: Promise<DataTables.Api>;
  dtOptions: any;
  constructor(
      private rekapPenjualanService: RekapPenjualanService,
      private landaService: LandaService,
      private calendar: NgbCalendar, 
      public formatter: NgbDateParserFormatter
  ){
      this.fromDate = calendar.getToday();
  this.toDate = calendar.getNext(calendar.getToday(), 'd', 10);
  }
  hoveredDate: NgbDate | null = null;

fromDate: NgbDate | null;
toDate: NgbDate | null;

  ngOnInit(): void {
      this.filter = {
          nama: ''
      };
      this.getPenjualan();
  }

onDateSelection(date: NgbDate) {
  if (!this.fromDate && !this.toDate) {
    this.fromDate = date;
  } else if (this.fromDate && !this.toDate && date && date.after(this.fromDate)) {
    this.toDate = date;
  } else {
    this.toDate = null;
    this.fromDate = date;
  }
}

isHovered(date: NgbDate) {
  return (
    this.fromDate && !this.toDate && this.hoveredDate && date.after(this.fromDate) && date.before(this.hoveredDate)
  );
}

isInside(date: NgbDate) {
  return this.toDate && date.after(this.fromDate) && date.before(this.toDate);
}

isRange(date: NgbDate) {
  return (
    date.equals(this.fromDate) ||
    (this.toDate && date.equals(this.toDate)) ||
    this.isInside(date) ||
    this.isHovered(date)
  );
}

  reloadDataTable(): void {
      this.dtElement.dtInstance.then(
          (dtInstance: DataTables.Api) => {dtInstance.draw();}
      );
  }

  getPenjualan() {
      this.dtOptions = {
          serverSide: true,
          processing: true,
          ordering: true,
          pageLength: 10,
          ajax: (dtParams: any, callback) => {
              const params = {
                  nama: this.filter.nama,
                  per_page: dtParams.length,
                  page: (dtParams.start / dtParams.length) + 1,
              };
              const thead = document.querySelector('thead tr');
              const tfoot = document.querySelector('tfoot tr');

              this.rekapPenjualanService.getRekapPenjualan(params).subscribe((res: any) => {
                  const { list, meta } = res.data;
                  let number = dtParams.start + 1;

                  list.forEach(item => {
                      item.no = number++;
                  });
                  this.listPenjualan = list;
                  
                  let totalMenu = 0;
                  let totalDiskon = 0;
                  let totalVoucher = 0;
                  let totalBayar = 0;
                  let dataMenu = [];

                  for(let i = 0; i < this.listPenjualan.length; i++){
                      dataMenu = this.listPenjualan[i].data_detail;

                      for(let j = 0; j < dataMenu.length; j++){
                          totalMenu = totalMenu + dataMenu[j].total;
                      }

                      totalBayar = totalBayar + this.listPenjualan[i].total_bayar;
                      if(this.listPenjualan[i].potongan > 0){
                          totalDiskon = totalDiskon + this.listPenjualan[i].potongan;
                      }

                      if(this.listPenjualan[i].id_voucher){
                          totalVoucher = totalVoucher + this.listPenjualan[i].potongan;
                      }
                  }

                  const menuFoot = document.createElement('th');
                  const diskonFoot = document.createElement('th');
                  const voucherFoot = document.createElement('th');
                  const bayarFoot = document.createElement('th');

                  menuFoot.className = "vertical-middle text-center total-menu";
                  diskonFoot.className = "vertical-middle text-center total-diskon";
                  voucherFoot.className = "vertical-middle text-center total-voucher";
                  bayarFoot.className = "vertical-middle text-center total-bayar";

                  menuFoot.innerHTML = 'Rp. '+totalMenu.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'.00';
                  diskonFoot.innerHTML = 'Rp. '+totalDiskon.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'.00';
                  voucherFoot.innerHTML = 'Rp. '+totalVoucher.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'.00';
                  bayarFoot.innerHTML = 'Rp. '+totalBayar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'.00';

                  tfoot.appendChild(menuFoot);
                  tfoot.appendChild(diskonFoot);
                  tfoot.appendChild(voucherFoot);
                  tfoot.appendChild(bayarFoot);

                  let menuClass = document.querySelectorAll(".total-menu");
                  if(menuClass.length > 1){
                      menuClass[0].remove();
                  }

                  let diskonClass = document.querySelectorAll(".total-diskon");
                  if(diskonClass.length > 1){
                      diskonClass[0].remove();
                  }

                  let voucherClass = document.querySelectorAll(".total-voucher");
                  if(voucherClass.length > 1){
                      voucherClass[0].remove();
                  }

                  let bayarClass = document.querySelectorAll(".total-bayar");
                  if(bayarClass.length > 1){
                      bayarClass[0].remove();
                  }
                  callback({
                      recordsTotal: meta.total,
                      recordsFiltered: meta.total,
                      data: [],
                  });
              }, (err: any) => {
          
              });
          },
      };
  }

}
