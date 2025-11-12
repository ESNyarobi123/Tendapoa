# 🔔 TendaPoa Notifications API Documentation

## Overview

The Notifications API provides a complete notification system for both **Mfanyakazi** (Workers) and **Muhitaji** (Job Owners) users. Notifications are automatically created when specific events occur in the system.

---

## 📱 Notification Types

### For Mfanyakazi (Workers)

| Type | Title | When Triggered | Data Included |
|------|-------|----------------|---------------|
| `job_available` | Kazi Mpya Imepostwa! 🎉 | When a new job is posted | job_id, job_title, job_price, category |
| `worker_assigned` | Umechaguliwa Kufanya Kazi! 🎯 | When assigned to a job | job_id, job_title, muhitaji_name |
| `job_accepted` | Umekubali Kazi! ✅ | When they accept a job | job_id, job_title |
| `job_declined` | Umekataa Kazi ❌ | When they decline a job | job_id, job_title |
| `job_completed` | Kazi Imekamilika! 🎊 | When job is completed | job_id, job_title, amount |

### For Muhitaji (Job Owners)

| Type | Title | When Triggered | Data Included |
|------|-------|----------------|---------------|
| `job_posted` | Kazi Yako Imepostwa! 📢 | When their job is posted | job_id, job_title |
| `job_accepted` | Mfanyakazi Amekubali! ✅ | When worker accepts their job | job_id, job_title, worker_id, worker_name |
| `job_declined` | Mfanyakazi Amekataa ❌ | When worker declines their job | job_id, job_title, worker_id, worker_name |
| `new_comment` | Maoni Mapya! 💬 | When someone comments on their job | job_id, job_title, commenter_id, commenter_name, comment_preview |
| `job_completed` | Kazi Imekamilika! 🎊 | When job is completed | job_id, job_title, worker_id, worker_name |

---

## 🔌 API Endpoints

### Base URL
```
http://localhost:8000/api/notifications
```

### Authentication
All endpoints require Bearer token authentication:
```
Authorization: Bearer {your_sanctum_token}
```

---

## 📋 Endpoints Details

### 1. Get All Notifications
```http
GET /api/notifications
```

**Query Parameters:**
- `per_page` (optional, default: 20) - Number of notifications per page
- `type` (optional) - Filter by notification type
- `unread_only` (optional, default: false) - Show only unread notifications

**Response:**
```json
{
  "success": true,
  "notifications": [
    {
      "id": 1,
      "user_id": 5,
      "type": "job_available",
      "title": "Kazi Mpya Imepostwa! 🎉",
      "message": "Kazi mpya: Uchakataji wa mazao. Bei: TZS 50,000",
      "data": {
        "job_id": 10,
        "job_title": "Uchakataji wa mazao",
        "job_price": 50000,
        "category": "Agriculture"
      },
      "is_read": false,
      "read_at": null,
      "created_at": "2025-11-12T10:30:00.000000Z",
      "updated_at": "2025-11-12T10:30:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 20,
    "total": 45,
    "has_more": true
  },
  "unread_count": 12,
  "status": "success"
}
```

---

### 2. Get Unread Count
```http
GET /api/notifications/unread-count
```

**Response:**
```json
{
  "success": true,
  "unread_count": 12,
  "status": "success"
}
```

---

### 3. Get Unread Notifications
```http
GET /api/notifications/unread
```

**Query Parameters:**
- `per_page` (optional) - Number of notifications per page

**Response:**
```json
{
  "success": true,
  "notifications": [...],
  "pagination": {...},
  "status": "success"
}
```

---

### 4. Get Notifications Summary
```http
GET /api/notifications/summary
```

**Response:**
```json
{
  "success": true,
  "summary": [
    {
      "type": "job_available",
      "count": 25,
      "unread_count": 8
    },
    {
      "type": "worker_assigned",
      "count": 10,
      "unread_count": 2
    }
  ],
  "total_unread": 12,
  "status": "success"
}
```

---

### 5. Get Notifications by Type
```http
GET /api/notifications/type/{type}
```

**Path Parameters:**
- `type` - Notification type (job_available, worker_assigned, etc.)

**Query Parameters:**
- `per_page` (optional) - Number of notifications per page

**Response:**
```json
{
  "success": true,
  "type": "job_available",
  "notifications": [...],
  "pagination": {...},
  "status": "success"
}
```

---

### 6. Mark Notification as Read
```http
POST /api/notifications/{notification_id}/read
```

**Path Parameters:**
- `notification_id` - ID of the notification to mark as read

**Response:**
```json
{
  "success": true,
  "message": "Notification imesomwa.",
  "notification": {...},
  "unread_count": 11,
  "status": "success"
}
```

---

### 7. Mark All Notifications as Read
```http
POST /api/notifications/mark-all-read
```

**Response:**
```json
{
  "success": true,
  "message": "Notifications 11 zimesomwa.",
  "marked_count": 11,
  "unread_count": 0,
  "status": "success"
}
```

---

### 8. Delete Notification
```http
DELETE /api/notifications/{notification_id}
```

