import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RekapPenjualanComponent } from './rekap-penjualan.component';

describe('RekapPenjualanComponent', () => {
  let component: RekapPenjualanComponent;
  let fixture: ComponentFixture<RekapPenjualanComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RekapPenjualanComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RekapPenjualanComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
