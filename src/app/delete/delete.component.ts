import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { catchError, of } from 'rxjs';

@Component({
  selector: 'app-delete',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, FormsModule],
  templateUrl: './delete.component.html',
  styleUrls: ['./delete.component.css'] // Correct usage of styleUrls
})
export class DeleteComponent {
  CordID: string = '';

  constructor(private http: HttpClient) {}

  deleteItem() {
    const Code = this.CordID;
    console.log(this.CordID);
    this.http.delete(`http://localhost/bo/products_Delete.php?productCode=${Code}`)
      .pipe(
        catchError(error => {
          console.error('Error deleting item', error);
          return of(null); // Handle the error gracefully
        })
      )
      .subscribe(response => {
        // Handle successful delete operation
        console.log('Item deleted successfully', response);
      });
  }
}
