import {Component, OnInit} from '@angular/core';
import {Product} from '../core/models/product.model';
import {ApiService} from '../core/services/api.service';
import {CommonModule} from '@angular/common';

@Component({
  selector: 'app-product-list',
  imports: [CommonModule],
  templateUrl: './product-list.component.html',
  standalone: true,
  styleUrl: './product-list.component.css'
})
export class ProductListComponent implements OnInit {
  products: Product[] = [];
  loading: boolean = true;

  constructor(private apiService: ApiService) {
  }

  ngOnInit() {
    this.apiService.getProducts().subscribe(
      products => this.products = products
    );
  }
}
