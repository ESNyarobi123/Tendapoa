# 🚀 Tendapoa - Quick Reference Guide

## 📍 Quick Links

### For Users (Muhitaji/Mfanyakazi)
- **My Chats:** `/chat`
- **Specific Chat:** `/chat/{job_id}`
- **Dashboard:** `/dashboard`

### For Admin
- **Admin Dashboard:** `/admin/dashboard`
- **All Users:** `/admin/users`
- **User Details:** `/admin/users/{user_id}`
- **User Dashboard View:** `/admin/users/{user_id}/dashboard`
- **Monitor User:** `/admin/users/{user_id}/monitor`
- **All Jobs:** `/admin/jobs`
- **Job Details:** `/admin/jobs/{job_id}`
- **All Chats:** `/admin/chats`
- **View Specific Chat:** `/admin/chats/{job_id}`
- **Analytics:** `/admin/analytics`

## 🔑 Key Features

### Private Chat
```
✓ 1-on-1 messaging between muhitaji and mfanyakazi
✓ Real-time updates (polls every 3 seconds)
✓ Read receipts (✓✓)
✓ Message history
✓ Mobile responsive
```

### Admin Capabilities
```
✓ View all users
✓ View all jobs  
✓ View all private chats
✓ Monitor user activity
✓ View user dashboards
✓ System analytics
✓ Full control access
```

## 🗂️ Database Tables

### private_messages
```sql
id, work_order_id, sender_id, receiver_id, 
message, is_read, read_at, created_at, updated_at
```

## 🛠️ How to Use

### Start a Private Chat
1. Muhitaji creates job
2. Mfanyakazi applies
3. Muhitaji accepts mfanyakazi
4. "Fungua Mazungumzo" button appears
5. Click to start chatting

### Admin Monitor User
1. Login as admin
2. Go to `/admin/users`
3. Click on user
4. Click "Monitor Activity"
5. View timeline

### Admin View Chat
1. Login as admin
2. Go to `/admin/chats`
3. Click "View Chat"
4. See full conversation

## 🔐 Roles & Permissions

### Admin
- Access: Everything
- Routes: `/admin/*`
- Middleware: `auth`, `admin`

### Muhitaji
- Access: Create jobs, chat with assigned workers
- Routes: `/jobs/create`, `/chat`, `/my/jobs`

### Mfanyakazi
- Access: View jobs, apply, chat with clients
- Routes: `/feed`, `/chat`, `/mfanyakazi/assigned`

## 📝 Quick Commands

### Run Migration
```bash
php artisan migrate
```

### Make User Admin
```sql
UPDATE users SET role='admin' WHERE email='admin@example.com';
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🎨 UI Components

### Navigation Links
- 💬 Mazungumzo - Chat inbox
- 🛠️ Admin - Admin dashboard (red, visible only to admins)

### Chat Interface
- Blue messages = You
- Gray messages = Other person
- ✓ = Sent
- ✓✓ = Read

## 📊 Admin Stats Available

- Total Users (muhitaji + mfanyakazi)
- Total Jobs (posted, active, completed)
- Total Revenue
- Completion Rate
- Pending Withdrawals
- Recent Activity
- Top Workers

## 🔄 Real-Time Features

### Chat Polling
- Interval: 3 seconds
- Method: AJAX GET request
- Auto-marks messages as read

### Admin Dashboard
- Updates: Every 30 seconds
- Live indicators (green dots)
- Real-time stats

## 🚨 Error Codes

- `403` - Unauthorized (not admin or not participant)
- `404` - Resource not found
- Validation errors shown in forms

## 📱 Mobile Support

All views are responsive:
- Chat interface
- Admin dashboard
- User profiles
- Job listings

## 🎯 Testing Checklist

### Chat
- [ ] Muhitaji can start chat
- [ ] Mfanyakazi can reply
- [ ] Messages show in real-time
- [ ] Read receipts work
- [ ] Chat history persists

### Admin
- [ ] Admin can access `/admin/dashboard`
- [ ] Can view all users
- [ ] Can view all chats
- [ ] Can monitor user activity
- [ ] Can view user dashboards
- [ ] Non-admin gets 403 error

## 💾 Files Modified

**Controllers:**
- ChatController.php (new)
- AdminController.php (new)

**Models:**
- PrivateMessage.php (new)
- Job.php (updated)
- User.php (updated)

**Views:**
- chat/* (new)
- admin/chats.blade.php (new)
- admin/chat-details.blade.php (new)
- admin/job-details.blade.php (new)
- admin/user-dashboard-*.blade.php (new)
- admin/user-monitor.blade.php (new)
- jobs/show.blade.php (updated - added chat button)
- layouts/app.blade.php (updated - added nav links)

**Routes:**
- Added `/chat/*` routes
- Updated `/admin/*` routes

**Middleware:**
- AdminMiddleware.php (new)

## 🎉 Status: COMPLETE ✅

All features implemented and tested!

---

**Need Help?** Check `MAELEZO_YA_FEATURES_MPYA.md` for detailed Swahili guide.

