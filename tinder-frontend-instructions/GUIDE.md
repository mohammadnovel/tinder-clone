# 📱 React Native Tinder Clone - Step by Step Guide

## 🎯 Overview
Membuat aplikasi Tinder clone dengan React Native menggunakan:
- **Atomic Design Pattern**
- **React Query** (untuk state management API)
- **Recoil** (untuk global state)
- **Dummy Data** (untuk development awal)

---

## 📁 Step 1: Project Setup

### 1.1 Inisialisasi Project
```bash
# Buat project baru
npx create-expo-app tinder-clone --template blank-typescript

# Masuk ke folder project
cd tinder-clone
```

### 1.2 Install Dependencies
```bash
# Core dependencies
npm install @tanstack/react-query recoil

# Navigation
npm install @react-navigation/native @react-navigation/stack
npm install react-native-screens react-native-safe-area-context

# Gesture & Animation untuk swipe card
npm install react-native-gesture-handler react-native-reanimated

# UI Components
npm install expo-linear-gradient
npm install @expo/vector-icons
```

### 1.3 Update babel.config.js
```javascript
module.exports = function(api) {
  api.cache(true);
  return {
    presets: ['babel-preset-expo'],
    plugins: ['react-native-reanimated/plugin'],
  };
};
```

---

## 📂 Step 2: Folder Structure (Atomic Design)

```
src/
├── components/
│   ├── atoms/           # Komponen terkecil
│   │   ├── Button.tsx
│   │   ├── IconButton.tsx
│   │   ├── Badge.tsx
│   │   └── Text.tsx
│   │
│   ├── molecules/       # Kombinasi atoms
│   │   ├── ProfileInfo.tsx
│   │   ├── ActionButton.tsx
│   │   └── LocationBadge.tsx
│   │
│   ├── organisms/       # Kombinasi molecules
│   │   ├── SwipeCard.tsx
│   │   ├── CardStack.tsx
│   │   └── ActionBar.tsx
│   │
│   └── templates/       # Layout halaman
│       ├── MainTemplate.tsx
│       └── ListTemplate.tsx
│
├── screens/
│   ├── SplashScreen.tsx
│   ├── HomeScreen.tsx
│   └── LikedListScreen.tsx
│
├── data/
│   └── dummyData.ts     # Data dummy
│
├── hooks/
│   ├── useSwipe.ts
│   └── useProfiles.ts
│
├── state/
│   ├── atoms.ts         # Recoil atoms
│   └── selectors.ts     # Recoil selectors
│
├── services/
│   └── api.ts           # API calls (dummy untuk sekarang)
│
├── theme/
│   ├── colors.ts
│   ├── fonts.ts
│   └── spacing.ts
│
├── types/
│   └── index.ts
│
└── utils/
    └── helpers.ts
```

Buat struktur folder:
```bash
mkdir -p src/{components/{atoms,molecules,organisms,templates},screens,data,hooks,state,services,theme,types,utils}
```

---

## 🎨 Step 3: Setup Theme

### 3.1 src/theme/colors.ts
```typescript
export const colors = {
  // Primary
  primary: '#FE3C72',      // Tinder pink/red
  primaryDark: '#E91E63',
  
  // Secondary
  secondary: '#00D4AA',    // Green for like
  tertiary: '#21D07C',     // Super like blue
  
  // Neutrals
  white: '#FFFFFF',
  black: '#000000',
  gray100: '#F7F7F7',
  gray200: '#E8E8E8',
  gray300: '#CCCCCC',
  gray400: '#999999',
  gray500: '#666666',
  gray600: '#333333',
  
  // Actions
  like: '#00D4AA',
  nope: '#FE3C72',
  superLike: '#17C3EC',
  
  // Overlay
  overlay: 'rgba(0,0,0,0.5)',
  cardShadow: 'rgba(0,0,0,0.1)',
};
```

### 3.2 src/theme/fonts.ts
```typescript
export const fonts = {
  regular: 'System',
  medium: 'System',
  bold: 'System',
  
  sizes: {
    xs: 12,
    sm: 14,
    md: 16,
    lg: 18,
    xl: 24,
    xxl: 32,
  },
};
```

### 3.3 src/theme/spacing.ts
```typescript
export const spacing = {
  xs: 4,
  sm: 8,
  md: 16,
  lg: 24,
  xl: 32,
  xxl: 48,
};

export const borderRadius = {
  sm: 8,
  md: 12,
  lg: 16,
  xl: 24,
  full: 9999,
};
```

