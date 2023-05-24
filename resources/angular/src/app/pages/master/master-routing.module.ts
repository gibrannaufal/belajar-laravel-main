import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DaftarCustomerComponent } from './customers/components/daftar-customer/daftar-customer.component';
import { DaftarItemComponent } from './items/components/daftar-item/daftar-item.component';
import { DaftarRolesComponent } from './roles/components/daftar-roles/daftar-roles.component';
import { DaftarUserComponent } from './users/components/daftar-user/daftar-user.component';
import { ProfileComponent } from './profile/components/profile/profile.component';
import { DaftarPromoComponent } from './promo/components/daftar-promo/daftar-promo.component';
import { DaftarVoucherComponent } from './voucher/components/daftar-voucher/daftar-voucher.component';
import { DaftarDiskonComponent } from './Diskon/components/daftar-diskon/daftar-diskon.component';
import { OrderMenuComponent } from './OrderMenu/components/order-menu/order-menu.component';
import { DaftarPenjualanUserComponent } from './PenjualanUser/Components/daftar-penjualan-user/daftar-penjualan-user.component';
import { RekapPenjualanComponent } from './RekapPenjualan/components/rekap-penjualan/rekap-penjualan.component';

const routes: Routes = [
    { path: 'users', component: DaftarUserComponent },
    { path: 'roles', component: DaftarRolesComponent },
    { path: 'customers', component: DaftarCustomerComponent },
    { path: 'promo', component: DaftarPromoComponent},
    { path: 'items', component: DaftarItemComponent },
    { path: 'diskon', component: DaftarDiskonComponent },
    { path: 'voucher', component: DaftarVoucherComponent },
    { path: 'OrderMenu', component: OrderMenuComponent },
    { path: 'PenjualanUser', component: DaftarPenjualanUserComponent },
    { path: 'RekapPenjualan', component: RekapPenjualanComponent },
    { path: 'profile/:id', component: ProfileComponent },
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class MasterRoutingModule { }
