import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ɵInternalFormsSharedModule } from '@angular/forms';

import {
    NgbModule,
    NgbTooltipModule,
    NgbModalModule
} from '@ng-bootstrap/ng-bootstrap';
import { NgSelectModule } from '@ng-select/ng-select';
import { DataTablesModule } from 'angular-datatables';

import { MasterRoutingModule } from './master-routing.module';
import { DaftarUserComponent } from './users/components/daftar-user/daftar-user.component';
import { FormUserComponent } from './users/components/form-user/form-user.component';
import { DaftarRolesComponent } from './roles/components/daftar-roles/daftar-roles.component';
import { FormRolesComponent } from './roles/components/form-roles/form-roles.component';
import { DaftarCustomerComponent } from './customers/components/daftar-customer/daftar-customer.component';
import { FormCustomerComponent } from './customers/components/form-customer/form-customer.component';
import { FormItemComponent } from './items/components/form-item/form-item.component';
import { DaftarItemComponent } from './items/components/daftar-item/daftar-item.component';
import { formShared } from '../formShared/formShared';
import { ProfileComponent } from './profile/components/profile/profile.component';
import { FormProfileComponent } from './profile/components/form-profile/form-profile.component';
import { FormPromoComponent } from './promo/components/form-promo/form-promo.component';
import { DaftarVoucherComponent } from './voucher/components/daftar-voucher/daftar-voucher.component';
import { FormVoucherComponent } from './voucher/components/form-voucher/form-voucher.component';
import { DaftarPromoComponent } from './promo/components/daftar-promo/daftar-promo.component';
import { DaftarDiskonComponent } from './Diskon/components/daftar-diskon/daftar-diskon.component';
import { OrderMenuComponent } from './OrderMenu/components/order-menu/order-menu.component';
import { DaftarPenjualanUserComponent } from './PenjualanUser/Components/daftar-penjualan-user/daftar-penjualan-user.component';
import { RekapPenjualanComponent } from './RekapPenjualan/components/rekap-penjualan/rekap-penjualan.component';


@NgModule({
    declarations: [DaftarUserComponent, FormUserComponent, DaftarRolesComponent, FormRolesComponent, DaftarCustomerComponent, FormCustomerComponent, FormItemComponent, DaftarItemComponent, ProfileComponent, FormProfileComponent, FormPromoComponent, DaftarVoucherComponent, FormVoucherComponent, DaftarPromoComponent, DaftarDiskonComponent, OrderMenuComponent, DaftarPenjualanUserComponent, RekapPenjualanComponent],

    imports: [
        CommonModule,
        MasterRoutingModule,
        NgbModule,
        NgbTooltipModule,
        NgbModalModule,
        NgSelectModule,
        FormsModule,
        DataTablesModule,
        formShared
    ]

})
export class MasterModule { }