---

## 📝 Step 4: Types Definition

### 4.1 src/types/index.ts
```typescript
export interface Person {
  id: string;
  name: string;
  age: number;
  pictures: string[];
  location: string;
  bio?: string;
  distance?: number;
}

export interface SwipeAction {
  type: 'like' | 'nope' | 'superlike';
  personId: string;
  timestamp: Date;
}

export type SwipeDirection = 'left' | 'right' | 'up';
```

---

## 🗃️ Step 5: Dummy Data

### 5.1 src/data/dummyData.ts
```typescript
import { Person } from '../types';

export const dummyProfiles: Person[] = [
  {
    id: '1',
    name: 'Sarah',
    age: 24,
    pictures: [
      'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400',
      'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=400',
    ],
    location: 'Jakarta',
    bio: 'Love traveling and good coffee ☕',
    distance: 5,
  },
  {
    id: '2',
    name: 'Amanda',
    age: 26,
    pictures: [
      'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=400',
      'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=400',
    ],
    location: 'Bandung',
    bio: 'Foodie 🍕 | Gym enthusiast 💪',
    distance: 12,
  },
  {
    id: '3',
    name: 'Jessica',
    age: 23,
    pictures: [
      'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=400',
    ],
    location: 'Surabaya',
    bio: 'Music lover 🎵',
    distance: 8,
  },
  {
    id: '4',
    name: 'Michelle',
    age: 25,
    pictures: [
      'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?w=400',
      'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?w=400',
    ],
    location: 'Yogyakarta',
    bio: 'Adventure seeker 🌍',
    distance: 15,
  },
  {
    id: '5',
    name: 'Diana',
    age: 27,
    pictures: [
      'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400',
    ],
    location: 'Bali',
    bio: 'Beach vibes only 🏖️',
    distance: 3,
  },
];
```

---

## ⚛️ Step 6: Recoil State Management

### 6.1 src/state/atoms.ts
```typescript
import { atom } from 'recoil';
import { Person, SwipeAction } from '../types';
import { dummyProfiles } from '../data/dummyData';

// Profiles yang tersedia untuk di-swipe
export const profilesState = atom<Person[]>({
  key: 'profilesState',
  default: dummyProfiles,
});

// Index card yang sedang aktif
export const currentIndexState = atom<number>({
  key: 'currentIndexState',
  default: 0,
});

// List orang yang sudah di-like
export const likedProfilesState = atom<Person[]>({
  key: 'likedProfilesState',
  default: [],
});

// History swipe actions
export const swipeHistoryState = atom<SwipeAction[]>({
  key: 'swipeHistoryState',
  default: [],
});
```

### 6.2 src/state/selectors.ts
```typescript
import { selector } from 'recoil';
import { profilesState, currentIndexState, likedProfilesState } from './atoms';

// Get current profile to display
export const currentProfileSelector = selector({
  key: 'currentProfileSelector',
  get: ({ get }) => {
    const profiles = get(profilesState);
    const currentIndex = get(currentIndexState);
    return profiles[currentIndex] || null;
  },
});

// Get remaining profiles count
export const remainingProfilesSelector = selector({
  key: 'remainingProfilesSelector',
  get: ({ get }) => {
    const profiles = get(profilesState);
    const currentIndex = get(currentIndexState);
    return profiles.length - currentIndex;
  },
});

// Get liked count
export const likedCountSelector = selector({
  key: 'likedCountSelector',
  get: ({ get }) => {
    const liked = get(likedProfilesState);
    return liked.length;
  },
});
```

---

## 🔲 Step 7: Atoms (Komponen Terkecil)

### 7.1 src/components/atoms/IconButton.tsx
```tsx
import React from 'react';
import {
  TouchableOpacity,
  StyleSheet,
  ViewStyle,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { colors } from '../../theme/colors';

interface IconButtonProps {
  icon: keyof typeof Ionicons.glyphMap;
  size?: 'small' | 'medium' | 'large';
  color?: string;
  backgroundColor?: string;
  onPress: () => void;
  style?: ViewStyle;
  disabled?: boolean;
}

const sizeMap = {
  small: { button: 44, icon: 20 },
  medium: { button: 56, icon: 28 },
  large: { button: 64, icon: 32 },
};

export const IconButton: React.FC<IconButtonProps> = ({
  icon,
  size = 'medium',
  color = colors.gray600,
  backgroundColor = colors.white,
  onPress,
  style,
  disabled = false,
}) => {
  const dimensions = sizeMap[size];

  return (
    <TouchableOpacity
      onPress={onPress}
      disabled={disabled}
      style={[
        styles.button,
        {
          width: dimensions.button,
          height: dimensions.button,
          backgroundColor,
          opacity: disabled ? 0.5 : 1,
        },
        style,
      ]}
      activeOpacity={0.7}
    >
      <Ionicons name={icon} size={dimensions.icon} color={color} />
    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  button: {
    borderRadius: 999,
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: colors.black,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 4,
  },
});
```

