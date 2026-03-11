# Laravel + Vue.js Ecommerce Backend

🚀 Production-ready Laravel Ecommerce Backend API for modern Vue.js applications.

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![License](https://img.shields.io/badge/License-MIT-green)

A scalable and secure **Ecommerce Backend API** built with **Laravel**, designed to power a modern **Vue.js frontend**.  
This project covers complete e-commerce functionality including authentication, product management, cart, wishlist, invoices, and payment handling.

👉 **Frontend:** Built with **Vue.js**  
🔗 Frontend Repository: https://github.com/sohelsamy1/laravel-vuejs-ecommerce-frontend 

---

## 🚀 Features

- 🔐 JWT-based user authentication
- 👤 User profile management
- 🏷 Brand & category management
- 📦 Product listing, filtering & details
- ❤️ Wishlist functionality
- 🛒 Shopping cart management
- 🧾 Invoice generation & listing
- 💳 Payment success, cancel & fail handling
- 🔒 Protected routes using custom middleware
- 📊 Clean and scalable MVC architecture

---

## 🛠 Tech Stack

**Backend**
- Laravel
- PHP
- REST API
- JWT Authentication
- MVC Architecture

**Database**
- MySQL

**Tools**
- Git & GitHub
- Postman (API testing)

---

## 📂 Project Structure (Key Parts)

- `app/Http/Controllers` – All API controllers
- `app/Http/Middleware` – JWT authentication middleware
- `routes/api.php` – API route definitions
- `app/Models` – Eloquent models
- `database/` – Migrations & seeders

---

## 🔗 API Highlights

```http
GET /BrandList
GET /CategoryList
GET /ListProductByCategory/{id}

POST /CreateCartList
GET /CartList

POST /CreateWishList/{product_id}

POST /InvoiceCreate

GET /PaymentSuccess
GET /PaymentCancel
GET /PaymentFail
```
_All sensitive routes are protected using JWT middleware._

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Apple iPhone 15",
      "price": 1200
    }
  ]
}
```
---

## 📸 Screenshots

> Screenshots will be added here  
> (Product list, Cart, Wishlist, Invoice, Payment flow)

---

## 🎥 Demo Video

> Project demo video will be added here

---

## ⚙️ Installation & Setup

```bash
git clone https://github.com/sohelsamy1/laravel-vuejs-ecommerce-backend.git
cd laravel-vuejs-ecommerce-backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
---

## 📌 Notes

- Frontend (Vue.js) is maintained in a separate repository
- This backend is API-ready and scalable for production use

---

## 👤 Author

**Sohel Samy**   
Laravel | Vue | React Developer   
GitHub: https://github.com/sohelsamy1   
LinkedIn: https://linkedin.com/in/sohelsamy

