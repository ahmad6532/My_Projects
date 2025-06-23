# 📊 Motul Analytics & Content Dashboard

The **Motul Dashboard** is a Laravel-powered web application built to track, analyze, and visualize customer and user engagement with Motul’s training content and technical documentation. Designed with real-time interactivity using **jQuery AJAX**, the platform provides in-depth insights into content performance, regional participation, and user behavior.

Motul, a global leader in automotive lubricants and technology, benefits from this system to improve the distribution and effectiveness of its digital training resources.

<img src="https://github.com/ahmad6532/My_Projects/blob/main/Motul/dashboard.png" />

---

<img src="https://github.com/ahmad6532/My_Projects/blob/main/Motul/table.png" />

---

## 🎯 Purpose

To empower Motul’s internal stakeholders with a dynamic dashboard for:

- Monitoring **user engagement** across regions and time
- Tracking **training effectiveness**
- Identifying **most used files, content pillars**, and **top users**
- Comparing usage trends across **custom date ranges**

---

## 🧩 Core Features

### 📊 Dashboard Overview

- Total Users (Growth percentage)
- Active Users by Time Spent
- Viewed Files (Growth comparison)

### 🌍 Geographic Analytics

- **Users by Region** – Tracks how many users accessed the app by country
- **Time Spent by Region** – Measures average engagement duration per region

### 🕓 Time-Based Comparison

- Selectable date ranges for both current and previous periods
- Compare usage metrics over time (e.g., July vs June)

### 👤 Top Visitors

- Displays top users by training activity
- Tracks repeat engagement (e.g., Ali Raza most active)

### 📄 Most Viewed & Bookmarked Files

- Lists most popular documents (PDFs, PNGs)
- Tracks growth or decline in views/bookmarks
- View/download option for CSV and PDF reports

### 📚 Pillar Engagement Tracking

- View which **pillars** (categories) and **sub-pillars** were most accessed
- Categorization by product type and family (e.g., MC CARE, CHAIN MAINTENANCE)

### 📦 Content Management

- Manage:
  - **Modules** (training materials)
  - **Pillars** (main topics)
  - **Pillar Families** (subcategories)
- CRUD operations for each entity (assumed)

---

## 🧠 Technologies Used

| Layer          | Stack                                   |
| -------------- | --------------------------------------- |
| **Backend**    | Laravel 9.x (PHP Framework)             |
| **Frontend**   | Blade Templates, HTML/CSS               |
| **Dynamic UI** | jQuery AJAX                             |
| **Charts**     | Chart.js or ApexCharts (for bar graphs) |
| **Reports**    | Export to CSV/PDF                       |
| **Database**   | MySQL                                   |
| **Auth**       | Laravel Auth System                     |

---

## 🧪 Functional Modules Summary

| Module                   | Purpose                                          |
| ------------------------ | ------------------------------------------------ |
| Dashboard                | High-level metrics and trends                    |
| Users by Region          | Visual breakdown of user activity by country     |
| Time Spent (User/Region) | Engagement heatmaps                              |
| Training Visitors        | Most active participants                         |
| Viewed Files             | File-level engagement analytics                  |
| Viewed Pillars           | Topic-level content tracking                     |
| Bookmarked Files         | Track frequently saved content                   |
| Content Management       | Admin panel to manage Modules, Pillars, Families |

---

## 🧭 Usage Flow

1. **Admin Login** using Laravel Auth
2. Navigate to dashboard
3. Select desired date ranges (with comparison option)
4. Analyze reports:
   - Total engagement
   - Regional activity
   - Top documents and pillars
5. View and export reports (CSV or PDF)
6. Use Content Management to upload and categorize new materials

---

## 🚀 Setup Instructions

### Requirements

- PHP >= 8.0
- Composer
- Laravel CLI
- MySQL

### Installation

---

## 📄 License

Licensed under the [MIT License](LICENSE).

---

> 🔧 **Motul Dashboard** – Empower Decisions with Data. Train Smarter. Analyze Better.