### 7.2 src/components/atoms/Badge.tsx
```tsx
import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { colors } from '../../theme/colors';
import { fonts } from '../../theme/fonts';

interface BadgeProps {
  label: string;
  icon?: string;
}

export const Badge: React.FC<BadgeProps> = ({ label, icon }) => {
  return (
    <View style={styles.container}>
      {icon && <Text style={styles.icon}>{icon}</Text>}
      <Text style={styles.label}>{label}</Text>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(255,255,255,0.9)',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 20,
  },
  icon: {
    marginRight: 4,
    fontSize: fonts.sizes.sm,
  },
  label: {
    color: colors.gray600,
    fontSize: fonts.sizes.sm,
    fontWeight: '500',
  },
});
```

### 7.3 src/components/atoms/SwipeLabel.tsx
```tsx
import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import Animated, { 
  useAnimatedStyle, 
  interpolate,
  Extrapolate 
} from 'react-native-reanimated';
import { colors } from '../../theme/colors';

interface SwipeLabelProps {
  type: 'like' | 'nope';
  animatedValue: Animated.SharedValue<number>;
}

export const SwipeLabel: React.FC<SwipeLabelProps> = ({ 
  type, 
  animatedValue 
}) => {
  const isLike = type === 'like';

  const animatedStyle = useAnimatedStyle(() => {
    const opacity = interpolate(
      animatedValue.value,
      isLike ? [0, 50, 100] : [-100, -50, 0],
      isLike ? [0, 0.5, 1] : [1, 0.5, 0],
      Extrapolate.CLAMP
    );

    const rotate = isLike ? '-15deg' : '15deg';

    return {
      opacity,
      transform: [{ rotate }],
    };
  });

  return (
    <Animated.View
      style={[
        styles.container,
        isLike ? styles.likeContainer : styles.nopeContainer,
        animatedStyle,
      ]}
    >
      <Text style={[
        styles.text,
        { color: isLike ? colors.like : colors.nope }
      ]}>
        {isLike ? 'LIKE' : 'NOPE'}
      </Text>
    </Animated.View>
  );
};

const styles = StyleSheet.create({
  container: {
    position: 'absolute',
    top: 50,
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderWidth: 4,
    borderRadius: 8,
    zIndex: 10,
  },
  likeContainer: {
    left: 20,
    borderColor: colors.like,
  },
  nopeContainer: {
    right: 20,
    borderColor: colors.nope,
  },
  text: {
    fontSize: 32,
    fontWeight: '800',
    letterSpacing: 2,
  },
});
```

---

## 🧬 Step 8: Molecules (Kombinasi Atoms)

### 8.1 src/components/molecules/ProfileInfo.tsx
```tsx
import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { colors } from '../../theme/colors';
import { fonts } from '../../theme/fonts';
import { spacing } from '../../theme/spacing';

interface ProfileInfoProps {
  name: string;
  age: number;
  location: string;
  distance?: number;
  bio?: string;
}

export const ProfileInfo: React.FC<ProfileInfoProps> = ({
  name,
  age,
  location,
  distance,
  bio,
}) => {
  return (
    <View style={styles.container}>
      <View style={styles.nameRow}>
        <Text style={styles.name}>{name}</Text>
        <Text style={styles.age}>{age}</Text>
      </View>
      
      <View style={styles.locationRow}>
        <Ionicons name="location" size={16} color={colors.white} />
        <Text style={styles.location}>
          {location} {distance && `• ${distance} km away`}
        </Text>
      </View>

      {bio && (
        <Text style={styles.bio} numberOfLines={2}>
          {bio}
        </Text>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    padding: spacing.md,
  },
  nameRow: {
    flexDirection: 'row',
    alignItems: 'baseline',
    marginBottom: spacing.xs,
  },
  name: {
    fontSize: fonts.sizes.xxl,
    fontWeight: '700',
    color: colors.white,
    marginRight: spacing.sm,
  },
  age: {
    fontSize: fonts.sizes.xl,
    fontWeight: '400',
    color: colors.white,
  },
  locationRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: spacing.xs,
  },
  location: {
    fontSize: fonts.sizes.sm,
    color: colors.white,
    marginLeft: spacing.xs,
  },
  bio: {
    fontSize: fonts.sizes.md,
    color: colors.white,
    opacity: 0.9,
    marginTop: spacing.xs,
  },
});
```

