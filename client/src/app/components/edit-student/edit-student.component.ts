import { Component } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiStudentsService } from 'src/app/service/api-students.service';

@Component({
  selector: 'app-edit-student',
  templateUrl: './edit-student.component.html',
  styleUrls: ['./edit-student.component.css']
})
export class EditStudentComponent {
  info! : any
  hobbyList: any = ['Painting', ' Hiking','sport',"coding",'Swimming', ' Music']
  val = ''
  data = this.val.split(',')
  HobbyArray : any[] = []
  student_id: any

  hobbies: any
  hbs: any;


  constructor(
    private database: ApiStudentsService,
    private router: Router,
    private formBuilder: FormBuilder,
    private url: ActivatedRoute
  ){
    this.info = this.formBuilder.group({
      id: [],
      first_name: ['', Validators.required],
      last_name: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
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

  ngOnInit(): void {
    const studentId = this.url.snapshot.params['id']; // Assuming 'id' is the parameter name in the URL
    this.database.getSingleStudent(studentId)
      .subscribe((res: any) => {
        console.log('res.data:', res.data); // Log res.data to see its structure
        this.info.patchValue(res.data);

        this.hobbies = res.data.hobbies

        this.hbs = this.hobbies.split(',')
        console.log(this.hbs);
        this.setAuthorized(this.hbs);
  })
}



  editStudent(){
    this.database.edit_student(this.info.value)
    .subscribe((data: any) =>{
      this.router.navigate([''])
    },
    error => {
      alert(error.message)
    }
    )
  }
}
