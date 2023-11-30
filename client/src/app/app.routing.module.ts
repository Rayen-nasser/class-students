import { RouterModule, Routes } from '@angular/router';
import { ListStudentsComponent } from './components/list-students/list-students.component';
import { AddStudentComponent } from './components/add-student/add-student.component';
import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common'
import { EditStudentComponent } from './components/edit-student/edit-student.component';

export const routes: Routes = [
  { path: '', component: ListStudentsComponent , pathMatch: 'full'},
  {path: 'add-student', component: AddStudentComponent },
  {path: 'edit/:id', component: EditStudentComponent}
];

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forRoot(routes)
  ],
  exports:[
     RouterModule,
  ],
  declarations: []
 })

 export class AppRoutingModule{}
