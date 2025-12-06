# 🔥 Tinder Clone - Full Stack Application

Complete Tinder Clone application with **Backend (Laravel PHP)** and **Frontend (React Native)**.

## 📦 Project Structure
```
tinder-clone/
├── tinder-backend/       # Laravel PHP Backend API
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── routes/
│   └── README.md
│
└── tinder-frontend/      # React Native Frontend
    ├── GUIDE.md          # Step-by-step building guide
    ├── TinderApp.jsx     # Complete React component
    └── README.md
```

## ✅ Features Implemented

### Backend (Laravel PHP)
| Feature | Status |
|---------|--------|
| User Authentication (Register/Login/Logout) | ✅ |
| List recommended people (with pagination) | ✅ |
| Like person API | ✅ |
| Dislike person API | ✅ |
| Liked people list | ✅ |
| Disliked people list | ✅ |
| Matches list | ✅ |
| Swipe statistics | ✅ |
| Cronjob per minute (50+ likes → email admin) | ✅ |
| Spatie Roles & Permissions (User/Admin) | ✅ |
| Admin Dashboard (Web + API) | ✅ |
| Mailtrap Email Integration | ✅ |
| Database Schema (Migrations) | ✅ |
| Swagger/OpenAPI Documentation | ✅ |
| Dummy Data Seeder (100+ users) | ✅ |

### Frontend (React Native)
| Feature | Status |
|---------|--------|
| Splash Screen | ✅ |
| Login Screen | ✅ |
| Register Screen | ✅ |
| Swipeable Cards (like Tinder) | ✅ |
| Like (swipe right) | ✅ |
| Nope (swipe left) | ✅ |
| Multiple photos navigation | ✅ |
| Action buttons (Nope, Super Like, Like) | ✅ |
| Activity Screen (Matches/Likes/Nopes) | ✅ |
| Profile Screen with Stats | ✅ |
| Match Modal Animation | ✅ |
| Atomic Design Architecture | ✅ |
| React Query Setup | ✅ |
| Recoil State Management | ✅ |

## 🚀 Quick Start

### Backend Setup
```bash
cd tinder-backend

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure Mailtrap in .env
# MAIL_HOST=sandbox.smtp.mailtrap.io
# MAIL_PORT=2525
# MAIL_USERNAME=your_username
# MAIL_PASSWORD=your_password

# Setup database
touch database/database.sqlite
php artisan migrate

# Seed data (roles, permissions, users, popular users)
php artisan db:seed

# Start server
php artisan serve
# API available at http://localhost:8000
```

### Frontend Setup
```bash
# Create new Expo project
npx create-expo-app tinder-app --template blank-typescript
cd tinder-app

# Install dependencies
npm install @tanstack/react-query recoil
npm install @react-navigation/native @react-navigation/stack
npm install react-native-screens react-native-safe-area-context
npm install react-native-gesture-handler react-native-reanimated
npm install expo-linear-gradient @expo/vector-icons

# Follow GUIDE.md for component implementation

# Start app
npx expo start
```

## 📡 API Endpoints

### Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/auth/register` | Register new user |
| POST | `/api/v1/auth/login` | Login user |
| POST | `/api/v1/auth/logout` | Logout user |
| GET | `/api/v1/auth/me` | Get current user |

### People
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/people` | List recommended people (paginated) |
| GET | `/api/v1/people/liked` | Get liked people |
| GET | `/api/v1/people/disliked` | Get disliked people |
| GET | `/api/v1/people/matches` | Get matches |

### Swipe
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/swipe/like` | Like a person |
| POST | `/api/v1/swipe/dislike` | Dislike a person |
| GET | `/api/v1/swipe/stats` | Get swipe statistics |

