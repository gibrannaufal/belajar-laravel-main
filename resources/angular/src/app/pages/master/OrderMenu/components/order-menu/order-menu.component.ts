import { Component, OnInit } from '@angular/core';
import { NgbAlertModule, NgbCalendar, NgbDate, NgbDateParserFormatter, NgbDatepickerModule, NgbDateStruct } from '@ng-bootstrap/ng-bootstrap';

import { OrderMenuService } from '../../services/order-menu.service';
import { DataTableDirective } from 'angular-datatables';
import { ViewChild } from '@angular/core';

@Component({
  selector: 'app-order-menu',
  templateUrl: './order-menu.component.html',
  styleUrls: ['./order-menu.component.scss']
})
export class OrderMenuComponent implements OnInit {
  model: NgbDateStruct;
  listOrderMenu: [];
  orderMenuArray: { key: string, value: any[] }[] = [];
  grandtotalArray: { key: string, value: any[] }[] = [];
  GrandTotal: any;
  titleModal: string;
  modelId: number;
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: Promise<DataTables.Api>;
  dtOptions: any;
  listKategori: string[];
  imageSource:any;
  filter: {
      kategori: string, 
    };
    days: number[] = [];
    periode: any;
    dateTotal = 0;
    oldDateTotal = 0;
    prevMonth: number;
    isOpenForm: boolean = false;
  
    constructor(
      private OrderMenuService: OrderMenuService,
      public formatter: NgbDateParserFormatter,
      private calendar: NgbCalendar
    
    ) { 
        this.fromDate = calendar.getToday();
      }
      hoveredDate: NgbDate | null = null;

  fromDate: NgbDate | null;
  toDate: NgbDate | null;
  
  ngOnInit(): void {
    this.filter = {
      kategori: '',
    };
    var nowDate = new Date();
    var month = this.getMonth(nowDate.getMonth());
    this.periode = nowDate.getFullYear() +'-'+ month;
    this.getOrder();
  }

  getObjectKeys(obj: any): { key: number, value: any, object: any }[] {
    return Object.entries(obj).map(([key, value]) => ({
      key: parseInt(key.split('-')[2]), // Mengambil tanggal dari kunci (key)
      value,
      object: obj
    }));
  }

  parseInteger(value: string): number {
    // console.log(typeof parseInt(value, 10));
    return parseInt(value, 10);
  }
  

  daysInMonth (month, year) {
    return new Date(year, month, 0).getDate();
  }

  counter(i: number) {
    return new Array(i);
  }
    getMonth(month){
      var datMonth = month + 1;
      if (datMonth.toString().length == 1) {
          return "0" + datMonth;
      }else{
          return datMonth;
      }
  }

  getOrder() {
    const params = {
     kategori: this.filter.kategori,
     bulan: this.periode,

   }
   this.listOrderMenu = [];
   this.days = [];

   var date = new Date(this.periode);
   this.oldDateTotal = this.dateTotal;
   this.dateTotal = this.daysInMonth(date.getMonth() + 1, date.getFullYear());
   
   let monthArray = [
     'Januari', 'Februari', 'Maret', 'April',
     'Mei', 'Juni', 'Juli', 'Agustus',
     'September', 'Oktober', 'November', 'Desember'
   ];
   var periodDate = this.periode.split('-');
   
   const thead = document.querySelector('thead tr.lower-head');
   
   document.getElementById("menu-head").setAttribute("rowspan", "2");
   document.getElementById("period-head").setAttribute("colspan", String(this.dateTotal));
   document.getElementById("total-head").setAttribute("rowspan", "2");
   document.getElementById("period-head").innerHTML = "Periode: "+ monthArray[parseInt(periodDate[1])-1] +" "+periodDate[0];

   for (let i = 1; i <= this.dateTotal; i++) {
     this.days.push(i);
   }

   for (let i = 1; i <= this.dateTotal; i++) {
     const dateHeader = document.createElement('th');
     
     dateHeader.innerHTML = String(i);
     dateHeader.className = "head-id-"+i;
     dateHeader.style.cssText = 'white-space: nowrap;width: 1%;';
     thead.appendChild(dateHeader);
   }
   
   for(let i = 1; i <= this.oldDateTotal; i++){
     let headClass = document.querySelectorAll(".head-id-"+i);
     if(headClass.length >= 1){
       headClass[0].remove();
       // console.log('done '+i);
     }
   }
 
   this.OrderMenuService.getOrderMenu(params).subscribe(
     (res: any) => {
       const { list, total } = res.data;
       
       this.GrandTotal = total;
       console.log(this.GrandTotal);

    
       this.listOrderMenu = list;
      //  console.log(this.listOrderMenu);

       this.orderMenuArray = Object.entries(this.listOrderMenu).map(([key, value]) => ({ key, value: Object.values(value) })) as { key: string, value: any[] }[];
 
      //  console.log(this.orderMenuArray);
     },
     (err: any) => {
       console.log(err);
     }
   );
 
   this.dtOptions = {
     serverSide: false, // Tidak menggunakan server-side processing
     processing: true,
     ordering: false,
     paging: false, // Tidak menggunakan paging
     info: false
   };
 }
  updateFilter(kategori: string) {
    this.filter.kategori = kategori;
    this.getOrder();
  }
  exportExcel()
  {
    const params = {
      kategori: this.filter.kategori,
      bulan: this.periode,
      hari: this.dateTotal
    };
    this.OrderMenuService.exportExcel(params).subscribe(
      (res) => {
        const file = new Blob([res], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const fileURL = URL.createObjectURL(file);
        window.open(fileURL);
        const a = document.createElement('a');
        a.href = fileURL;
        a.target = '_blank';
        a.download = 'penjualanMenu.xlsx'; // Mengatur nama file
        document.body.appendChild(a);
        a.click();
      },
      (error) => {
        console.log('generateExcel error: ', error);
      }
    );
  }
  generatePDF() {
    const params = {
      kategori: this.filter.kategori,
      bulan: this.periode,
      hari: this.dateTotal
    };
    this.OrderMenuService.generatePDF(params).subscribe(
      (res) => {
        var file = new Blob([res], { type: 'application/pdf' })
        var fileURL = URL.createObjectURL(file)
        window.open(fileURL)
        var a = document.createElement('a')
        a.href = fileURL;
        a.target = '_blank';
        a.download = 'penjualanMenu.pdf'; // Mengatur nama file
        document.body.appendChild(a)
        a.click()
      },
      (error) => {
        console.log('getPDF error: ', error)
      },
    )
  }
  

}
