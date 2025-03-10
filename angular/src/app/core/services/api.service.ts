import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {Product} from '../models/product.model';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private apiUrl = 'https://127.0.0.1:8000';

  constructor(private http: HttpClient) { }

  getProducts(): Observable<Product[]> {
    return this.http.get<Product[]>(`${this.apiUrl}/api/products`);
  }
}