### 8.2 src/components/molecules/ImageIndicator.tsx
```tsx
import React from 'react';
import { View, StyleSheet } from 'react-native';
import { colors } from '../../theme/colors';

interface ImageIndicatorProps {
  total: number;
  current: number;
}

export const ImageIndicator: React.FC<ImageIndicatorProps> = ({
  total,
  current,
}) => {
  if (total <= 1) return null;

  return (
    <View style={styles.container}>
      {Array.from({ length: total }).map((_, index) => (
        <View
          key={index}
          style={[
            styles.indicator,
            index === current && styles.activeIndicator,
          ]}
        />
      ))}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    justifyContent: 'center',
    paddingHorizontal: 8,
    paddingTop: 8,
    gap: 4,
  },
  indicator: {
    flex: 1,
    height: 4,
    backgroundColor: 'rgba(255,255,255,0.4)',
    borderRadius: 2,
  },
  activeIndicator: {
    backgroundColor: colors.white,
  },
});
```

---

## 🦠 Step 9: Organisms (Komponen Kompleks)

### 9.1 src/components/organisms/SwipeCard.tsx
```tsx
import React, { useState } from 'react';
import {
  View,
  Image,
  StyleSheet,
  Dimensions,
  TouchableWithoutFeedback,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import Animated, {
  useSharedValue,
  useAnimatedStyle,
  useAnimatedGestureHandler,
  withSpring,
  runOnJS,
  interpolate,
  Extrapolate,
} from 'react-native-reanimated';
import { PanGestureHandler } from 'react-native-gesture-handler';
import { Person } from '../../types';
import { ProfileInfo } from '../molecules/ProfileInfo';
import { ImageIndicator } from '../molecules/ImageIndicator';
import { SwipeLabel } from '../atoms/SwipeLabel';
import { colors } from '../../theme/colors';
import { borderRadius } from '../../theme/spacing';

const { width: SCREEN_WIDTH, height: SCREEN_HEIGHT } = Dimensions.get('window');
const SWIPE_THRESHOLD = SCREEN_WIDTH * 0.25;

interface SwipeCardProps {
  person: Person;
  onSwipeLeft: () => void;
  onSwipeRight: () => void;
  isFirst: boolean;
}

export const SwipeCard: React.FC<SwipeCardProps> = ({
  person,
  onSwipeLeft,
  onSwipeRight,
  isFirst,
}) => {
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const translateX = useSharedValue(0);
  const translateY = useSharedValue(0);

  const handleImageTap = (side: 'left' | 'right') => {
    if (side === 'right' && currentImageIndex < person.pictures.length - 1) {
      setCurrentImageIndex(currentImageIndex + 1);
    } else if (side === 'left' && currentImageIndex > 0) {
      setCurrentImageIndex(currentImageIndex - 1);
    }
  };

  const gestureHandler = useAnimatedGestureHandler({
    onStart: (_, context: any) => {
      context.startX = translateX.value;
      context.startY = translateY.value;
    },
    onActive: (event, context) => {
      translateX.value = context.startX + event.translationX;
      translateY.value = context.startY + event.translationY;
    },
    onEnd: (event) => {
      if (translateX.value > SWIPE_THRESHOLD) {
        translateX.value = withSpring(SCREEN_WIDTH + 100);
        runOnJS(onSwipeRight)();
      } else if (translateX.value < -SWIPE_THRESHOLD) {
        translateX.value = withSpring(-SCREEN_WIDTH - 100);
        runOnJS(onSwipeLeft)();
      } else {
        translateX.value = withSpring(0);
        translateY.value = withSpring(0);
      }
    },
  });

  const cardStyle = useAnimatedStyle(() => {
    const rotate = interpolate(
      translateX.value,
      [-SCREEN_WIDTH / 2, 0, SCREEN_WIDTH / 2],
      [-15, 0, 15],
      Extrapolate.CLAMP
    );

    return {
      transform: [
        { translateX: translateX.value },
        { translateY: translateY.value },
        { rotate: `${rotate}deg` },
      ],
    };
  });

  const nextCardStyle = useAnimatedStyle(() => {
    const scale = interpolate(
      Math.abs(translateX.value),
      [0, SCREEN_WIDTH / 2],
      [0.95, 1],
      Extrapolate.CLAMP
    );

    return {
      transform: [{ scale }],
    };
  });

  if (!isFirst) {
    return (
      <Animated.View style={[styles.card, styles.nextCard, nextCardStyle]}>
        <Image
          source={{ uri: person.pictures[0] }}
          style={styles.image}
          resizeMode="cover"
        />
      </Animated.View>
    );
  }

  return (
    <PanGestureHandler onGestureEvent={gestureHandler}>
      <Animated.View style={[styles.card, cardStyle]}>
        {/* Image */}
        <Image
          source={{ uri: person.pictures[currentImageIndex] }}
          style={styles.image}
          resizeMode="cover"
        />

        {/* Image Indicator */}
        <View style={styles.indicatorContainer}>
          <ImageIndicator
            total={person.pictures.length}
            current={currentImageIndex}
          />
        </View>

        {/* Tap Areas for Image Navigation */}
        <View style={styles.tapContainer}>
          <TouchableWithoutFeedback onPress={() => handleImageTap('left')}>
            <View style={styles.tapArea} />
          </TouchableWithoutFeedback>
          <TouchableWithoutFeedback onPress={() => handleImageTap('right')}>
            <View style={styles.tapArea} />
          </TouchableWithoutFeedback>
        </View>

        {/* Swipe Labels */}
        <SwipeLabel type="like" animatedValue={translateX} />
        <SwipeLabel type="nope" animatedValue={translateX} />

        {/* Gradient Overlay */}
        <LinearGradient
          colors={['transparent', 'rgba(0,0,0,0.8)']}
          style={styles.gradient}
        >
          <ProfileInfo
            name={person.name}
            age={person.age}
            location={person.location}
            distance={person.distance}
            bio={person.bio}
          />
        </LinearGradient>
      </Animated.View>
    </PanGestureHandler>
  );
};

const styles = StyleSheet.create({
  card: {
    width: SCREEN_WIDTH - 20,
    height: SCREEN_HEIGHT * 0.7,
    borderRadius: borderRadius.lg,
    position: 'absolute',
    overflow: 'hidden',
    backgroundColor: colors.gray200,
    shadowColor: colors.black,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.15,
    shadowRadius: 12,
    elevation: 8,
  },
  nextCard: {
    top: 10,
  },
  image: {
    width: '100%',
    height: '100%',
  },
  indicatorContainer: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
  },
  tapContainer: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    flexDirection: 'row',
  },
  tapArea: {
    flex: 1,
  },
  gradient: {
    position: 'absolute',
    left: 0,
    right: 0,
    bottom: 0,
    height: '40%',
    justifyContent: 'flex-end',
  },
});
```

