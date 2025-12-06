# 🔥 Tinder Clone - Backend API (Laravel)

A complete REST API backend for Tinder Clone application built with Laravel.

## 📋 Features

- ✅ User Authentication (Register, Login, Logout)
- ✅ List recommended people (with pagination)
- ✅ Like person
- ✅ Dislike person
- ✅ Liked people list
- ✅ Disliked people list
- ✅ Matches list
- ✅ Swipe statistics
- ✅ Cronjob - Email notification when someone gets 50+ likes
- ✅ Swagger API Documentation
- ✅ 100 dummy profiles

## 🛠️ Requirements

- PHP >= 8.1
- Composer
- SQLite / MySQL / PostgreSQL

## 🚀 Installation

### Step 1: Install Dependencies

```bash
cd tinder-backend
composer install
```

### Step 2: Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Setup Database

```bash
# Create SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed with 100 dummy data
php artisan db:seed
```

### Step 4: Start Server

```bash
php artisan serve
```

API will be available at: `http://localhost:8000`

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
| GET | `/api/v1/people/{id}` | Get specific person |
| GET | `/api/v1/people/liked` | Get liked people list |
| GET | `/api/v1/people/disliked` | Get disliked people list |
| GET | `/api/v1/people/matches` | Get matches (mutual likes) |

### Swipes

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/swipe/like` | Like a person |
| POST | `/api/v1/swipe/dislike` | Dislike a person |
| GET | `/api/v1/swipe/stats` | Get swipe statistics |
| DELETE | `/api/v1/swipe/{id}` | Undo a swipe |

## 🔐 Authentication

All API endpoints (except login/register) require authentication using Bearer token.

### Get Token

```bash
# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "john@example.com", "password": "password123"}'
```

Response:
```json
{
  "success": true,
  "data": {
    "user": {...},
    "token": "your-api-token"
  }
}
```

### Use Token

```bash
curl http://localhost:8000/api/v1/people \
  -H "Authorization: Bearer your-api-token"
```

## 👤 Demo Accounts

| Email | Password |
|-------|----------|
| john@example.com | password123 |
| jane@example.com | password123 |
| admin@example.com | admin123 |

## ⏰ Cronjob Setup

The application includes a command to check for popular users (50+ likes) and send email notifications.

### Manual Run

```bash
php artisan users:check-popular
```

### Schedule (Add to crontab)

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

The command runs hourly by default.

## 🗄️ Database Schema

### Users Table
```
- id
- name
- email
- password
- age
- pictures (JSON)
- location
- bio
- latitude
- longitude
- created_at
- updated_at
```

### People Table
```
- id
- name
- age
- pictures (JSON)
- location
- bio
- latitude
- longitude
- created_at
- updated_at
```

### Swipes Table
```
- id
- swiper_id (FK -> users)
- swiped_id (FK -> people)
- type (enum: like, dislike)
- created_at
- updated_at
```

## 📖 API Documentation (Swagger)

After installing `l5-swagger` package, access documentation at:

```
http://localhost:8000/api/documentation
```

## 🧪 Testing API with cURL

### Register

```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "age": 25,
    "location": "Jakarta"
  }'
```

### Like a Person

```bash
curl -X POST http://localhost:8000/api/v1/swipe/like \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"person_id": 1}'
```

### Get Recommendations

```bash
curl http://localhost:8000/api/v1/people?per_page=10 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 📝 License

MIT License
