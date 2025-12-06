import React, { useState, useRef, useEffect, useCallback } from 'react';
import { Heart, X, Star, MapPin, ChevronLeft, MessageCircle, User, Flame, Loader2, RefreshCw, LogOut, Users, HeartCrack, Sparkles, Eye, EyeOff, Mail, Lock, UserPlus, ArrowRight } from 'lucide-react';

// ============================================
// DATABASE & MOCK API
// ============================================

// ============================================
// API INTEGRATION
// ============================================

const API_URL = 'http://localhost:8000/api/v1';

const getAuthHeader = () => {
  const user = JSON.parse(localStorage.getItem('tinder_user'));
  return user?.token ? { 'Authorization': `Bearer ${user.token}` } : {};
};

const api = {
  // Auth
  login: async (email, password) => {
    const res = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Login failed');
    return { 
        success: true, 
        user: { ...data.data.user, token: data.data.token } 
    };
  },

  register: async (userData) => {
    const res = await fetch(`${API_URL}/auth/register`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(userData),
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Registration failed');
    return { 
        success: true, 
        user: { ...data.data.user, token: data.data.token } 
    };
  },

  logout: async () => {
    try {
        await fetch(`${API_URL}/auth/logout`, {
            method: 'POST',
            headers: { ...getAuthHeader(), 'Content-Type': 'application/json' }
        });
    } catch (e) {
        console.error('Logout failed', e);
    }
    return { success: true };
  },

  // People
  getPeople: async () => {
    const res = await fetch(`${API_URL}/people`, {
        headers: getAuthHeader()
    });
    if (!res.ok) throw new Error('Failed to fetch people');
    const data = await res.json();
    return { data: data.data };
  },

  // Swipes
  likePerson: async (personId) => {
    const res = await fetch(`${API_URL}/swipe/like`, {
        method: 'POST',
        headers: { ...getAuthHeader(), 'Content-Type': 'application/json' },
        body: JSON.stringify({ person_id: personId })
    });
    const data = await res.json();
    return { success: res.ok, data: data.data };
  },

  dislikePerson: async (personId) => {
    const res = await fetch(`${API_URL}/swipe/dislike`, {
        method: 'POST',
        headers: { ...getAuthHeader(), 'Content-Type': 'application/json' },
        body: JSON.stringify({ person_id: personId })
    });
    return { success: res.ok };
  },

  // Matches
  getMatches: async () => {
      const res = await fetch(`${API_URL}/people/matches`, {
          headers: getAuthHeader()
      });
      const data = await res.json();
      return { data: data.data };
  },

  // Get liked people
  getLikedPeople: async () => {
      const res = await fetch(`${API_URL}/people/liked`, {
          headers: getAuthHeader()
      });
      const data = await res.json();
      return { data: data.data };
  },

  // Get disliked people
  getDislikedPeople: async () => {
      const res = await fetch(`${API_URL}/people/disliked`, {
          headers: getAuthHeader()
      });
      const data = await res.json();
      return { data: data.data };
  },

  // Stats
  getStats: async () => {
      const res = await fetch(`${API_URL}/swipe/stats`, {
          headers: getAuthHeader()
      });
      const data = await res.json();
      return { data: data.data };
  }
};

// ============================================
// COMPONENTS
// ============================================

// Login Screen
const LoginScreen = ({ onLogin, onSwitchToRegister }) => {
  const [email, setEmail] = useState('john@example.com');
  const [password, setPassword] = useState('password123');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setIsLoading(true);
    
    try {
      const result = await api.login(email, password);
      onLogin(result.user);
    } catch (err) {
      setError(err.message);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="h-full bg-gradient-to-b from-pink-500 to-rose-600 flex flex-col">
      {/* Header */}
      <div className="flex-1 flex flex-col items-center justify-center p-8">
        <Flame size={64} className="text-white mb-4" />
        <h1 className="text-white text-4xl font-bold tracking-wider">tinder</h1>
        <p className="text-white/70 mt-2">Find your perfect match</p>
      </div>

      {/* Form */}
      <div className="bg-white rounded-t-3xl p-8 shadow-2xl">
        <h2 className="text-2xl font-bold text-gray-800 mb-6">Welcome Back</h2>
        
        {error && (
          <div className="bg-red-50 text-red-600 px-4 py-3 rounded-xl mb-4 text-sm">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-600 mb-2">Email</label>
            <div className="relative">
              <Mail size={20} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition"
                placeholder="Enter your email"
                required
              />
            </div>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-600 mb-2">Password</label>
            <div className="relative">
              <Lock size={20} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
              <input
                type={showPassword ? 'text' : 'password'}
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                className="w-full pl-12 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition"
                placeholder="Enter your password"
                required
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
              >
                {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
              </button>
            </div>
          </div>

          <button
            type="submit"
            disabled={isLoading}
            className="w-full bg-gradient-to-r from-pink-500 to-rose-500 text-white py-3 rounded-xl font-semibold hover:from-pink-600 hover:to-rose-600 transition disabled:opacity-50 flex items-center justify-center gap-2"
          >
            {isLoading ? (
              <Loader2 size={20} className="animate-spin" />
            ) : (
              <>
                Sign In <ArrowRight size={20} />
              </>
            )}
          </button>
        </form>

        <div className="mt-6 text-center">
          <p className="text-gray-500">
            Don't have an account?{' '}
            <button
              onClick={onSwitchToRegister}
              className="text-rose-500 font-semibold hover:underline"
            >
              Sign Up
            </button>
          </p>
        </div>

        <div className="mt-4 p-3 bg-gray-50 rounded-xl text-xs text-gray-500">
          <p className="font-medium mb-1">Demo Accounts:</p>
          <p>📧 john@example.com / password123</p>
          <p>📧 jane@example.com / password123</p>
        </div>
      </div>
    </div>
  );
};

// Register Screen
const RegisterScreen = ({ onRegister, onSwitchToLogin }) => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    age: '',
    location: 'Jakarta',
  });
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setIsLoading(true);
    
    try {
      const result = await api.register({
        ...formData,
        age: parseInt(formData.age),
      });
      onRegister(result.user);
    } catch (err) {
      setError(err.message);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="h-full bg-gradient-to-b from-pink-500 to-rose-600 flex flex-col">
      {/* Header */}
      <div className="p-6 flex items-center">
        <button onClick={onSwitchToLogin} className="text-white p-2">
          <ChevronLeft size={28} />
        </button>
        <h1 className="text-white text-xl font-bold flex-1 text-center mr-10">Create Account</h1>
      </div>

      {/* Form */}
      <div className="flex-1 bg-white rounded-t-3xl p-8 shadow-2xl overflow-y-auto">
        {error && (
          <div className="bg-red-50 text-red-600 px-4 py-3 rounded-xl mb-4 text-sm">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-600 mb-2">Full Name</label>
            <input
              type="text"
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              className="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none"
              placeholder="Enter your name"
              required
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-600 mb-2">Email</label>
            <input
              type="email"
              value={formData.email}
              onChange={(e) => setFormData({ ...formData, email: e.target.value })}
              className="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none"
              placeholder="Enter your email"
              required
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-600 mb-2">Password</label>
            <div className="relative">
              <input
                type={showPassword ? 'text' : 'password'}
                value={formData.password}
                onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                className="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none"
                placeholder="Create a password"
                required
                minLength={6}
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"
              >
                {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
              </button>
            </div>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">Age</label>
              <input
                type="number"
                value={formData.age}
                onChange={(e) => setFormData({ ...formData, age: e.target.value })}
                className="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none"
                placeholder="Age"
                min={18}
                max={100}
                required
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">Location</label>
              <select
                value={formData.location}
                onChange={(e) => setFormData({ ...formData, location: e.target.value })}
                className="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none bg-white"
              >
                <option>Jakarta</option>
                <option>Bandung</option>
                <option>Surabaya</option>
                <option>Yogyakarta</option>
                <option>Bali</option>
                <option>Semarang</option>
                <option>Medan</option>
              </select>
            </div>
          </div>

          <button
            type="submit"
            disabled={isLoading}
            className="w-full bg-gradient-to-r from-pink-500 to-rose-500 text-white py-3 rounded-xl font-semibold hover:from-pink-600 hover:to-rose-600 transition disabled:opacity-50 flex items-center justify-center gap-2 mt-6"
          >
            {isLoading ? (
              <Loader2 size={20} className="animate-spin" />
            ) : (
              <>
                <UserPlus size={20} /> Create Account
              </>
            )}
          </button>
        </form>
      </div>
    </div>
  );
};

// Splash Screen
const SplashScreen = ({ onFinish }) => {
  useEffect(() => {
    const timer = setTimeout(onFinish, 2000);
    return () => clearTimeout(timer);
  }, [onFinish]);

  return (
    <div className="h-full bg-gradient-to-b from-pink-500 to-rose-600 flex flex-col items-center justify-center">
      <div className="animate-bounce">
        <Flame size={80} className="text-white" />
      </div>
      <h1 className="text-white text-4xl font-bold mt-4 tracking-wider animate-pulse">tinder</h1>
      <p className="text-white/70 mt-4 text-sm">100 profiles ready</p>
    </div>
  );
};

// Match Modal
const MatchModal = ({ person, onClose }) => {
  if (!person) return null;
  
  return (
    <div className="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
      <div className="bg-gradient-to-b from-pink-500 to-rose-600 rounded-3xl p-8 max-w-sm w-full text-center">
        <Sparkles size={48} className="text-yellow-300 mx-auto mb-2" />
        <h2 className="text-white text-3xl font-bold mb-2">It's a Match!</h2>
        <p className="text-white/80 mb-6">You and {person.name} liked each other</p>
        <img
          src={person.pictures[0]}
          alt={person.name}
          className="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-xl"
        />
        <p className="text-white text-xl font-semibold mt-4">{person.name}, {person.age}</p>
        <button
          onClick={onClose}
          className="mt-6 bg-white text-rose-500 font-semibold px-8 py-3 rounded-full hover:bg-gray-100 transition"
        >
          Keep Swiping
        </button>
      </div>
    </div>
  );
};

// Swipe Card
const SwipeCard = ({ person, onSwipeLeft, onSwipeRight, isTop, isLoading }) => {
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const [dragStart, setDragStart] = useState(null);
  const [dragOffset, setDragOffset] = useState({ x: 0, y: 0 });
  const [isDragging, setIsDragging] = useState(false);

  const handleDragStart = (e) => {
    if (!isTop || isLoading) return;
    const clientX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
    const clientY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
    setDragStart({ x: clientX, y: clientY });
    setIsDragging(true);
  };

  const handleDragMove = (e) => {
    if (!dragStart || !isTop) return;
    const clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
    const clientY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;
    setDragOffset({ x: clientX - dragStart.x, y: clientY - dragStart.y });
  };

  const handleDragEnd = () => {
    if (!isTop) return;
    if (dragOffset.x > 100) onSwipeRight();
    else if (dragOffset.x < -100) onSwipeLeft();
    setDragStart(null);
    setDragOffset({ x: 0, y: 0 });
    setIsDragging(false);
  };

  const handleImageTap = (e) => {
    if (isDragging || Math.abs(dragOffset.x) > 5) return;
    const rect = e.currentTarget.getBoundingClientRect();
    const x = e.clientX - rect.left;
    if (x > rect.width / 2 && currentImageIndex < person.pictures.length - 1) {
      setCurrentImageIndex(currentImageIndex + 1);
    } else if (x < rect.width / 2 && currentImageIndex > 0) {
      setCurrentImageIndex(currentImageIndex - 1);
    }
  };

  return (
    <div
      className={`absolute w-full h-full rounded-2xl overflow-hidden shadow-2xl ${isTop ? 'z-10 cursor-grab active:cursor-grabbing' : 'z-0'}`}
      style={{
        transform: isTop
          ? `translateX(${dragOffset.x}px) translateY(${dragOffset.y * 0.3}px) rotate(${dragOffset.x * 0.1}deg)`
          : 'scale(0.95) translateY(10px)',
        transition: isDragging ? 'none' : 'transform 0.3s ease-out',
        opacity: isLoading && isTop ? 0.5 : 1,
      }}
      onMouseDown={handleDragStart}
      onMouseMove={handleDragMove}
      onMouseUp={handleDragEnd}
      onMouseLeave={handleDragEnd}
      onTouchStart={handleDragStart}
      onTouchMove={handleDragMove}
      onTouchEnd={handleDragEnd}
      onClick={handleImageTap}
    >
      <img src={person.pictures[currentImageIndex]} alt={person.name} className="w-full h-full object-cover" draggable={false} />

      {person.pictures.length > 1 && (
        <div className="absolute top-2 left-2 right-2 flex gap-1">
          {person.pictures.map((_, idx) => (
            <div key={idx} className={`flex-1 h-1 rounded-full ${idx === currentImageIndex ? 'bg-white' : 'bg-white/40'}`} />
          ))}
        </div>
      )}

      {isTop && dragOffset.x > 30 && (
        <div className="absolute top-16 left-6 border-4 border-green-500 rounded-lg px-4 py-2 -rotate-12 bg-green-500/20" style={{ opacity: Math.min(1, dragOffset.x / 100) }}>
          <span className="text-green-500 text-3xl font-extrabold">LIKE</span>
        </div>
      )}

      {isTop && dragOffset.x < -30 && (
        <div className="absolute top-16 right-6 border-4 border-rose-500 rounded-lg px-4 py-2 rotate-12 bg-rose-500/20" style={{ opacity: Math.min(1, Math.abs(dragOffset.x) / 100) }}>
          <span className="text-rose-500 text-3xl font-extrabold">NOPE</span>
        </div>
      )}

      <div className="absolute bottom-0 left-0 right-0 h-2/5 bg-gradient-to-t from-black/90 via-black/50 to-transparent" />
      
      <div className="absolute bottom-0 left-0 right-0 p-4 text-white">
        <div className="flex items-baseline gap-2">
          <h2 className="text-3xl font-bold">{person.name}</h2>
          <span className="text-2xl font-light">{person.age}</span>
        </div>
        <div className="flex items-center gap-1 mt-1">
          <MapPin size={14} />
          <span className="text-sm opacity-90">{person.location} • {person.distance} km away</span>
        </div>
        {person.bio && <p className="text-sm mt-2 opacity-80">{person.bio}</p>}
      </div>

      {isLoading && isTop && (
        <div className="absolute inset-0 bg-black/30 flex items-center justify-center">
          <Loader2 className="w-10 h-10 text-white animate-spin" />
        </div>
      )}
    </div>
  );
};

// Action Bar
const ActionBar = ({ onNope, onLike, disabled, isLoading }) => (
  <div className="flex justify-center items-center gap-6 py-4">
    <button onClick={onNope} disabled={disabled || isLoading} className="w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center border-2 border-gray-100 hover:scale-110 transition-all disabled:opacity-50 active:scale-95">
      <X size={32} className="text-rose-500" />
    </button>
    <button disabled={disabled || isLoading} className="w-12 h-12 rounded-full bg-white shadow-lg flex items-center justify-center border-2 border-gray-100 hover:scale-110 transition-all disabled:opacity-50">
      <Star size={24} className="text-blue-400" />
    </button>
    <button onClick={onLike} disabled={disabled || isLoading} className="w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center border-2 border-gray-100 hover:scale-110 transition-all disabled:opacity-50 active:scale-95">
      <Heart size={32} className="text-emerald-500" />
    </button>
  </div>
);

// Home Screen
const HomeScreen = ({ user, onNavigate, onLogout }) => {
  const [profiles, setProfiles] = useState([]);
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isLoading, setIsLoading] = useState(true);
  const [isProcessing, setIsProcessing] = useState(false);
  const [stats, setStats] = useState(null);
  const [matchedPerson, setMatchedPerson] = useState(null);
  const [page, setPage] = useState(1);

  const loadProfiles = useCallback(async (pageNum = 1, append = false) => {
    setIsLoading(true);
    try {
      const response = await api.getPeople(pageNum, 20);
      if (append) setProfiles(prev => [...prev, ...response.data]);
      else { setProfiles(response.data); setCurrentIndex(0); }
      setPage(pageNum);
    } catch (error) {
      console.error('Failed to load:', error);
    } finally {
      setIsLoading(false);
    }
  }, []);

  const loadStats = useCallback(async () => {
    try {
      const response = await api.getStats();
      setStats(response.data);
    } catch (error) {
      console.error('Failed to load stats:', error);
    }
  }, []);

  useEffect(() => { loadProfiles(); loadStats(); }, [loadProfiles, loadStats]);

  useEffect(() => {
    if (profiles.length - currentIndex <= 3 && !isLoading) {
      loadProfiles(page + 1, true);
    }
  }, [currentIndex, profiles.length, isLoading, page, loadProfiles]);

  const handleSwipe = async (type) => {
    if (isProcessing || currentIndex >= profiles.length) return;
    setIsProcessing(true);
    const person = profiles[currentIndex];
    try {
      if (type === 'like') {
        const response = await api.likePerson(person.id);
        if (response.data.is_match) setMatchedPerson(person);
      } else {
        await api.dislikePerson(person.id);
      }
      setCurrentIndex(prev => prev + 1);
      loadStats();
    } catch (error) {
      console.error('Swipe failed:', error);
    } finally {
      setIsProcessing(false);
    }
  };

  const remaining = profiles.length - currentIndex;

  return (
    <div className="h-full flex flex-col bg-gray-100">
      {/* Header */}
      <div className="flex justify-between items-center px-4 py-3 bg-white border-b shadow-sm">
        <button onClick={() => onNavigate('profile')} className="p-2 hover:bg-gray-100 rounded-full">
          <User size={24} className="text-gray-400" />
        </button>
        <Flame size={32} className="text-rose-500" />
        <button onClick={() => onNavigate('activity')} className="p-2 relative hover:bg-gray-100 rounded-full">
          <MessageCircle size={24} className="text-gray-400" />
          {stats?.total_matches > 0 && (
            <span className="absolute -top-1 -right-1 bg-rose-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
              {stats.total_matches}
            </span>
          )}
        </button>
      </div>

      {/* Stats */}
      {stats && (
        <div className="flex justify-center gap-4 py-2 px-4 bg-white/80 text-xs">
          <span className="text-gray-600"><span className="font-semibold text-emerald-600">{stats.total_likes_given}</span> likes</span>
          <span className="text-gray-400">•</span>
          <span className="text-gray-600"><span className="font-semibold text-rose-500">{stats.total_dislikes_given}</span> nopes</span>
          <span className="text-gray-400">•</span>
          <span className="text-gray-600"><span className="font-semibold text-purple-500">{stats.total_matches}</span> matches</span>
        </div>
      )}

      {/* Cards */}
      <div className="flex-1 relative px-3 py-4 overflow-hidden">
        {isLoading && profiles.length === 0 ? (
          <div className="h-full flex flex-col items-center justify-center">
            <Loader2 className="w-12 h-12 text-rose-500 animate-spin" />
            <p className="text-gray-500 mt-4">Loading profiles...</p>
          </div>
        ) : remaining > 0 ? (
          <div className="relative w-full h-full max-w-sm mx-auto">
            {profiles.slice(currentIndex, currentIndex + 2).reverse().map((person, idx, arr) => (
              <SwipeCard
                key={person.id}
                person={person}
                isTop={idx === arr.length - 1}
                onSwipeLeft={() => handleSwipe('dislike')}
                onSwipeRight={() => handleSwipe('like')}
                isLoading={isProcessing}
              />
            ))}
          </div>
        ) : (
          <div className="h-full flex flex-col items-center justify-center text-center px-8">
            <Heart size={48} className="text-gray-300 mb-4" />
            <h3 className="text-xl font-semibold text-gray-600">No more profiles!</h3>
            <button
              onClick={() => { loadProfiles(1); loadStats(); }}
              className="mt-6 flex items-center gap-2 bg-rose-500 text-white px-6 py-3 rounded-full font-semibold"
            >
              <RefreshCw size={18} /> Reset
            </button>
          </div>
        )}
      </div>

      <ActionBar onNope={() => handleSwipe('dislike')} onLike={() => handleSwipe('like')} disabled={remaining === 0} isLoading={isProcessing} />
      <MatchModal person={matchedPerson} onClose={() => setMatchedPerson(null)} />
    </div>
  );
};

// Activity Screen (Likes, Dislikes, Matches)
const ActivityScreen = ({ onGoBack }) => {
  const [activeTab, setActiveTab] = useState('matches');
  const [data, setData] = useState([]);
  const [isLoading, setIsLoading] = useState(true);

  const loadData = useCallback(async () => {
    setIsLoading(true);
    try {
      let response;
      if (activeTab === 'matches') response = await api.getMatches();
      else if (activeTab === 'likes') response = await api.getLikedPeople();
      else response = await api.getDislikedPeople();
      setData(response.data);
    } catch (error) {
      console.error('Failed to load:', error);
    } finally {
      setIsLoading(false);
    }
  }, [activeTab]);

  useEffect(() => { loadData(); }, [loadData]);

  const tabs = [
    { id: 'matches', label: 'Matches', icon: Sparkles, color: 'text-purple-500' },
    { id: 'likes', label: 'Likes', icon: Heart, color: 'text-emerald-500' },
    { id: 'dislikes', label: 'Nopes', icon: HeartCrack, color: 'text-rose-500' },
  ];

  return (
    <div className="h-full flex flex-col bg-white">
      {/* Header */}
      <div className="flex items-center px-4 py-3 border-b">
        <button onClick={onGoBack} className="p-2 -ml-2">
          <ChevronLeft size={28} className="text-gray-600" />
        </button>
        <h1 className="flex-1 text-center text-lg font-semibold text-gray-700">Activity</h1>
        <div className="w-10" />
      </div>

      {/* Tabs */}
      <div className="flex border-b">
        {tabs.map(tab => (
          <button
            key={tab.id}
            onClick={() => setActiveTab(tab.id)}
            className={`flex-1 py-3 flex items-center justify-center gap-2 transition ${activeTab === tab.id ? 'border-b-2 border-rose-500' : ''}`}
          >
            <tab.icon size={18} className={activeTab === tab.id ? tab.color : 'text-gray-400'} />
            <span className={`text-sm font-medium ${activeTab === tab.id ? 'text-gray-800' : 'text-gray-400'}`}>
              {tab.label}
            </span>
          </button>
        ))}
      </div>

      {/* Content */}
      <div className="flex-1 overflow-y-auto p-3">
        {isLoading ? (
          <div className="h-full flex items-center justify-center">
            <Loader2 className="w-10 h-10 text-rose-500 animate-spin" />
          </div>
        ) : data.length > 0 ? (
          <div className="grid grid-cols-2 gap-3">
            {data.map(person => (
              <div key={person.id} className="aspect-[3/4] rounded-xl overflow-hidden relative shadow-md group">
                <img src={person.pictures[0]} alt={person.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent" />
                <div className="absolute bottom-0 left-0 right-0 p-3">
                  <p className="text-white font-semibold">{person.name}, {person.age}</p>
                  <div className="flex items-center gap-1 text-white/80 text-xs">
                    <MapPin size={10} />{person.location}
                  </div>
                </div>
                <div className="absolute top-2 right-2">
                  {activeTab === 'matches' && <Sparkles size={18} className="text-purple-400 fill-purple-400" />}
                  {activeTab === 'likes' && <Heart size={18} className="text-emerald-400 fill-emerald-400" />}
                  {activeTab === 'dislikes' && <X size={18} className="text-rose-400" />}
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="h-full flex flex-col items-center justify-center">
            {activeTab === 'matches' && <Sparkles size={48} className="text-gray-300 mb-4" />}
            {activeTab === 'likes' && <Heart size={48} className="text-gray-300 mb-4" />}
            {activeTab === 'dislikes' && <HeartCrack size={48} className="text-gray-300 mb-4" />}
            <p className="text-gray-500">No {activeTab} yet</p>
          </div>
        )}
      </div>
    </div>
  );
};

// Profile Screen
const ProfileScreen = ({ user, onGoBack, onLogout }) => {
  const [stats, setStats] = useState(null);

  useEffect(() => {
    api.getStats().then(res => setStats(res.data)).catch(console.error);
  }, []);

  return (
    <div className="h-full flex flex-col bg-gray-100">
      {/* Header */}
      <div className="bg-gradient-to-b from-rose-500 to-pink-500 pt-8 pb-20 px-4">
        <div className="flex justify-between items-center mb-6">
          <button onClick={onGoBack} className="p-2 text-white">
            <ChevronLeft size={28} />
          </button>
          <h1 className="text-white text-lg font-semibold">Profile</h1>
          <button onClick={onLogout} className="p-2 text-white">
            <LogOut size={24} />
          </button>
        </div>
      </div>

      {/* Profile Card */}
      <div className="px-4 -mt-16">
        <div className="bg-white rounded-2xl shadow-lg p-6">
          <div className="flex flex-col items-center -mt-16">
            <img
              src={user.pictures?.[0] || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop'}
              alt={user.name}
              className="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg"
            />
            <h2 className="text-2xl font-bold text-gray-800 mt-4">{user.name}, {user.age}</h2>
            <div className="flex items-center gap-1 text-gray-500 mt-1">
              <MapPin size={14} />
              <span>{user.location}</span>
            </div>
            <p className="text-gray-600 mt-2 text-center">{user.bio}</p>
          </div>

          {/* Stats */}
          {stats && (
            <div className="grid grid-cols-3 gap-4 mt-6 pt-6 border-t">
              <div className="text-center">
                <p className="text-2xl font-bold text-emerald-500">{stats.total_likes_given}</p>
                <p className="text-xs text-gray-500">Likes</p>
              </div>
              <div className="text-center">
                <p className="text-2xl font-bold text-purple-500">{stats.total_matches}</p>
                <p className="text-xs text-gray-500">Matches</p>
              </div>
              <div className="text-center">
                <p className="text-2xl font-bold text-rose-500">{stats.total_dislikes_given}</p>
                <p className="text-xs text-gray-500">Nopes</p>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* Menu */}
      <div className="p-4 mt-4 space-y-3">
        <button className="w-full bg-white rounded-xl p-4 flex items-center gap-4 shadow-sm">
          <div className="w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center">
            <Heart size={20} className="text-rose-500" />
          </div>
          <span className="font-medium text-gray-700">Liked People</span>
        </button>
        <button className="w-full bg-white rounded-xl p-4 flex items-center gap-4 shadow-sm">
          <div className="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
            <Sparkles size={20} className="text-purple-500" />
          </div>
          <span className="font-medium text-gray-700">My Matches</span>
        </button>
        <button onClick={onLogout} className="w-full bg-white rounded-xl p-4 flex items-center gap-4 shadow-sm">
          <div className="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
            <LogOut size={20} className="text-gray-500" />
          </div>
          <span className="font-medium text-gray-700">Logout</span>
        </button>
      </div>
    </div>
  );
};

// Main App
export default function TinderCloneComplete() {
  // Initialize user from localStorage if available
  const [user, setUser] = useState(() => {
    const savedUser = localStorage.getItem('tinder_user');
    return savedUser ? JSON.parse(savedUser) : null;
  });

  const [currentScreen, setCurrentScreen] = useState('splash');



  const handleLogin = (userData) => {
    localStorage.setItem('tinder_user', JSON.stringify(userData));
    setUser(userData);
    setCurrentScreen('home');
  };

  const handleLogout = async () => {
    await api.logout();
    localStorage.removeItem('tinder_user');
    setUser(null);
    setCurrentScreen('login');
  };

  const renderScreen = () => {
    switch (currentScreen) {
      case 'splash':
        return <SplashScreen onFinish={() => setCurrentScreen(user ? 'home' : 'login')} />;
      case 'login':
        return <LoginScreen onLogin={handleLogin} onSwitchToRegister={() => setCurrentScreen('register')} />;
      case 'register':
        return <RegisterScreen onRegister={handleLogin} onSwitchToLogin={() => setCurrentScreen('login')} />;
      case 'home':
        return <HomeScreen user={user} onNavigate={setCurrentScreen} onLogout={handleLogout} />;
      case 'activity':
        return <ActivityScreen onGoBack={() => setCurrentScreen('home')} />;
      case 'profile':
        return <ProfileScreen user={user} onGoBack={() => setCurrentScreen('home')} onLogout={handleLogout} />;
      default:
        return null;
    }
  };

  return (
    <div className="w-full max-w-md mx-auto h-screen bg-white shadow-2xl overflow-hidden">
      {renderScreen()}
    </div>
  );
}
