# 🏢 QI-Tech – Quality Improvement Technology Platform

**QI-Tech** (Quality Improvement Technology) is a scalable, Laravel-based platform designed to serve **multiple industries** by helping organizations create digital ecosystems for handling **public complaints**, **incident reports**, and **quality assurance cases**.

It allows head offices to manage multiple branches (locations), create teams, publish industry-specific forms, and track the full lifecycle of public-reported cases—from submission to resolution—through a centralized dashboard.

---

## 🎯 Project Purpose

To provide a flexible, form-based quality improvement and incident reporting solution for:
- Healthcare
- Manufacturing
- Education
- Logistics
- Any industry requiring distributed quality control or compliance management

---

## 🌟 Core Features

### 🏬 Multi-Level Organization
- Create **Head Office** accounts for companies
- Add and manage multiple **branches/locations**
- Assign users to Head Office or branch roles

### 📄 Public Form Management
- Build **bespoke incident reporting forms**
- Assign forms to specific branches or categories
- Forms are **publicly accessible** (no login required)
- Form submissions generate **cases**

### 📂 Case Lifecycle
- Form submission creates a **case**
- **Automatic Case Allocation** based on branch or form type
- Head Office or branch can **manually reassign** cases
- Case status: `New → In Review → Assigned → Resolved`

### 🧑‍💼 Role-Based Access & Dashboards
- **Dedicated Dashboard for Each User**
  - View personal assignments, stats, alerts
  - Branch-specific insights for local users
  - Organization-wide overviews for HQ teams

### 🎨 Theme Settings
- Customizable themes per organization or user
- Dark/light mode support
- Logo, brand color, and header settings

### 👩‍💼 Role-Based Case Access
- Head Office users view all branch activity
- Branch users handle only local case data
- Super Admin panel for system-wide control

### 📢 Alerts & Notifications
- National alerts
- Patient safety alerts
- Email and OTP-based verification

---

## ⚙️ Technologies Used

| Layer         | Stack                        |
|---------------|------------------------------|
| **Backend**   | Laravel                      |
| **Frontend**  | Blade Templates/ Livewire    |
| **Database**  | MySQL               |
| **Auth**      | Laravel Auth + OTP           |
| **API Access**| REST-style endpoints         |
| **Reports**   | Case and Form Submissions    |
| **Theming**   | Dynamic theme engine         |

---

## 📡 Key Controllers & Routes

| Controller                          | Role                                                |
|-------------------------------------|-----------------------------------------------------|
| `HeadOfficesController`            | Manage Head Office data                             |
| `LocationsController`              | Create and update branches                          |
| `BeSpokeFormsController`           | Design and manage public incident forms             |
| `CaseManagerController`            | Track and assign cases                              |
| `HeadOfficeUsersController`        | Assign users to roles and regions                   |
| `NearMissManagerController`        | Incident-specific case tracking                     |
| `GdprController`                   | Handle GDPR compliance                              |
| `OtpController`                    | OTP login support                                   |
| `ThemeSettingsController`          | Manage UI themes and appearance                     |

---

## 🌍 Use Case Flow

1. **Admin or Head Office** registers and sets up their company and branches.
2. **Create public-facing forms** specific to organization needs.
3. **Public users submit complaints or incidents** through these forms.
4. **Cases are automatically generated and allocated** based on predefined rules.
5. **Branch users and assigned staff manage and resolve cases**.
6. **Admins customize appearance** and monitor insights via dashboards.

---

## 🚀 Setup Instructions

### Requirements
- PHP >= 8.0
- Composer
- Laravel CLI
- Livewire
- MySQL

### Installation

```bash
git clone https://github.com/ahmad6532/My_Projects/tree/main/QI%20Tech
cd qitech

composer install

php artisan key:generate

php artisan migrate --seed

php artisan serve
```

---

## 🔒 Security and Compliance

- OTP-based authentication
- Public access forms with submission tracking
- GDPR-compliant data handling
- Role-based access (Head Office, Branch, Admin)

---

## 🧪 Future Enhancements

- Drag-and-drop form builder
- Mobile responsive UI
- Case prioritization engine
- Multi-language support
- Analytics dashboard (charts, KPIs)

---


## 📜 License

This project is licensed under the MIT License.

---

> 🧠 **QI-Tech** – Elevate Quality. Resolve Faster. Empower Teams.

