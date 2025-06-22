# 🚚 Smart Order Management System

Laravel-based backend application that streamlines **order lifecycle management** with role-based functionality for **Admins**, **Riders**, and **Customers**. The project integrates advanced features like **Firebase Authentication**, **Role-specific APIs**, **status-based order tracking**, and **feedback handling**, along with proper **validation**, **error management**, and **transaction-safe database operations**.

---

## 🎯 Project Objectives

This system is developed to demonstrate:
- Order lifecycle flow and role-based task allocation
- Firebase Authentication for real-time updates
- Form request validation and resource collection responses


---

## 👥 System Roles

### 👤 Admin

- Can:
  - Create Riders and Customers
  - Assign Orders to Riders
  - Trigger Authentication to Riders

### 🚴 Rider
- Can **sign up** or be added by Admin
- Lifecycle Actions:
  - Accept Order
  - Mark as Picked
  - Start Ride (On My Way)
  - Mark as Delivered

### 🛍️ Customer
- Can **sign up** or be added by Admin
- Features:
  - Can view live order status
  - Submit feedback after delivery
  - Order is marked **COMPLETED** after feedback

---

## 🔁 Order Lifecycle

1. **Order Created** → by Admin for a Rider  
2. **Order Accepted** → by Rider  
3. **Order Picked** → after packing  
4. **On My Way** → rider starts delivery  
5. **Delivered** → rider completes delivery  
6. **Completed** → customer submits feedback  
7. Customers can **track order status anytime**

---

## 🧰 Tech Stack

- **Framework**: Laravel (PHP)
- **Database**: MySQL 
- **Authentication**: Firebase
- **API Testing**: Postman
- **Authentication**: Laravel Sanctum

---

## 📡 API Endpoints

### 🔐 Authentication
- `POST /api/signin` – Login
- `POST /api/signup` – Sign up

### 👤 User Profile
- `POST /api/updateProfile` – Update profile details

### 📦 Order Management
- `GET /api/order/allUserOrder?status=ALL`  
  Retrieve all orders (status filter: `ALL`, `PENDING`, `ACCEPTED`, `PICKED`, `ON_MY_WAY`, `DELIVERED`, `COMPLETED`)

- `GET /api/order/{orderId}`  
  Retrieve single order detail with complete status

- `PUT /api/order/{orderId}/edit`  
  Admin can edit order (status must be `PENDING`)

- `PUT /api/order/updateOrder`  
  Rider can update order status (from accepted to delivered)

---

## ⚙️ Setup Instructions

### Prerequisites
- PHP 8.x
- Composer
- MySQL
- Laravel CLI

### Installation
```bash
# Clone the repository
git clone https://github.com/yourusername/orderlygo.git

# Install dependencies
composer install

php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Serve application
php artisan serve
```

---

## 📦 Suggested Improvements

- 🔐 Two-factor authentication for Admin
- 📊 Dashboard with real-time order analytics
- 📍 Rider location tracking (using GPS)
- 📱 Mobile frontend (React Native or Flutter)
- 🌐 Swagger API documentation

---



## 📝 License

This project is open-source and available under the **MIT License**.
