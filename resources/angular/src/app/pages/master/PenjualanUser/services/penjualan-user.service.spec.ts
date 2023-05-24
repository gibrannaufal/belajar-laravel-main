import { TestBed } from '@angular/core/testing';

import { PenjualanUserService } from './penjualan-user.service';

describe('PenjualanUserService', () => {
  let service: PenjualanUserService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PenjualanUserService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
