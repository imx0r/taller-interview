# taller code challange

## How to start
1. Go to `symfony` folder, open a cmd and type `symfony server:start`
2. Go to `angular` folder, open a cmd and type `ng serve`

## How to access

### Frontend
Open `http://localhost:4200` on your browser to access the frontend.

### Backend
For the backend we have the base URL: `https://127.0.0.1:8000/api` for the following routes:

| PATH               | VERB   | PARAMETERS       | DESCRIPTION          |
|--------------------|--------|------------------|----------------------|
| /api/products      | GET    |                  | Get all products     |
| /api/products      | POST   |                  | Create a product     |
| /api/products/{id} | GET    | `id`: Product ID | Get a single Product |
| /api/products/{id} | PUT    | `id`: Product ID | Update an Product    |
| /api/products/{id} | DELETE | `id`: Product ID | Delete an Product    |

