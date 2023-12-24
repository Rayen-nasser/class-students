import { Component } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import {  Router } from '@angular/router';
import { first } from 'rxjs';
import { Students } from 'src/app/modules/students';
import { ApiStudentsService } from 'src/app/service/api-students.service';

@Component({
  selector: 'app-add-student',
  templateUrl: './add-student.component.html',
  styleUrls: ['./add-student.component.css']
})
export class AddStudentComponent {
  info! : any;
  hobbyList: any = ["coding","sport","design"]
  val = ''
  data = this.val.split(',')
  HobbyArray: any[] = []

  constructor(
    private database: ApiStudentsService,
    private router: Router,
    private formBuilder: FormBuilder
    ){
      this.info = this.formBuilder.group({
        first_name: ['', Validators.required],
        last_name: ['', Validators.required],
        email: ['', Validators.required],
        profile: ['', Validators.required],
        gender: ['', Validators.required],
        hobbyField: new FormControl(this.data),
        section: ['', Validators.required]

      })
    }

    get authorizedArray(){

      return (this.info.get("hobbyField") as FormArray)
    }

    setAuthorized(data: string[]){
      this.HobbyArray = this.hobbyList.map((x: any)=>({
        name: x,
        value: data.indexOf(x) >= 0
      }))
    }

    parse(){
      const result = this.hobbyList.map(
        (x: any ,index: any) => this.HobbyArray[index].value ? x : null
      ).filter((x: any) => x)

      return result.length > 0 ? result : null
    }

    ngOnInit() : void{
      this.setAuthorized(this.data)
      localStorage.setItem("students",this.info)
    }

    sendInfo() {
      this.database.add_student(this.info.value)
        .subscribe((data: any) => {
          alert(data.message);
          this.router.navigate(['/'])
          // Reset the form
          this.info.reset(); // This will clear all form controls
        },
        error => {
          console.log(error);
        }
        );
    }


}
