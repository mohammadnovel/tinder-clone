# 🔥 Tinder Clone - Full Stack Application

Complete Tinder Clone application with **Backend (Laravel PHP)** and **Frontend (React Native)**.

![Tinder Clone](https://img.shields.io/badge/Laravel-10.x-red) ![React Native](https://img.shields.io/badge/React%20Native-Expo-blue) ![License](https://img.shields.io/badge/License-MIT-green)

---
## 🎬 Demo Preview

https://github.com/user-attachments/assets/preview/preview-record.mov

<video src="preview/preview-record.mov" width="100%" controls autoplay loop>
  Your browser does not support the video tag.
</video>

> 📱 Video demo menampilkan fitur swipe, like, match, dan admin dashboard

## 📦 Project Structure
```
tinder-clone/
├── tinder-backend/           # Laravel PHP Backend API
│   ├── app/
│   │   ├── Console/Commands/ # Artisan commands (Cronjob)
│   │   ├── Http/Controllers/ # API & Admin Controllers
│   │   ├── Mail/             # Email templates
│   │   └── Models/           # Eloquent models
│   ├── config/
│   ├── database/
│   │   ├── migrations/       # Database schema
│   │   └── seeders/          # Dummy data
│   ├── routes/
│   │   ├── api.php           # API routes
│   │   └── web.php           # Web routes (Admin)
│   └── resources/views/      # Blade templates
│
├── tinder-frontend/          # React Native Frontend
│   ├── GUIDE.md              # Step-by-step building guide
│   ├── TinderApp.jsx         # Complete React component
│   └── README.md
│
└── README.md                 # This file
```

---

## ✅ Features Implemented

### Backend (Laravel PHP)
| Feature | Status |
|---------|--------|
| User Authentication (Register/Login/Logout) | ✅ |
| List recommended people (with pagination) | ✅ |
| Like person API | ✅ |
| Dislike person API | ✅ |
| Liked people list (API only) | ✅ |
| Disliked people list | ✅ |
| Matches list | ✅ |
| Swipe statistics | ✅ |
| **Cronjob per minute** (50+ likes → email admin) | ✅ |
| **Spatie Roles & Permissions** (User/Admin) | ✅ |
| **Admin Dashboard** (Web + API) | ✅ |
| **Mailtrap Email Integration** | ✅ |
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

---

## 🚀 Quick Start

### Prerequisites

Pastikan sudah terinstall:
- **PHP** >= 8.1
- **Composer** >= 2.0
- **Node.js** >= 18.0
- **npm** atau **yarn**
- **Expo CLI** (untuk frontend)

---

## 📱 Backend Setup (Step-by-Step)

### Step 1: Clone Repository
```bash
git clone https://github.com/mohammadnovel/tinder-clone.git
cd tinder-clone/tinder-backend
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Setup Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Edit file `.env`:
```env
# Untuk SQLite (simple, recommended untuk development)
DB_CONNECTION=sqlite

# Untuk MySQL (production)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=tinder_clone
# DB_USERNAME=root
# DB_PASSWORD=your_password
```

Jika menggunakan SQLite:
```bash
touch database/database.sqlite
```

### Step 5: Configure Mailtrap (Email)

1. Daftar gratis di https://mailtrap.io
2. Buat inbox baru
3. Copy SMTP credentials
4. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tinderclone.com"
MAIL_FROM_NAME="Tinder Clone"

ADMIN_EMAIL=admin@example.com
```

### Step 6: Run Migrations
```bash
php artisan migrate
```

### Step 7: Seed Database
```bash
# Seed semua data (roles, users, popular users)
php artisan db:seed

# Atau seed satu per satu:
php artisan db:seed --class=RolePermissionSeeder   # Roles & Admin
php artisan db:seed --class=UserSeeder             # Dummy users
php artisan db:seed --class=PopularUsersSeeder     # Popular users (50+ likes)
```

### Step 8: Generate Swagger Documentation
```bash
php artisan l5-swagger:generate
```

### Step 9: Start Server
```bash
php artisan serve
```

### Step 10: Verify Installation

Buka browser dan test:

| URL | Description |
|-----|-------------|
| http://localhost:8000 | Welcome Page |
| http://localhost:8000/admin/login | Admin Login |
| http://localhost:8000/api/documentation | Swagger API Docs |

Login Admin:
- **Email:** admin@example.com
- **Password:** admin123

### Step 11: Test Cronjob (Optional)
```bash
# Manual test
php artisan users:check-popular

# Run scheduler (untuk development)
php artisan schedule:work
```

---

## 📱 Frontend Setup (Step-by-Step)

### Step 1: Navigate to Frontend Directory
```bash
cd tinder-frontend
```

### Step 2: Create Expo Project (Jika belum ada)
```bash
npx create-expo-app TinderApp --template blank-typescript
cd TinderApp
```

### Step 3: Install Dependencies
```bash
# Core dependencies
npm install @tanstack/react-query recoil axios

# Navigation
npm install @react-navigation/native @react-navigation/stack @react-navigation/bottom-tabs

# React Native essentials
npm install react-native-screens react-native-safe-area-context
npm install react-native-gesture-handler react-native-reanimated

# UI components
npm install expo-linear-gradient @expo/vector-icons
npm install react-native-deck-swiper
```

### Step 4: Configure API URL

Buat file `src/config/api.ts`:
```typescript
// Untuk development (localhost)
// Gunakan IP komputer jika test di device fisik
export const API_URL = 'http://localhost:8000/api/v1';

// Untuk device fisik, gunakan IP komputer:
// export const API_URL = 'http://192.168.1.100:8000/api/v1';
```

### Step 5: Setup React Query

Buat file `src/providers/QueryProvider.tsx`:
```typescript
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

const queryClient = new QueryClient();

export const QueryProvider = ({ children }) => (
  <QueryClientProvider client={queryClient}>
    {children}
  </QueryClientProvider>
);
```

### Step 6: Setup Recoil

Update `App.tsx`:
```typescript
import { RecoilRoot } from 'recoil';
import { QueryProvider } from './src/providers/QueryProvider';

export default function App() {
  return (
    <RecoilRoot>
      <QueryProvider>
        {/* Your app components */}
      </QueryProvider>
    </RecoilRoot>
  );
}
```

### Step 7: Follow GUIDE.md

Ikuti panduan lengkap di `tinder-frontend/GUIDE.md` untuk implementasi komponen:
- Screens (Login, Register, Home, Profile, Activity)
- Components (SwipeCard, MatchModal, etc.)
- Hooks (useAuth, useSwipe, etc.)
- State management

### Step 8: Start Expo
```bash
npx expo start
```

### Step 9: Run on Device/Emulator

- **iOS Simulator:** Press `i`
- **Android Emulator:** Press `a`
- **Physical Device:** Scan QR code with Expo Go app

---

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
| GET | `/api/v1/admin/users` | List all users |
| GET | `/api/v1/admin/users/{id}` | User detail |
| PUT | `/api/v1/admin/users/{id}/role` | Update user role |
| PUT | `/api/v1/admin/users/{id}/block` | Block/unblock user |
| GET | `/api/v1/admin/popular-users` | Users with 50+ likes |
| GET | `/api/v1/admin/email-logs` | Email notification logs |
| POST | `/api/v1/admin/send-popular-notification` | Trigger notification |

---

## 🔐 Demo Accounts

| Email | Password | Role | Notes |
|-------|----------|------|-------|
| admin@example.com | admin123 | Admin | Full dashboard access |
| john@example.com | password123 | User | Regular user |
| jane@example.com | password123 | User | Regular user |
| sarah.popular@example.com | password123 | User | 60 likes (Popular!) |
| mike.famous@example.com | password123 | User | 55 likes (Popular!) |

---

## 🌐 Web Pages

| URL | Description |
|-----|-------------|
| http://localhost:8000 | Welcome page |
| http://localhost:8000/admin | Admin Dashboard |
| http://localhost:8000/admin/login | Admin Login |
| http://localhost:8000/admin/users | User Management |
| http://localhost:8000/admin/popular-users | Popular Users |
| http://localhost:8000/admin/email-logs | Email Logs |
| http://localhost:8000/api/documentation | Swagger API Docs |

---

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
│    roles (Spatie)       │          │ created_at              │
├─────────────────────────┤          │ updated_at              │
│ id                      │          └─────────────────────────┘
│ name (admin/user)       │
│ guard_name              │
└─────────────────────────┘
```

---

## ⏰ Cronjob Configuration

### How It Works
- Cronjob berjalan **setiap menit**
- Mengecek user dengan **50+ likes**
- Mengirim email notifikasi ke admin via **Mailtrap**
- Menyimpan log notifikasi ke database

### Manual Test
```bash
# Test dengan threshold default (50 likes)
php artisan users:check-popular

# Test dengan threshold custom
php artisan users:check-popular --min-likes=10
```

### Development Mode
```bash
# Jalankan scheduler secara lokal
php artisan schedule:work
```

### Production Setup

Add to crontab (`crontab -e`):
```bash
* * * * * cd /path-to-your-project/tinder-backend && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📧 Email Configuration

### Mailtrap Setup (Development)

1. Register at https://mailtrap.io (free)
2. Create new inbox
3. Get SMTP credentials
4. Update `.env`:
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

### Production (Gmail/SMTP)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
```

---

## 🧪 Testing API

### Using cURL
```bash
# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","age":25,"location":"Jakarta"}'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"admin123"}'

# Get People (replace TOKEN)
curl -X GET http://localhost:8000/api/v1/people \
  -H "Authorization: Bearer TOKEN"

# Like someone
curl -X POST http://localhost:8000/api/v1/swipe/like \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"person_id":5}'

# Admin Dashboard
curl -X GET http://localhost:8000/api/v1/admin/dashboard \
  -H "Authorization: Bearer TOKEN"
```

### Using Swagger

1. Open http://localhost:8000/api/documentation
2. Click "Authorize" button
3. Enter: `Bearer YOUR_TOKEN`
4. Test any endpoint

### Using Postman

Import collection or create requests manually with:
- Base URL: `http://localhost:8000/api/v1`
- Auth Header: `Authorization: Bearer {token}`

---

## 🛠️ Tech Stack

### Backend
| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.x | Server-side language |
| Laravel | 10.x | PHP Framework |
| Laravel Sanctum | - | API Authentication |
| Spatie Permission | 6.x | Roles & Permissions |
| L5-Swagger | - | API Documentation |
| Mailtrap | - | Email Testing |
| SQLite/MySQL | - | Database |

### Frontend
| Technology | Version | Purpose |
|------------|---------|---------|
| React Native | - | Mobile Framework |
| Expo | - | Development Platform |
| React Query | - | Server State Management |
| Recoil | - | Client State Management |
| React Navigation | - | Navigation |

---

## 📋 Available Commands

### Artisan Commands
```bash
# Database
php artisan migrate                    # Run migrations
php artisan migrate:fresh              # Fresh migration
php artisan db:seed                    # Seed all data

# Seeders
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=PopularUsersSeeder

# Cronjob
php artisan users:check-popular        # Check popular users
php artisan schedule:work              # Run scheduler locally

# Swagger
php artisan l5-swagger:generate        # Generate API docs

# Cache
php artisan config:clear               # Clear config cache
php artisan cache:clear                # Clear app cache
php artisan route:clear                # Clear route cache
```

### NPM Commands (Frontend)
```bash
npx expo start                         # Start Expo dev server
npx expo start --ios                   # Start iOS simulator
npx expo start --android               # Start Android emulator
npx expo build:android                 # Build Android APK
npx expo build:ios                     # Build iOS IPA
```

---

## 🐛 Troubleshooting

### Backend Issues

**Error: SQLSTATE[HY000] [14] unable to open database file**
```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
```

**Error: Class not found**
```bash
composer dump-autoload
php artisan config:clear
```

**Email not sending**
- Check `.env` Mailtrap credentials
- Run `php artisan config:clear`
- Check `storage/logs/laravel.log`

### Frontend Issues

**Metro bundler error**
```bash
npx expo start --clear
```

**Network request failed**
- Use computer IP instead of `localhost`
- Check if backend server is running
- Verify API_URL in config

---

## 📝 License

MIT License

---

## 👨‍💻 Author

**Mohammad Novel**

- GitHub: [@mohammadnovel](https://github.com/mohammadnovel)

---

Monvear