import { Component, OnInit } from '@angular/core';
import * as Chart from 'chart.js';
import { DashboardService } from './services/dashboard.service';
import { DataTableDirective } from 'angular-datatables';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {

  listData: any[] = [];
  filter = {
    bulan: ''
  };
  prevMonth: number;
  nowMonth: number;
  today: number;
  yesterday: number;
  dtElement: DataTableDirective;
  dtInstance: Promise<DataTables.Api>;
  constructor(
    private dashboardService: DashboardService,
   
  ){}

  ngOnInit(): void {
    this.getData();
    const currentDate = new Date();
    const day = String(currentDate.getDate()).padStart(2, '0');
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const year = currentDate.getFullYear();
    
    this.filter.bulan = `${year}-${month}-${day}`;

  }
  chart: Chart;
  


  ngAfterViewInit() {
    const canvas = document.getElementById('myChart') as HTMLCanvasElement;
    const ctx = canvas.getContext('2d');
  
    this.chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
          label: 'Data Statistik Penjualan',
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
          backgroundColor: ' #009aad'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false
            }
          }],
          yAxes: [{
            gridLines: {
              display: true
            },
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });
  
    this.getData();
  }
  
  getData() {
    const params = {
      filter: this.filter.bulan
    };
  
    this.dashboardService.getDashboard(params).subscribe((res: any) => {
      const monthlyData = res.data[0]; 
       this.prevMonth = res.data[1];
        this.nowMonth = res.data[2];
        this.today = res.data[3];
        this.yesterday = res.data[4];
        
      // console.log(this.nowMonth);
      const data = Object.values(monthlyData); 
  
      if (this.chart) {
        this.chart.data.datasets[0].data = data; // Perbarui nilai data dalam objek dataset chart
        this.chart.update(); // Perbarui tampilan chart
      }
    }, err => {
      console.log(err);
    });
  }
  

}
