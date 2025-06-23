<h1 align="center">🏨 Hostel Management System (PHP)</h1>

<p align="center">
  <img src="https://img.shields.io/badge/Language-PHP-blue?style=for-the-badge&logo=php" />
  <img src="https://img.shields.io/badge/System-Hostel%20Mgmt-green?style=for-the-badge" />
  <img src="https://img.shields.io/badge/Version-1.0-yellow?style=for-the-badge" />
</p>

---

## 📌 Overview

This Hostel Management System is developed using **Core PHP** and provides backend functionality for managing student accommodations, room assignments, leave requests, and dashboard views for both administrators and students. It is suitable for use in university or institutional hostel setups.

<img src="https://github.com/ahmad6532/My_Projects/blob/main/Hostel%20Management/SS.png" />

---

## ✨ Key Features

- 👨‍🎓 Add and manage student records
- 🚪 Allocate and manage room details
- 🛏 View detailed student & room info
- 📋 Manage student leave applications
- 🔐 Login/logout functionality for secure access
- 📊 Separate dashboards for students and room management
- 🔎 Search functionality for students/rooms

---

## 🧩 Core PHP Files Description

| File Name              | Description |
|------------------------|-------------|
| `add_student_backend`       | Adds a new student and stores data in the database |
| `connection`                | Manages database connection setup |
| `dashboard_backrom`         | Room management dashboard backend |
| `dashboard_backstd`         | Student dashboard backend |
| `leave`                     | Handles student leave request submissions |
| `login_backend`             | Processes user login and validation |
| `logout`                    | Destroys session and logs out the user |
| `rooms_backend`             | Room creation and assignment logic |
| `search`                    | Search students/rooms based on input filters |
| `view_detail_backend`       | Shows individual student/room detail |
| `view_std_backend`          | Displays all student data with options to manage |

---

## 🛠 Technologies Used

- 💻 Core PHP
- 🗃 MySQL Database
- 🧱 HTML/CSS for interface
- 📄 PHP procedural structure

---

## 🚀 Setup Instructions

```bash
1. Clone or download this repository.
2. Create a MySQL database (e.g., hostel_db).
3. Import the provided SQL schema.
4. Configure database credentials in `connection.php`.
5. Run the project via localhost (e.g., XAMPP/WAMP).
```

---

## 📂 Folder Structure

```
└── PHP
    ├── add_student_backend.php
    ├── connection.php
    ├── dashboard_backrom.php
    ├── dashboard_backstd.php
    ├── leave.php
    ├── login_backend.php
    ├── logout.php
    ├── rooms_backend.php
    ├── search.php
    ├── view_detail_backend.php
    ├── view_std_backend.php
```

---

## 🔒 Security Notes

- Session-based login/logout management
- Simple input validation (consider adding stronger sanitization)
- Recommend using prepared statements (MySQLi or PDO)

---

## 📄 License

This project is for academic and educational use.