**Path Parameters:**
- `notification_id` - ID of the notification to delete

**Response:**
```json
{
  "success": true,
  "message": "Notification imefutwa.",
  "unread_count": 10,
  "status": "success"
}
```

---

### 9. Clear All Read Notifications
```http
DELETE /api/notifications/clear-read
```

**Response:**
```json
{
  "success": true,
  "message": "Notifications 33 zimefutwa.",
  "deleted_count": 33,
  "status": "success"
}
```

---

## 🎯 When Notifications Are Created

### Automatic Notifications

#### 1. Job Posted (Payment Completed)
**Trigger:** When job payment is completed successfully
- ✅ Muhitaji receives: `job_posted` notification
- ✅ All workers receive: `job_available` notification

#### 2. Worker Assigned to Job
**Trigger:** When muhitaji selects a worker
- ✅ Worker receives: `worker_assigned` notification

#### 3. Worker Accepts Job
**Trigger:** When worker accepts assigned job
- ✅ Worker receives: `job_accepted` notification
- ✅ Muhitaji receives: `job_accepted` notification

#### 4. Worker Declines Job
**Trigger:** When worker declines assigned job
- ✅ Worker receives: `job_declined` notification
- ✅ Muhitaji receives: `job_declined` notification

#### 5. New Comment on Job
**Trigger:** When someone comments on a job
- ✅ Muhitaji receives: `new_comment` notification (if commenter is not muhitaji)

#### 6. Job Completed
**Trigger:** When worker completes job with code
- ✅ Worker receives: `job_completed` notification with payment amount
- ✅ Muhitaji receives: `job_completed` notification

---

## 💻 Example Usage in Mobile/Web App

### Polling for Notifications (Recommended)

```javascript
// Poll every 30 seconds
setInterval(async () => {
  const response = await fetch('/api/notifications/unread-count', {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  const data = await response.json();
  updateNotificationBadge(data.unread_count);
}, 30000);
```

### Fetching Notifications

```javascript
async function fetchNotifications(page = 1) {
  const response = await fetch(
    `/api/notifications?per_page=20&unread_only=false&page=${page}`,
    {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    }
  );
  const data = await response.json();
  return data;
}
```

### Mark as Read

```javascript
async function markAsRead(notificationId) {
  const response = await fetch(
    `/api/notifications/${notificationId}/read`,
    {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`
      }
    }
  );
  const data = await response.json();
  return data;
}
```

---

## 🔧 Database Schema

```sql
CREATE TABLE notifications (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read),
    INDEX idx_type (type),
    INDEX idx_created (created_at)
);
```

---

## 🎨 UI/UX Recommendations

### Notification Badge
- Show unread count on notification icon
- Update in real-time using polling (every 30s)
- Red badge for new notifications

### Notification List
- Show newest first
- Different styling for unread vs read
- Icons/emojis for each type
- Click to mark as read

### Actions
- Swipe to delete (mobile)
- "Mark all as read" button
- "Clear read" button for cleanup
- Click notification to navigate to related job

---

## 📊 Notification Data Structure

Each notification contains:

```typescript
interface Notification {
  id: number;
  user_id: number;
  type: string;  // notification type
  title: string;  // short title
  message: string;  // descriptive message
  data: {  // type-specific data
    job_id?: number;
    job_title?: string;
    job_price?: number;
    worker_id?: number;
    worker_name?: string;
    amount?: number;
    // ... other fields depending on type
  };
  is_read: boolean;
  read_at: string | null;
  created_at: string;
  updated_at: string;
}
```

---

## 🚀 Setup Instructions

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Import Postman Collection
- Import `NOTIFICATIONS_API_POSTMAN.json` into Postman
- Set `base_url` variable (e.g., http://localhost:8000)
- Set `auth_token` variable with your Sanctum token

### 3. Test Endpoints
- Login to get auth token
- Test each endpoint with different scenarios
- Check database for created notifications

---

## 🎯 Best Practices

1. **Polling Interval:** Use 30-60 seconds for production
2. **Cleanup:** Regularly delete old read notifications (30+ days)
3. **Performance:** Index on user_id and is_read columns
4. **Security:** Always validate notification ownership
5. **UX:** Show toast/popup for real-time notifications

---

## 📝 Notes

- Notifications are user-specific (can't see others' notifications)
- Soft delete not used - permanent deletion only
- Notifications created automatically by system events
- No manual notification creation endpoint (security)
- All notifications are in Swahili language

---

## 🆘 Troubleshooting

### Notifications not appearing?
- Check if NotificationService is injected in controllers
- Verify database migration ran successfully
- Check user_id matches authenticated user
- Look at Laravel logs for errors

### Too many notifications?
- Use pagination (per_page parameter)
- Clear old read notifications periodically
- Filter by type to reduce noise

### Performance issues?
- Add database indexes (see schema above)
- Use caching for unread counts
- Limit notifications per user (e.g., max 1000)

---

## 📞 Support

For questions or issues, contact the development team or check the main API documentation.

**Version:** 1.0.0  
**Last Updated:** November 12, 2025

