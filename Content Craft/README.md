<h1 align="center">✨ Content Craft</h1>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-F72C1F?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/PHP-8.1-8892BF?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/Stripe-Integrated-635BFF?style=for-the-badge&logo=stripe&logoColor=white" />
  <img src="https://img.shields.io/badge/AdminLTE-Dashboard-3C8DBC?style=for-the-badge" />
    <img src="https://img.shields.io/badge/Role_&_Permission-Spatie-FF69B4?style=for-the-badge" />

</p>

<p align="center">
   <strong>This is my practice project. A modular Laravel SaaS application with role-based dashboards and Subscription Plan. User can post his/her articles by subscribe a plan. There is the limit of articles to post, then need to upgrad plan.</strong>
</p>

<hr>

## 🌟 Project Highlights

-   🔐 Multi-role authentication system (Admin, Manager, User)
-   💸 Stripe-powered article monetization
-   📲 Firebase Authentication
-   📊 Smart dashboards for insights and analytics
-   🎨 Clean UI using AdminLTE and Bootstrap 5

---

## 📖 About the Project

This Laravel project demonstrates a complete real-world system that includes:

-   Advanced role handling with [Spatie Laravel Permission](https://github.com/spatie/laravel-permission)
-   Modular dashboards for each role with specific capabilities
-   Free and paid content creation system using Stripe
-   Notification-based engagement and user interaction

---

## 🧩 Features Breakdown

### 👤 Roles

-   **Admin**: Manages managers
-   **Manager**: Manages users, sees revenue
-   **User**: Publishes and interacts with articles

### 📝 Articles & Plans

-   ✅ Free Plan: 5 articles
-   💳 Paid Plans:
    -   Basic: Rs. 50 / 5 articles
    -   Platinum: Rs. 70 / 10 articles
    -   Gold: Rs. 100 / 15 articles

### 📢 Notifications

-   User likes trigger Firebase push and email notifications

### 📈 Dashboards

-   Admin: Manager stats, income chart
-   Manager: User stats, monthly sales chart
-   User: Articles, plan purchases, and likes

---

## 🛠 Technology Stack

| Tech              | Purpose                  |
| ----------------- | ------------------------ |
| 🧱 Laravel 10.10  | PHP Framework            |
| 🐘 PHP 8.1        | Language                 |
| 📦 Composer       | Dependency Manager       |
| 🧩 Spatie         | Role/Permission Handling |
| 🔐 Fortify        | Authentication           |
| 💳 Stripe PHP SDK | Payments                 |
| 🖥 AdminLTE        | Dashboard Theme          |
| 🗃 Sanctum         | API Authentication       |
| 🎨 Bootstrap 5    | UI Styling               |
| 🔥 Firebase       | Authentication           |
| 🗃 MySQL           | Database                 |

---

## 🔌 RESTful API Endpoints

| Method   | Endpoint                           | Description                |
| -------- | ---------------------------------- | -------------------------- |
| `POST`   | `/api/signin`                      | Login user                 |
| `POST`   | `/api/signup`                      | Register user              |
| `POST`   | `/api/articles`                    | Create article             |
| `GET`    | `/api/articles`                    | List own articles          |
| `GET`    | `/api/articles/{id}/edit`          | Get article for edit       |
| `PUT`    | `/api/articles/{id}`               | Update article             |
| `DELETE` | `/api/articles/{id}`               | Delete article             |
| `GET`    | `/api/allUsersArticles`            | Browse all users' articles |
| `GET`    | `/api/usersArticle?articleId={id}` | Get specific article       |
| `GET`    | `/api/allPlans`                    | View plans                 |
| `POST`   | `/api/purchase`                    | Purchase plan              |

---

## ⚙️ Installation Guide

```bash
git clone https://github.com/ahmad6532/My_Projects/tree/main/Content%20Craft

cd your-repo-name

composer install

php artisan key:generate

php artisan migrate --seed

php artisan serve
```

---

## 🎓 Learning Objectives

| ✅ Skill                   |
| -------------------------- |
| Repository Pattern         |
| Eloquent ORM               |
| Middleware Usage           |
| API Authentication         |
| Role-Based Routing         |
| Stripe Payment Integration |
| Firebase Authentication    |

---

## 📄 License

This project is intended for learning, demo, and training purposes.