### 9.2 src/components/organisms/ActionBar.tsx
```tsx
import React from 'react';
import { View, StyleSheet } from 'react-native';
import { IconButton } from '../atoms/IconButton';
import { colors } from '../../theme/colors';
import { spacing } from '../../theme/spacing';

interface ActionBarProps {
  onNope: () => void;
  onLike: () => void;
  onSuperLike?: () => void;
  disabled?: boolean;
}

export const ActionBar: React.FC<ActionBarProps> = ({
  onNope,
  onLike,
  onSuperLike,
  disabled = false,
}) => {
  return (
    <View style={styles.container}>
      {/* Nope Button */}
      <IconButton
        icon="close"
        size="large"
        color={colors.nope}
        backgroundColor={colors.white}
        onPress={onNope}
        disabled={disabled}
        style={styles.mainButton}
      />

      {/* Super Like Button (Optional) */}
      {onSuperLike && (
        <IconButton
          icon="star"
          size="medium"
          color={colors.superLike}
          backgroundColor={colors.white}
          onPress={onSuperLike}
          disabled={disabled}
        />
      )}

      {/* Like Button */}
      <IconButton
        icon="heart"
        size="large"
        color={colors.like}
        backgroundColor={colors.white}
        onPress={onLike}
        disabled={disabled}
        style={styles.mainButton}
      />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: spacing.lg,
    gap: spacing.lg,
  },
  mainButton: {
    shadowColor: colors.black,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.15,
    shadowRadius: 8,
    elevation: 6,
  },
});
```

