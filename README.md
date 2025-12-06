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
| Cronjob (50+ likes → email admin) | ✅ |
| Database Schema (Migrations) | ✅ |
| Swagger Documentation | ✅ |
| 100 Dummy Data Seeder | ✅ |

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

# Setup database
touch database/database.sqlite
php artisan migrate
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

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/auth/register` | Register new user |
| POST | `/api/v1/auth/login` | Login user |
| POST | `/api/v1/auth/logout` | Logout user |
| GET | `/api/v1/people` | List recommended people |
| GET | `/api/v1/people/liked` | Get liked people |
| GET | `/api/v1/people/disliked` | Get disliked people |
| GET | `/api/v1/people/matches` | Get matches |
| POST | `/api/v1/swipe/like` | Like a person |
| POST | `/api/v1/swipe/dislike` | Dislike a person |
| GET | `/api/v1/swipe/stats` | Get statistics |

## 👤 Demo Accounts

| Email | Password |
|-------|----------|
| john@example.com | password123 |
| jane@example.com | password123 |
| admin@example.com | admin123 |

## 🗄️ Database Schema

```
┌─────────────────────┐          ┌─────────────────────┐
│       users         │          │       swipes        │
├─────────────────────┤          ├─────────────────────┤
│ id (PK)             │          │ id (PK)             │
│ name                │──────────│ swiper_id (FK)      │
│ email               │          │ swiped_id (FK)──────│
│ password            │          │ type (like/dislike) │
│ age                 │          │ created_at          │
│ pictures (JSON)     │          │ updated_at          │
│ location            │          └─────────────────────┘
│ bio                 │                    │
│ latitude            │                    │
│ longitude           │          ┌─────────────────────┐
│ created_at          │          │       people        │
│ updated_at          │          ├─────────────────────┤
└─────────────────────┘          │ id (PK)             │
                                 │ name                │
                                 │ age                 │
                                 │ pictures (JSON)     │
                                 │ location            │
                                 │ bio                 │
                                 │ latitude            │
                                 │ longitude           │
                                 │ created_at          │
                                 │ updated_at          │
                                 └─────────────────────┘
```

## ⏰ Cronjob

Check for popular users (50+ likes) and send email notification:

```bash
# Manual run
php artisan users:check-popular

# Add to crontab for hourly check
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## 📝 License

MIT License

---

Made with ❤️ for Tinder Clone Test Case
