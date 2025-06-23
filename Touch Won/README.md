# 🎰 Touchwon - Mobile Sweepstakes and Player Reward Platform

**Touchwon** is a Laravel-based web application designed to manage a mobile sweepstakes and gaming platform. This system allows vendors to add and manage players, allocate credits, run promotional sweepstakes, and track all player activities securely. The platform emphasizes both **profitability for vendors** and **trust for players**.



<img src="https://github.com/ahmad6532/My_Projects/blob/main/Touch%20Won/public/SS.png" />

---

## 🎯 Purpose

The main goal of Touchwon is to provide vendors with:

-   A **lucrative slot-based sweepstakes system**
-   A **trusted environment** for players
-   A streamlined dashboard to **manage credits, players, transactions, and gameplay**

---

## 💼 Key Features

### 🔐 Authentication

-   Player/vendor sign-up and sign-in
-   Password reset and recovery
-   Token expiration handling

### 🎮 Player Management

-   Add/edit player accounts
-   Bulk player credit allocation (`bulk_credits.blade`)
-   View and manage all player data (`players.blade`)

### 💰 Credit System

-   Add player credits manually (`add_player_credits.blade`)
-   Fill player account balances (`fillamount.blade`)
-   Redeem points and manage balances (`redeempoints.blade`)

### 🧾 Transactions and History

-   View complete transaction history (`transactions.blade`)
-   Monitor shift sessions and credit movement (`shifts.blade`)

### 📧 Notifications and Support

-   Email templates for password reset (`forgetpassword_email.blade`)
-   Expired token handling (`token_expire.blade`)
-   Support/help pages in English and Spanish

### 📜 Legal and Onboarding

-   Terms and conditions (`terms_&_conditions.blade`)
-   Welcome and sign-up pages (`welcome.blade`, `signUp.blade`)
-   Multilingual support (`help_en.blade`, `help_es.blade`, `espanol.blade`)

---

## 🧩 Project Structure Overview

```
resources/views/
├── auth/                         # Authentication views
├── layouts/                      # App layout templates
├── pages/                        # Core user-facing pages
│   ├── add_player.blade
│   ├── transactions.blade
│   ├── home.blade
│   └── signIn.blade
├── vendor/                       # Vendor-specific components
│   ├── bulk_credits.blade
│   ├── players.blade
│   ├── editplayer.blade
│   └── shifts.blade
├── paypal/                       # Integration (future/payments)
├── Other Views
│   ├── help_en.blade
│   ├── redeempoints.blade
│   ├── rhb.blade
│   ├── fillamount.blade
│   ├── welcome.blade
│   └── password_success.blade
```

---

## ⚙️ Technologies Used

-   **Backend**: Laravel (PHP Framework)
-   **Frontend**: Blade Templating Engine
-   **Database**: MySQL
-   **Authentication**: Laravel Auth
-   **Payment Integration**: PayPal (integration prepared)
-   **Localization**: Multilingual support (English, Spanish)
-   **Email Services**: Password recovery, notifications

---

## 🔧 Setup Instructions

### Requirements

-   PHP >= 8.0
-   Composer
-   MySQL
-   Laravel CLI

### Installation Steps

```bash
git clone https://github.com/ahmad6532/My_Projects.git
cd touchwon

composer install

php artisan key:generate


php artisan migrate --seed
php artisan serve
```

---

## 🚀 Usage Flow

1. Vendor logs in to manage players and credits
2. Adds or edits player accounts
3. Allocates credits via single or bulk upload
4. Players participate in sweepstake/slot games
5. Transactions, shifts, and credit logs are tracked
6. Players may redeem rewards or bonuses
7. Admins monitor activities and ensure security

---

## 💡 Future Enhancements

-   🎨 Frontend improvement with Vue/React
-   💳 Full PayPal and Stripe integration
-   📊 Real-time analytics for credit usage and revenue
-   📱 Mobile version or PWA for Android/iOS
-   🛡️ Role-based access control for multi-tiered admin

---

## 📜 License

This project is licensed under the **MIT License**.

---

> 🎰 **Touchwon** – Build Trust. Reward Loyalty. Grow Revenue.
