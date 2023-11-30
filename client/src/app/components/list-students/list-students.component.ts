import { Component } from '@angular/core';
import { Students } from 'src/app/modules/students';
import { ApiStudentsService } from 'src/app/service/api-students.service';

@Component({
  selector: 'app-list-students',
  templateUrl: './list-students.component.html',
  styleUrls: ['./list-students.component.css']
})
export class ListStudentsComponent {
  constructor(private data: ApiStudentsService){}
  students : Students[] = []
  ngOnInit(): void{
    this.data.getStudents().subscribe(
      (data: any) => {
        this.students = data.data
        console.log(data.data);

      }
    )
  }

  deleteStudent(student: Students){
    this.data.delete_student(student.id).subscribe(
      (data) =>{
        this.students = this.students.filter((students :any) => students !== student)
      }
    )
  }
}
