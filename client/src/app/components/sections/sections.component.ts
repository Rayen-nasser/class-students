import { Component } from '@angular/core';
import { ApiStudentsService } from 'src/app/service/api-students.service';
import { Students } from 'src/app/modules/students';

@Component({
  selector: 'app-sections',
  templateUrl: './sections.component.html',
  styleUrls: ['./sections.component.css']
})
export class SectionsComponent {
  sectionId: string = ''; // Store selected section ID
  students: any[] = [];

  constructor(private data: ApiStudentsService) {}

  onChange(event: Event): void {
    const selectedSectionId = (event.target as HTMLSelectElement).value;
    this.sectionId = selectedSectionId;
    if (this.sectionId) {
      this.getStudents();
    }
  }

  ngOnInit(): void{
    this.getStudents();
  }
  getStudents(): void {
    this.data.getStudentsByClass(this.sectionId).subscribe(
      (data: any) => {
        console.log(data.data); // Log the received data
        this.students = data.data;
        console.log(this.students); // Log the populated students array
      },
      (error: any) => {
        console.error(error);
      }
    );
  }


}