---

## 📄 Step 10: Screens

### 10.1 src/screens/SplashScreen.tsx
```tsx
import React, { useEffect } from 'react';
import { View, StyleSheet, Animated, Easing } from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import { colors } from '../theme/colors';

interface SplashScreenProps {
  onFinish: () => void;
}

export const SplashScreen: React.FC<SplashScreenProps> = ({ onFinish }) => {
  const scaleAnim = new Animated.Value(0.5);
  const opacityAnim = new Animated.Value(0);

  useEffect(() => {
    // Animate logo
    Animated.parallel([
      Animated.timing(scaleAnim, {
        toValue: 1,
        duration: 800,
        easing: Easing.elastic(1.2),
        useNativeDriver: true,
      }),
      Animated.timing(opacityAnim, {
        toValue: 1,
        duration: 600,
        useNativeDriver: true,
      }),
    ]).start();

    // Navigate after delay
    const timer = setTimeout(onFinish, 2500);
    return () => clearTimeout(timer);
  }, []);

  return (
    <LinearGradient
      colors={[colors.primary, colors.primaryDark]}
      style={styles.container}
    >
      <Animated.View
        style={[
          styles.logoContainer,
          {
            transform: [{ scale: scaleAnim }],
            opacity: opacityAnim,
          },
        ]}
      >
        <Ionicons name="flame" size={100} color={colors.white} />
        <Animated.Text style={styles.title}>tinder</Animated.Text>
      </Animated.View>
    </LinearGradient>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  logoContainer: {
    alignItems: 'center',
  },
  title: {
    fontSize: 48,
    fontWeight: '700',
    color: colors.white,
    marginTop: 16,
    letterSpacing: 2,
  },
});
```

### 10.2 src/screens/HomeScreen.tsx
```tsx
import React, { useCallback } from 'react';
import { View, Text, StyleSheet, SafeAreaView } from 'react-native';
import { useRecoilState, useRecoilValue } from 'recoil';
import { 
  profilesState, 
  currentIndexState, 
  likedProfilesState 
} from '../state/atoms';
import { 
  currentProfileSelector, 
  remainingProfilesSelector 
} from '../state/selectors';
import { SwipeCard } from '../components/organisms/SwipeCard';
import { ActionBar } from '../components/organisms/ActionBar';
import { IconButton } from '../components/atoms/IconButton';
import { colors } from '../theme/colors';
import { spacing } from '../theme/spacing';

interface HomeScreenProps {
  onNavigateToLiked: () => void;
}

export const HomeScreen: React.FC<HomeScreenProps> = ({ 
  onNavigateToLiked 
}) => {
  const profiles = useRecoilValue(profilesState);
  const [currentIndex, setCurrentIndex] = useRecoilState(currentIndexState);
  const [likedProfiles, setLikedProfiles] = useRecoilState(likedProfilesState);
  const currentProfile = useRecoilValue(currentProfileSelector);
  const remainingCount = useRecoilValue(remainingProfilesSelector);

  const handleSwipeLeft = useCallback(() => {
    console.log('NOPE:', profiles[currentIndex]?.name);
    setCurrentIndex((prev) => prev + 1);
  }, [currentIndex, profiles]);

  const handleSwipeRight = useCallback(() => {
    const profile = profiles[currentIndex];
    console.log('LIKE:', profile?.name);
    if (profile) {
      setLikedProfiles((prev) => [...prev, profile]);
    }
    setCurrentIndex((prev) => prev + 1);
  }, [currentIndex, profiles]);

  const renderCards = () => {
    if (remainingCount === 0) {
      return (
        <View style={styles.emptyContainer}>
          <Text style={styles.emptyText}>No more profiles!</Text>
          <Text style={styles.emptySubtext}>Check back later</Text>
        </View>
      );
    }

    // Render top 2 cards for stacking effect
    return profiles
      .slice(currentIndex, currentIndex + 2)
      .reverse()
      .map((person, index, arr) => (
        <SwipeCard
          key={person.id}
          person={person}
          isFirst={index === arr.length - 1}
          onSwipeLeft={handleSwipeLeft}
          onSwipeRight={handleSwipeRight}
        />
      ));
  };

  return (
    <SafeAreaView style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <IconButton
          icon="person-outline"
          size="small"
          color={colors.gray400}
          backgroundColor="transparent"
          onPress={() => {}}
        />
        
        <View style={styles.logoContainer}>
          <IconButton
            icon="flame"
            size="medium"
            color={colors.primary}
            backgroundColor="transparent"
            onPress={() => {}}
          />
        </View>

        <IconButton
          icon="chatbubbles-outline"
          size="small"
          color={colors.gray400}
          backgroundColor="transparent"
          onPress={onNavigateToLiked}
        />
      </View>

      {/* Card Stack */}
      <View style={styles.cardContainer}>
        {renderCards()}
      </View>

      {/* Action Bar */}
      <ActionBar
        onNope={handleSwipeLeft}
        onLike={handleSwipeRight}
        disabled={remainingCount === 0}
      />
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.gray100,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: spacing.md,
    paddingVertical: spacing.sm,
  },
  logoContainer: {
    alignItems: 'center',
  },
  cardContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  emptyContainer: {
    alignItems: 'center',
  },
  emptyText: {
    fontSize: 24,
    fontWeight: '600',
    color: colors.gray600,
    marginBottom: spacing.sm,
  },
  emptySubtext: {
    fontSize: 16,
    color: colors.gray400,
  },
});
```

