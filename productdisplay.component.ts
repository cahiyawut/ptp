import { Component, Input, OnInit } from '@angular/core';
import { ReactiveFormsModule, FormGroup, FormControl } from '@angular/forms';
import { HttpClient, HttpParams } from '@angular/common/http';
import { catchError } from 'rxjs/operators';
import { of } from 'rxjs';
import { ProductProfile } from '../product/product.component';

@Component({
  selector: 'app-product-detail',
  standalone: true,
  imports: [ReactiveFormsModule],
  templateUrl: './productdisplay.component.html',
  styleUrls: ['./productdisplay.component.css']
})
export class ProductDisplayComponent implements OnInit {
  @Input() productCode: string = ''; // Receive productCode as input
  productProfileForm = new FormGroup({
    c1: new FormControl(''), // Read-only
    c2: new FormControl(''),
    c3: new FormControl(''),
    c4: new FormControl(''),
    c5: new FormControl(''),
    c6: new FormControl(''),
    c7: new FormControl<number | null>(null), // Numeric value, can be null
    c8: new FormControl<number | null>(null), // Numeric value, can be null
    c9: new FormControl<number | null>(null),
  });

  constructor(private http: HttpClient) { }

  products$: ProductProfile[] | null = null;
  searchkey: string = ''; // Use string for simplicity

  ngOnInit() {
    if (this.productCode) {
      this.getProducts2();
    }
  }

  getProducts2() {
    this.http.post<ProductProfile[]>(
      'http://localhost/bo/products_select.php',
      this.searchkey,
      {
        headers: { "Content-Type": "application/json; charset=UTF-8" }
      }
    ).subscribe((resp: ProductProfile[]) => {
      this.products$ = resp;
    });
  }
  updateProduct() {
    const headers = { 'Content-Type': 'application/json' };
    this.http.put('http://localhost/bo/products_update.php', this.productProfileForm.value, { headers })
      .subscribe(response => {
        console.log('Product updated successfully', response);
      });
  }
}
