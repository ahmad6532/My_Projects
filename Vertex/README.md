# 👨‍💼 Vertex – Human Resource Management System

**Vertex** is an enterprise-grade Human Resource Management System built using **Laravel (REST APIs)** and **React (frontend)**, also integrated for use with **Flutter apps** and **tablet interfaces**. It offers a complete digital solution for managing human resources and office operations including **employee records**, **asset management**, **biometric attendance**, **geo-location mobile attendance**, **payroll**, **leave requests**, **holidays**, **notifications**, **multi-company support**, and **advanced reporting**.

Designed for versatility and scalability, HRMS empowers organizations to efficiently manage their entire workforce and infrastructure across **multiple branches and companies**.

---

## 🎯 Purpose

To provide a unified, API-driven HRMS platform that:
- Centralizes HR operations and asset tracking
- Enables real-time and geo-fenced attendance
- Supports multi-branch, multi-HR roles for large companies
- Streamlines payroll, onboarding, and offboarding
- Offers secure multi-platform access (web, mobile, tablet)
- Supports theming, communication configs, and data exports

---

## 🌟 Core Features

### 👥 Employee Management
- Add, update, terminate employees
- Role, department, and document assignment
- Onboarding and offboarding workflows

### 🛠️ Asset Management
- Track all office assets (laptops, desks, equipment)
- Assign assets to employees
- Log asset transfers, damage, returns
- Export asset inventory reports

### 🕒 Attendance Management
- **ZKTeco Biometric Integration**
- **Mobile/Tablet Attendance via Geo-location**
  - Attendance allowed only within a range set by admin
  - Auto-rejection outside set radius
- Sync with Oracle for enterprise DBs
- Graphical attendance insights and logs

### 💼 Payroll Management
- Monthly payroll calculation
- Tax/deduction management
- Salary slips and payment records

### 🏖️ Leave & Holiday Management
- Leave requests and automated workflows
- Holiday management by location
- Custom leave types and balances

### 🧳 Onboarding & Offboarding
- HR-controlled entry/exit processes
- Exit documentation and handovers
- Device, asset, and record clearance

### 🛡️ Multi-Company & Multi-Branch HR Control
- Multiple companies under one system
- HRs assigned by branch/role
- Controlled access to only relevant employee data
- Organization-wide or localized reporting

### 📈 Reporting & Analytics
- Downloadable PDF and CSV reports
- Branch-wise HR statistics
- Real-time interactive graphs

### 🔁 Cron Jobs
- Attendance sync
- Automated daily backups
- Notification triggers

### 💌 SMTP Configuration
- Admin control for outgoing email servers
- SMTP testing utility

### 🎨 Theme Settings
- Brand customization
- Dark/light mode toggle
- System-wide or branch-specific themes

### 📡 ZKTeco Device Management
- Add/manage biometric devices
- Sync employee data
- User count and connectivity status
- Oracle integration support

---

## 🧩 Technologies Used

| Layer           | Technology                        |
|----------------|------------------------------------|
| **Backend**     | Laravel 9.x (API)                 |
| **Frontend**    | React JS                          |
| **Mobile**      | Flutter (API consumer)            |
| **Tablet**      | Custom UI integrated via API      |
| **Database**    | MySQL                    |
| **Authentication** | Laravel Sanctum               |
| **Biometric**   | ZKTeco SDK                        |
| **Geo-Attendance** | GPS & Radius-based Check-in    |
| **Reports**     | PDF, CSV Export                   |
| **Email**       | SMTP Integration                  |
| **Theming**     | Admin theme customization         |

---

## 📡 Sample API Endpoints

| Endpoint                          | Description                                      |
|----------------------------------|--------------------------------------------------|
| `/zkt`                           | Pull data from ZKTeco device                     |
| `/exportDataToOracle`            | Export attendance to Oracle                      |
| `/create-all-user-on-device`     | Sync all users to biometric devices              |
| `/countDeviceUser`               | Count biometric device users                     |
| `/admin/employee`                | Manage employee records                          |
| `/admin/payroll`                 | Manage payroll entries and taxes                 |
| `/admin/attendance`              | Retrieve attendance stats                        |
| `/admin/assets`                  | Manage and assign office assets                  |
| `/admin/leave`                   | Submit/approve leave requests                    |
| `/admin/holidays`                | Manage company holidays                          |
| `/admin/theme-settings`          | Adjust theme and branding                        |
| `/admin/smtp-settings`           | Set email server parameters                      |
| `/admin/reports`                 | Export data to PDF/CSV                           |
| `/admin/geo-attendance`          | Record location-based attendance (mobile/tablet) |

---

## 🚀 Setup Instructions

### Requirements

- PHP >= 8.1
- Composer
- Node.js + npm
- MySQL
- Laravel CLI
- React
- Flutter (optional, API-based)

### Backend Setup (Laravel)

```bash
git clone https://github.com/yourusername/hrms-backend.git
cd hrms-backend

composer install

php artisan key:generate

php artisan migrate --seed
php artisan serve
```


## 📱 Multi-Platform Support

- 🖥️ Admin Panel – React (Web)
- 📱 Employee App – Flutter (Mobile)
- 📟 Tablet Interface – API-driven for Kiosks or HR stations

All platforms utilize the Laravel API backend for secure and synchronized data flow.

---

## 📦 Future Enhancements

- 🌐 Multi-language & RTL support
- 🧠 AI-powered HR analytics
- 🔒 Biometric for mobile check-ins
- 📊 Department-wise dashboards
- 📍 Live tracking during shifts

---


## 📜 License

This project is licensed under the MIT License.

---

> 🏢 **HRMS** – Smart HR and Asset Management. Real-Time. Reliable. Ready.
