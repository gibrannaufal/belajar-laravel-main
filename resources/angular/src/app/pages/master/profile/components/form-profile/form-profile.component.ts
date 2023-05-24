import { Component, Input, OnInit, Output, SimpleChange, EventEmitter } from '@angular/core';

import { LandaService } from 'src/app/core/services/landa.service';
import { RoleService } from '../../../roles/services/role-service.service';
import { ProfileService } from '../../services/profile.service';

@Component({
  selector: 'app-form-profile',
  templateUrl: './form-profile.component.html',
  styleUrls: ['./form-profile.component.scss']
})
export class FormProfileComponent implements OnInit {
  @Input() userId: number;
  @Output() afterSave  = new EventEmitter<boolean>();
  mode: string;
  roles: [];
  formModel : {
      id: number,
      nama: string,
      foto: string,
      fotoUrl: string,
      email: string,
      password: string,
      user_roles_id:number,
      phone_number: number,
  }


  constructor(
      private profileservice:ProfileService,
      private roleService: RoleService,
      private landaService: LandaService
  ) {}

  ngOnInit(): void {
  }
  
  ngOnChanges(changes: SimpleChange) {
      this.emptyForm();
  }

  emptyForm() {
      this.mode = 'add';
      this.formModel = {
          id: 0,
          nama: '',
          foto: '',
          fotoUrl: '',
          email: '',
          password: '',
          user_roles_id: 0,
          phone_number: null
      }

      if (this.userId > 0) {
          this.mode = 'edit';
          this.getUser(this.userId);
      }
  }

  save() {
      if(this.mode == 'add') 
      {
          this.profileservice.updateUser(this.formModel).subscribe((res : any) => {
              this.landaService.alertSuccess('Berhasil', res.message);
              this.afterSave.emit();
              location.reload();
              
          }, err => {
              this.landaService.alertError('Mohon Maaf', err.error.errors);
          });
      }else {
        this.profileservice.updateUser(this.formModel).subscribe((res : any) => {
            this.landaService.alertSuccess('Berhasil', res.message);
            this.afterSave.emit();
            location.reload();
        }, err => {
            this.landaService.alertError('Mohon Maaf', err.error.errors);
        });
    }
  }

 

  getUser(userId) {
      this.profileservice.getUserById(userId).subscribe((res: any) => {
          this.formModel = res.data;
      }, err => {
          console.log(err);
      });
  }
  getCroppedImage($event) {
      this.formModel.foto = $event;
     }

}