### 10.3 src/screens/LikedListScreen.tsx
```tsx
import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  Image,
  SafeAreaView,
  TouchableOpacity,
  Dimensions,
} from 'react-native';
import { useRecoilValue } from 'recoil';
import { Ionicons } from '@expo/vector-icons';
import { likedProfilesState } from '../state/atoms';
import { likedCountSelector } from '../state/selectors';
import { Person } from '../types';
import { colors } from '../theme/colors';
import { spacing, borderRadius } from '../theme/spacing';
import { fonts } from '../theme/fonts';

const { width } = Dimensions.get('window');
const CARD_WIDTH = (width - spacing.md * 3) / 2;

interface LikedListScreenProps {
  onGoBack: () => void;
}

export const LikedListScreen: React.FC<LikedListScreenProps> = ({ 
  onGoBack 
}) => {
  const likedProfiles = useRecoilValue(likedProfilesState);
  const likedCount = useRecoilValue(likedCountSelector);

  const renderItem = ({ item }: { item: Person }) => (
    <View style={styles.card}>
      <Image
        source={{ uri: item.pictures[0] }}
        style={styles.cardImage}
        resizeMode="cover"
      />
      <View style={styles.cardOverlay}>
        <Text style={styles.cardName}>{item.name}, {item.age}</Text>
        <Text style={styles.cardLocation}>{item.location}</Text>
      </View>
    </View>
  );

  const renderEmptyState = () => (
    <View style={styles.emptyContainer}>
      <Ionicons name="heart-outline" size={80} color={colors.gray300} />
      <Text style={styles.emptyText}>No likes yet</Text>
      <Text style={styles.emptySubtext}>
        Start swiping to find your matches!
      </Text>
    </View>
  );

  return (
    <SafeAreaView style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <TouchableOpacity onPress={onGoBack} style={styles.backButton}>
          <Ionicons name="chevron-back" size={28} color={colors.gray600} />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Liked ({likedCount})</Text>
        <View style={styles.placeholder} />
      </View>

      {/* List */}
      <FlatList
        data={likedProfiles}
        renderItem={renderItem}
        keyExtractor={(item) => item.id}
        numColumns={2}
        contentContainerStyle={styles.listContent}
        columnWrapperStyle={styles.row}
        ListEmptyComponent={renderEmptyState}
        showsVerticalScrollIndicator={false}
      />
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.white,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: spacing.md,
    paddingVertical: spacing.md,
    borderBottomWidth: 1,
    borderBottomColor: colors.gray200,
  },
  backButton: {
    padding: spacing.xs,
  },
  headerTitle: {
    fontSize: fonts.sizes.lg,
    fontWeight: '600',
    color: colors.gray600,
  },
  placeholder: {
    width: 40,
  },
  listContent: {
    padding: spacing.md,
    flexGrow: 1,
  },
  row: {
    justifyContent: 'space-between',
    marginBottom: spacing.md,
  },
  card: {
    width: CARD_WIDTH,
    height: CARD_WIDTH * 1.4,
    borderRadius: borderRadius.md,
    overflow: 'hidden',
    backgroundColor: colors.gray200,
  },
  cardImage: {
    width: '100%',
    height: '100%',
  },
  cardOverlay: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    padding: spacing.sm,
    backgroundColor: 'rgba(0,0,0,0.5)',
  },
  cardName: {
    fontSize: fonts.sizes.md,
    fontWeight: '600',
    color: colors.white,
  },
  cardLocation: {
    fontSize: fonts.sizes.sm,
    color: colors.white,
    opacity: 0.8,
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingBottom: 100,
  },
  emptyText: {
    fontSize: fonts.sizes.xl,
    fontWeight: '600',
    color: colors.gray500,
    marginTop: spacing.md,
  },
  emptySubtext: {
    fontSize: fonts.sizes.md,
    color: colors.gray400,
    marginTop: spacing.sm,
    textAlign: 'center',
  },
});
```

