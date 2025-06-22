# 🚐 Fast Travel Solutions – Taxi Bidding Platform

**Fast Travel Solutions** is a feature-rich, Laravel-powered platform designed to simplify and streamline the **vehicle booking process** for customers, companies, and drivers. It includes secure user authentication, real-time communication, automated fare calculations, document expiry checks, and a flexible administrative backend.

Whether you are a travel company, driver, or customer, this system provides the tools to manage and monitor travel bookings effectively.

---

## 🎯 Purpose

To provide an all-in-one solution for:
- Booking vehicles for travel
- Managing drivers and fleets
- Approving bookings and quotes
- Sending real-time emails/SMS to customers and drivers
- Administering destination and fare data securely

---

## 🔑 Key Features

### 👤 Authentication & Role Management
- User login and registration
- Driver onboarding and approvals
- Admin authentication via API
- Role-based permission control using middleware (`auth:api`, `permission:*`)

### 🚘 Vehicle & Fleet Management
- Retrieve available fleets without login
- Document expiry validation (cron)
- Company-wise vehicle (fleet) approval system
- Dynamic fare management via API

### 🗺️ Booking & Travel
- Quote request and approval for destinations
- Booking tracking and communication
- Real-time booking status management
- Stripe payment integration (assumed from controller)

### ✉️ Communication System
- Auto email notifications (`send_comm_email`)
- Auto SMS notifications (`send_comm_sms`)
- Webhook support for third-party integrations

### 📥 Admin Capabilities
- Approve drivers, companies, quotes, and users
- Add, update, and delete travel destinations
- Manage fare structures dynamically
- View branding settings and downloadable content

---

## 🛠️ Technologies Used

| Layer           | Stack                          |
|----------------|---------------------------------|
| **Backend**     | Laravel (PHP Framework)        |
| **Frontend**    |  React  |
| **Communication**| Email, SMS                    |
| **Database**    | MySQL              |
| **Security**    | Laravel Sanctum    |
| **Real-Time**   | Cron Jobs       |
| **Payment**     | Stripe API                     |
| **RBAC**        | Permissions & Role Middleware  |

---

## 📡 Notable API Endpoints (from `api.php`)

| Endpoint                            | Description                                 |
|-------------------------------------|---------------------------------------------|
| `GET /send_comm_email`             | Trigger scheduled communication emails      |
| `GET /send_comm_sms`               | Trigger scheduled communication SMS         |
| `GET /fleet_list_without_auth`     | Public fleet listings                       |
| `GET /check_doc_expiry`            | Check and notify about expiring documents   |
| `POST /admin/add_fare`             | Add a fare structure (admin permission)     |
| `POST /admin/destinations`         | Add a new travel destination                |
| `DELETE /admin/destination_delete/{id}` | Delete a destination                   |
| `GET /admin/approved_driver`       | Retrieve approved driver list               |
| `GET /admin/get_website_branding`  | Fetch frontend branding settings            |

> Note: More endpoints are available in the full codebase, especially under modules like `DriverController`, `BookingController`, `StripePaymentController`, and more.

---

## 🚀 Setup Instructions

### Requirements
- PHP >= 8.0
- Composer
- MySQL
- Laravel CLI


### Installation Steps

```bash
# Clone the project
git clone https://github.com/yourusername/fast-travel-solutions.git
cd fast-travel-solutions

# Install backend dependencies
composer install

php artisan key:generate

php artisan migrate --seed

# Serve the application
php artisan serve
```

---

## 🧪 Future Enhancements

- 📱 Mobile app integration for customers and drivers
- 🌍 Multilingual support
- 📍 Live GPS tracking for vehicles
- 📊 Admin dashboard with real-time statistics
- 🔒 Two-factor authentication for critical actions


---

## 📄 License

Licensed under the [MIT License](LICENSE).

---

> 🚐 **Fast Travel Solutions** – Travel Smarter, Book Faster, Manage Better.