### Admin (Requires Admin Role)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/admin/dashboard` | Dashboard statistics |
| GET | `/api/v1/admin/users` | List all users (paginated) |
| GET | `/api/v1/admin/users/{id}` | User detail |
| PUT | `/api/v1/admin/users/{id}/role` | Update user role |
| PUT | `/api/v1/admin/users/{id}/block` | Block/unblock user |
| GET | `/api/v1/admin/popular-users` | Users with 50+ likes |
| GET | `/api/v1/admin/email-logs` | Email notification logs |
| POST | `/api/v1/admin/send-popular-notification` | Trigger notification manually |

## 🔐 Demo Accounts

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | admin123 | Admin |
| john@example.com | password123 | User |
| jane@example.com | password123 | User |
| sarah.popular@example.com | password123 | User (60 likes) |
| mike.famous@example.com | password123 | User (55 likes) |

## 🌐 Web Pages

| URL | Description |
|-----|-------------|
| http://localhost:8000 | Welcome page |
| http://localhost:8000/admin | Admin Dashboard |
| http://localhost:8000/admin/login | Admin Login |
| http://localhost:8000/api/documentation | Swagger API Docs |

## 🗄️ Database Schema
```
┌─────────────────────────┐          ┌─────────────────────────┐
│         users           │          │         swipes          │
├─────────────────────────┤          ├─────────────────────────┤
│ id (PK)                 │          │ id (PK)                 │
│ name                    │◄─────────│ swiper_id (FK)          │
│ email                   │          │ swiped_id (FK)──────────┤
│ password                │          │ type (like/dislike)     │
│ age                     │          │ created_at              │
│ pictures (JSON)         │          │ updated_at              │
│ location                │          └─────────────────────────┘
│ bio                     │
│ latitude                │          ┌─────────────────────────┐
│ longitude               │          │   popular_notifications │
│ is_blocked              │          ├─────────────────────────┤
│ created_at              │◄─────────│ id (PK)                 │
│ updated_at              │          │ user_id (FK)            │
└─────────────────────────┘          │ likes_count             │
                                     │ admin_email             │
┌─────────────────────────┐          │ notified_at             │
│     roles (Spatie)      │          │ created_at              │
├─────────────────────────┤          │ updated_at              │
│ id (PK)                 │          └─────────────────────────┘
│ name (admin/user)       │
│ guard_name              │
└─────────────────────────┘
```

## ⏰ Cronjob (Every Minute)

Check for popular users (50+ likes) and send email notification to admin:
```bash
# Manual run
php artisan users:check-popular

# With custom threshold
php artisan users:check-popular --min-likes=30

# Scheduler runs every minute automatically
# Add to crontab:
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1

# For development, use:
php artisan schedule:work
```

## 📧 Email Configuration (Mailtrap)

1. Register at https://mailtrap.io (free)
2. Get SMTP credentials from your inbox
3. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tinderclone.com"
MAIL_FROM_NAME="Tinder Clone"

ADMIN_EMAIL=admin@example.com
```

## 🛠️ Tech Stack

### Backend
- PHP 8.x
- Laravel 10.x
- Laravel Sanctum (API Authentication)
- Spatie Permission (Roles & Permissions)
- L5-Swagger (API Documentation)
- Mailtrap (Email Testing)
- SQLite / MySQL

### Frontend
- React Native
- Expo
- React Query
- Recoil
- React Navigation

## 📋 Available Seeders
```bash
# Run all seeders
php artisan db:seed

# Run specific seeders
php artisan db:seed --class=RolePermissionSeeder  # Create roles & admin
php artisan db:seed --class=PopularUsersSeeder    # Create popular users with 50+ likes
php artisan db:seed --class=UserSeeder            # Create dummy users
```

## 🧪 Testing API

### Using cURL
```bash
# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"admin123"}'

# Access admin dashboard (replace TOKEN)
curl -X GET http://localhost:8000/api/v1/admin/dashboard \
  -H "Authorization: Bearer TOKEN"

# Get popular users
curl -X GET http://localhost:8000/api/v1/admin/popular-users \
  -H "Authorization: Bearer TOKEN"
```

### Using Swagger
Visit http://localhost:8000/api/documentation

## 📝 License

MIT License

---

Made with ❤️ for Tinder Clone Test Case
