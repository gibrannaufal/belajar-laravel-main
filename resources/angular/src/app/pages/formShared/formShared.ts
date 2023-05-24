import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ImageCropperModule  } from 'ngx-image-cropper';
import { UploadImageComponent } from './upload-image/upload-image.component';

@NgModule({
  declarations: [UploadImageComponent],
  imports: [
    CommonModule,
    ImageCropperModule
  ],
  exports: [
    UploadImageComponent
  ]
 
})
export class formShared { }
