import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DaftarPenjualanUserComponent } from './daftar-penjualan-user.component';

describe('DaftarPenjualanUserComponent', () => {
  let component: DaftarPenjualanUserComponent;
  let fixture: ComponentFixture<DaftarPenjualanUserComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DaftarPenjualanUserComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DaftarPenjualanUserComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
