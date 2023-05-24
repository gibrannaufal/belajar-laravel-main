import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DaftarVoucherComponent } from './daftar-voucher.component';

describe('DaftarVoucherComponent', () => {
  let component: DaftarVoucherComponent;
  let fixture: ComponentFixture<DaftarVoucherComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DaftarVoucherComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DaftarVoucherComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
