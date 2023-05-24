import { Component, OnInit } from '@angular/core';
import { ProfileService } from '../../services/profile.service';
import { ActivatedRoute } from '@angular/router';
import { Router } from '@angular/router';
import { RoleService } from '../../../roles/services/role-service.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {
  titleModal: string;
  modelId: number;
  let: any;
  id:number;
  data:any;
  roles: {
    id: number,
    nama: string
  };
  formModel: any;
  
usersId:any;
  constructor(
    private ProfileService:ProfileService, 
    private route:ActivatedRoute, 
    private router: Router,
    private roleservice: RoleService,
    private modalService: NgbModal,
   
    ) 
    {}

  ngOnInit(): void {
    this.id = parseInt(this.route.snapshot.params.id);
    
    this.usersId = this.id;
    this.getprofileid(this.usersId);
    this.getRole();
    this.formModel = {
      id: 0,
      nama: '',
      user_roles_id: 0,
      foto: '',
      fotoUrl: '',
      email: '',
      password: '',
      phone_number: null
    };
    // this.updateData();
  }
  
  getprofileid(userId)
  {
    this.ProfileService.getUserById(userId).subscribe((res: any) => {
      this.formModel = res.data;
    }, err => {
        console.log(err);
    });
  }
  // updateData()
  // {
  //  this.ProfileService.updateUser(this.formModel).subscribe(res =>{
  //   this.router.navigate(['home']);
  //  })
  // }
  updateUser(modal, formModel) {
    this.titleModal = 'Edit User: ' + formModel.nama;
    this.modelId = formModel.id;
    this.modalService.open(modal, { size: 'lg', backdrop: 'static' });
  }
  getRole() {
    this.roleservice.getRoles(this.roles).subscribe((res: any) => {
        this.roles = res.data.list;
       
    }, err => {
        console.log(err);
    })
  }
}
