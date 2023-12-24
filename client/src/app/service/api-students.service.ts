import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http'
import { Students } from '../modules/students';
@Injectable({
  providedIn: 'root'
})
export class ApiStudentsService {

  constructor(private http: HttpClient) {

  }

  url: string = "http://localhost/ngClass/"

  getStudents(){
    return this.http.get<Students[]>(this.url + 'view.php')
  }

  add_student(newStudent: Students){
    return this.http.post<Students>(this.url + "add.php", newStudent)
  }

  delete_student(id: any){
    return this.http.delete<Students>(this.url + 'delete.php?id='+ id)
  }

  getSingleStudent(id: any){
    return this.http.get<Students[]>(this.url + 'view.php?id='+ id)
  }

  edit_student(newStudent: Students){
    return this.http.put<Students>(this.url + "update.php", newStudent)
  }

  getStudentsByClass(id: any){
    return this.http.get<Students[]>(this.url + 'studentsByClass.php?section=' + id)
  }

}