---

## 🚀 Step 11: App Entry Point

### 11.1 App.tsx
```tsx
import React, { useState } from 'react';
import { StatusBar } from 'expo-status-bar';
import { GestureHandlerRootView } from 'react-native-gesture-handler';
import { RecoilRoot } from 'recoil';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { SplashScreen } from './src/screens/SplashScreen';
import { HomeScreen } from './src/screens/HomeScreen';
import { LikedListScreen } from './src/screens/LikedListScreen';

const queryClient = new QueryClient();

type Screen = 'splash' | 'home' | 'liked';

export default function App() {
  const [currentScreen, setCurrentScreen] = useState<Screen>('splash');

  const renderScreen = () => {
    switch (currentScreen) {
      case 'splash':
        return <SplashScreen onFinish={() => setCurrentScreen('home')} />;
      case 'home':
        return (
          <HomeScreen 
            onNavigateToLiked={() => setCurrentScreen('liked')} 
          />
        );
      case 'liked':
        return (
          <LikedListScreen 
            onGoBack={() => setCurrentScreen('home')} 
          />
        );
      default:
        return null;
    }
  };

  return (
    <GestureHandlerRootView style={{ flex: 1 }}>
      <RecoilRoot>
        <QueryClientProvider client={queryClient}>
          <StatusBar style="dark" />
          {renderScreen()}
        </QueryClientProvider>
      </RecoilRoot>
    </GestureHandlerRootView>
  );
}
```

---

## ✅ Step 12: Checklist & Testing

### Features Checklist:
- [x] Splash Screen dengan animasi logo
- [x] Swipeable cards (seperti Tinder)
- [x] Swipe Right = Like (dengan label "LIKE" yang muncul)
- [x] Swipe Left = Nope (dengan label "NOPE" yang muncul)
- [x] Action buttons (Like & Nope)
- [x] Multiple images per profile (tap untuk ganti)
- [x] Image indicator
- [x] Profile info (name, age, location, bio)
- [x] Liked people list (grid view)
- [x] State management dengan Recoil
- [x] React Query setup (siap untuk integrasi API)

### Running the App:
```bash
# Start development server
npx expo start

# Run on iOS simulator
npx expo run:ios

# Run on Android emulator
npx expo run:android
```

---

## 📱 Preview Layout

```
┌─────────────────────────────┐
│  [👤]     🔥     [💬]       │  ← Header
├─────────────────────────────┤
│                             │
│  ┌───────────────────────┐  │
│  │ ● ○ ○                 │  │  ← Image indicators
│  │                       │  │
│  │     [LIKE]   [NOPE]   │  │  ← Swipe labels
│  │                       │  │
│  │    ┌─────────────┐    │  │
│  │    │             │    │  │
│  │    │   PHOTO     │    │  │
│  │    │             │    │  │
│  │    │             │    │  │
│  │    └─────────────┘    │  │
│  │ ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ │  │  ← Gradient overlay
│  │ Sarah, 24              │  │
│  │ 📍 Jakarta • 5km away  │  │
│  └───────────────────────┘  │
│                             │
│      [✕]    [⭐]    [❤️]    │  ← Action buttons
│                             │
└─────────────────────────────┘
```

---

## 🔄 Next Steps (Integrasi Backend)

Setelah tampilan selesai dengan dummy data, langkah selanjutnya:

1. **Buat API service** di `src/services/api.ts`
2. **Integrasi React Query** untuk fetch data dari Laravel backend
3. **Handle loading & error states**
4. **Implementasi pagination**

Butuh bantuan untuk langkah selanjutnya atau ada pertanyaan tentang implementasi di atas?
