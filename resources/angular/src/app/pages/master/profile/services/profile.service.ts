import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
  providedIn: 'root'
})
export class ProfileService {

  constructor(private landaService: LandaService) { }
  
  getUserById(userId) {
    return this.landaService.DataGet('/v1/profile/' + userId);
  }
  updateUser(payload) 
  {
    return this.landaService.DataPut('/v1/profile/update', payload);
  }
}
