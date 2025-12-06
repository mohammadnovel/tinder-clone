# 🔥 Tinder Clone - Frontend (React Native)

Complete mobile app frontend for Tinder Clone built with React Native (Expo).

## 📋 Features

- ✅ Splash Screen with animation
- ✅ Login & Register screens
- ✅ Swipeable cards (like Tinder)
- ✅ Like (swipe right) with "LIKE" label
- ✅ Nope (swipe left) with "NOPE" label
- ✅ Multiple photos (tap to navigate)
- ✅ Action buttons (Nope, Super Like, Like)
- ✅ Activity screen (Matches, Likes, Nopes tabs)
- ✅ Profile screen with statistics
- ✅ Match modal animation
- ✅ Pagination & auto-load more
- ✅ 100 dummy profiles

## 🛠️ Tech Stack

- **Framework**: React Native (Expo)
- **State Management**: Recoil
- **Data Fetching**: React Query
- **Architecture**: Atomic Design
- **Animation**: React Native Reanimated
- **Gestures**: React Native Gesture Handler

## 🚀 Installation

### Step 1: Create Expo Project

```bash
npx create-expo-app tinder-clone --template blank-typescript
cd tinder-clone
```

### Step 2: Install Dependencies

```bash
# Core dependencies
npm install @tanstack/react-query recoil

# Navigation
npm install @react-navigation/native @react-navigation/stack
npm install react-native-screens react-native-safe-area-context

# Gesture & Animation
npm install react-native-gesture-handler react-native-reanimated

# UI
npm install expo-linear-gradient @expo/vector-icons
```

### Step 3: Update babel.config.js

```javascript
module.exports = function(api) {
  api.cache(true);
  return {
    presets: ['babel-preset-expo'],
    plugins: ['react-native-reanimated/plugin'],
  };
};
```

### Step 4: Copy Components

Follow the `GUIDE.md` file for step-by-step component creation.

Or use `TinderApp.jsx` as a reference (web preview version).

### Step 5: Run App

```bash
npx expo start
```

## 📁 Project Structure (Atomic Design)

```
src/
├── components/
│   ├── atoms/           # Smallest components
│   │   ├── IconButton.tsx
│   │   ├── Badge.tsx
│   │   └── SwipeLabel.tsx
│   │
│   ├── molecules/       # Combination of atoms
│   │   ├── ProfileInfo.tsx
│   │   ├── ImageIndicator.tsx
│   │   └── ActionButton.tsx
│   │
│   ├── organisms/       # Complex components
│   │   ├── SwipeCard.tsx
│   │   ├── CardStack.tsx
│   │   └── ActionBar.tsx
│   │
│   └── templates/       # Page layouts
│       └── MainTemplate.tsx
│
├── screens/
│   ├── SplashScreen.tsx
│   ├── LoginScreen.tsx
│   ├── RegisterScreen.tsx
│   ├── HomeScreen.tsx
│   ├── ActivityScreen.tsx
│   └── ProfileScreen.tsx
│
├── services/
│   └── api.ts           # API integration
│
├── state/
│   ├── atoms.ts         # Recoil atoms
│   └── selectors.ts     # Recoil selectors
│
├── hooks/
│   ├── useSwipe.ts
│   └── useProfiles.ts
│
├── types/
│   └── index.ts
│
└── theme/
    ├── colors.ts
    ├── fonts.ts
    └── spacing.ts
```

## 🔌 API Integration

Update `src/services/api.ts` to connect with Laravel backend:

```typescript
const API_BASE_URL = 'http://localhost:8000/api/v1';

export const api = {
  // Auth
  login: async (email: string, password: string) => {
    const response = await fetch(`${API_BASE_URL}/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
    });
    return response.json();
  },

  // People
  getPeople: async (token: string, page = 1) => {
    const response = await fetch(`${API_BASE_URL}/people?page=${page}`, {
      headers: { 'Authorization': `Bearer ${token}` },
    });
    return response.json();
  },

  // Swipes
  likePerson: async (token: string, personId: number) => {
    const response = await fetch(`${API_BASE_URL}/swipe/like`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ person_id: personId }),
    });
    return response.json();
  },

  // ... more endpoints
};
```

## 📱 Screens Overview

### 1. Splash Screen
- Animated logo
- Auto-redirect after 2 seconds

### 2. Login Screen
- Email & password inputs
- Demo account hints
- Link to register

### 3. Home Screen
- Swipeable card stack
- Stats bar (likes, nopes, matches)
- Action buttons

### 4. Activity Screen
- Tabs: Matches, Likes, Nopes
- Grid view of profiles

### 5. Profile Screen
- User info & photo
- Statistics
- Logout button

## 👤 Demo Accounts

| Email | Password |
|-------|----------|
| john@example.com | password123 |
| jane@example.com | password123 |

## 📝 Files Included

1. **GUIDE.md** - Step-by-step guide to build from scratch
2. **TinderApp.jsx** - Complete React component (web preview)

## 📝 License

MIT License
