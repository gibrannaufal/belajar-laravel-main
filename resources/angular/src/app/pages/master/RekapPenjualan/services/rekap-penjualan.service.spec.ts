import { TestBed } from '@angular/core/testing';

import { RekapPenjualanService } from './rekap-penjualan.service';

describe('RekapPenjualanService', () => {
  let service: RekapPenjualanService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(RekapPenjualanService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
