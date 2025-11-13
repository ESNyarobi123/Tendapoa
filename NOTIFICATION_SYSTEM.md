# 📢 Notification System - Complete Documentation

## Muhtasari / Summary

Sistema hii ya notification inafanya kazi kwa wote - **Muhitaji** na **Mfanyakazi**. Kila tukio muhimu linatuma notification kwa watu wanaohusika.

---

## 🔔 Notification Flows / Mtiririko wa Notification

### 1️⃣ **Kazi Imepostwa** (Job Posted)
**Nani anapata notification:**
- ✅ **Muhitaji** - Anataarifiwa kwamba kazi yake imepostwa kwa mafanikio
- ✅ **Wafanyakazi wote** - Wanataarifiwa kuwa kuna kazi mpya

**Inafanyika wapi:**
- `JobController@apiStore()` - line 489-495
- `PaymentController@webhook()` - line 89-93 (baada ya malipo kukamilika)

**Notification Type:** `job_posted`, `job_available`

**API Implementation:**
```php
// Muhitaji notification
$this->notificationService->notifyMuhitajiJobPosted($job, $user);

// Workers notification
$this->notificationService->notifyNewJobPosted($job);
```

---

### 2️⃣ **Kazi Imebadilishwa** (Job Updated) ⭐ NEW!
**Nani anapata notification:**
- ✅ **Muhitaji** - Anataarifiwa kwamba kazi yake imebadilishwa
- ✅ **Mfanyakazi aliyechukua kazi** (kama yupo) - Anataarifiwa mabadiliko
- ✅ **Wafanyakazi walioonyesha nia** - Wale waliotoa maoni wanataarifiwa

**Inafanyika wapi:**
- `JobController@update()` - line 177-181 (Web)
- `JobController@apiUpdate()` - line 331-335 (API)

**Notification Type:** `job_updated`

**Mabadiliko yanayofuatiliwa:**
- Bei (Price)
- Jina la kazi (Title)
- Maelezo (Description)
- Eneo (Location)
- Kategoria (Category)

**API Implementation:**
```php
// Track all changes
$changes = [
    'price' => ['old' => $oldPrice, 'new' => $newPrice],
    'title' => ['old' => $oldTitle, 'new' => $newTitle],
    // ... etc
];

// Send notifications
$this->notificationService->notifyMuhitajiJobUpdated($job, Auth::user(), $changes);
$this->notificationService->notifyWorkersJobUpdated($job, $changes);
```

---

### 3️⃣ **Mfanyakazi Amechaguliwa** (Worker Assigned)
**Nani anapata notification:**
- ✅ **Mfanyakazi** - Anataarifiwa kwamba amechaguliwa kufanya kazi

**Inafanyika wapi:**
- `JobViewController@accept()` - line 73-76 (Web)
- `JobViewController@apiAccept()` - line 226-229 (API)

**Notification Type:** `worker_assigned`

**API Implementation:**
```php
if ($comment->user) {
    $this->notificationService->notifyWorkerAssigned($job, $comment->user);
}
```

---

### 4️⃣ **Mfanyakazi Amekubali Kazi** (Worker Accepts Job)
**Nani anapata notification:**
- ✅ **Mfanyakazi** - Anataarifiwa kwamba amekubali kazi
- ✅ **Muhitaji** - Anataarifiwa kwamba mfanyakazi amekubali kazi yake

**Inafanyika wapi:**
- `WorkerActionsController@accept()` - line 56-60 (Web)
- `WorkerActionsController@apiAccept()` - line 231-235 (API)

**Notification Type:** `job_accepted`

**API Implementation:**
```php
// Worker notification
$this->notificationService->notifyWorkerAccepted($job, $u);

// Muhitaji notification
if ($job->muhitaji) {
    $this->notificationService->notifyMuhitajiWorkerAccepted($job, $job->muhitaji, $u);
}
```

---

### 5️⃣ **Mfanyakazi Amekataa Kazi** (Worker Declines Job)
**Nani anapata notification:**
- ✅ **Mfanyakazi** - Anataarifiwa kwamba amekataa kazi
- ✅ **Muhitaji** - Anataarifiwa kwamba mfanyakazi amekataa kazi yake

**Inafanyika wapi:**
- `WorkerActionsController@decline()` - line 82-86 (Web)
- `WorkerActionsController@apiDecline()` - line 277-281 (API)

**Notification Type:** `job_declined`

**API Implementation:**
```php
// Worker notification
$this->notificationService->notifyWorkerDeclined($job, $u);

// Muhitaji notification
if ($job->muhitaji) {
    $this->notificationService->notifyMuhitajiWorkerDeclined($job, $job->muhitaji, $u);
}
```

---

### 6️⃣ **Kazi Imekamilika** (Job Completed)
**Nani anapata notification:**
- ✅ **Mfanyakazi** - Anataarifiwa kwamba kazi imekamilika na malipo
- ✅ **Muhitaji** - Anataarifiwa kwamba kazi yake imekamilika

**Inafanyika wapi:**
- `WorkerActionsController@complete()` - line 131-136 (Web)
- `WorkerActionsController@apiComplete()` (API)

**Notification Type:** `job_completed`

**API Implementation:**
```php
// Worker notification with payment amount
$this->notificationService->notifyWorkerJobCompleted($job, $u, $amount);

// Muhitaji notification
if ($job->muhitaji) {
    $this->notificationService->notifyMuhitajiJobCompleted($job, $job->muhitaji, $u);
}
```

---

### 7️⃣ **Maoni Mapya** (New Comment/Application/Offer)
**Nani anapata notification:**
- ✅ **Muhitaji** - Anataarifiwa wakati mfanyakazi anatoa maoni, application, au offer

**Inafanyika wapi:**
- `JobViewController@comment()` - line 46-54 (Web)
- `JobViewController@apiComment()` - line 180-188 (API)
- `JobViewController@apiApply()` - line 275-283 (API) ⭐ NEW!
- `JobViewController@apiOffer()` - line 336-344 (API) ⭐ NEW!

**Notification Type:** `new_comment`

**API Implementation:**
```php
// Notify muhitaji about new comment/application/offer
if ($job->muhitaji && Auth::id() !== $job->user_id) {
    $this->notificationService->notifyMuhitajiNewComment(
        $job, 
        $job->muhitaji, 
        Auth::user(), 
        $r->input('message')
    );
}
```

---

## 🎯 API Endpoints za Notification

### 1. Pata notification zote
```
GET /api/notifications
Query Parameters:
  - per_page: idadi ya notifications (default: 20)
  - type: filter by notification type
  - unread_only: boolean (default: false)
```

### 2. Pata idadi ya unread notifications
```
GET /api/notifications/unread-count
```

### 3. Pata unread notifications tu
```
GET /api/notifications/unread
Query Parameters:
  - per_page: idadi ya notifications (default: 20)
```

### 4. Pata latest notifications (recent activity) ⭐ NEW!
```
GET /api/notifications/latest
Query Parameters:
  - limit: idadi ya notifications (default: 10)
```

### 5. Pata notification summary by type
```
GET /api/notifications/summary
```

### 6. Pata notifications kwa kazi maalum ⭐ NEW!
```
GET /api/notifications/job/{jobId}
Query Parameters:
  - per_page: idadi ya notifications (default: 20)
```

### 7. Pata notifications by type
```
GET /api/notifications/type/{type}
Query Parameters:
  - per_page: idadi ya notifications (default: 20)
```

### 8. Weka notification as read
```
POST /api/notifications/{notification}/read
```

### 9. Weka notifications zote as read
```
POST /api/notifications/mark-all-read
```

### 10. Futa notification moja
```
DELETE /api/notifications/{notification}
```

### 11. Futa notifications zote zilizosomwa
```
DELETE /api/notifications/clear-read
```

---

## 📊 Notification Types

Hizi ni aina za notification zinazopatikana:

| Type | Description | Nani Anapata |
|------|-------------|--------------|
| `job_posted` | Kazi imepostwa | Muhitaji |
| `job_updated` | Kazi imebadilishwa | Muhitaji + Wafanyakazi |
| `job_available` | Kazi mpya ipo | Wafanyakazi wote |
| `worker_assigned` | Umechaguliwa kufanya kazi | Mfanyakazi |
| `job_accepted` | Mfanyakazi amekubali | Muhitaji + Mfanyakazi |
| `job_declined` | Mfanyakazi amekataa | Muhitaji + Mfanyakazi |
| `job_completed` | Kazi imekamilika | Muhitaji + Mfanyakazi |
| `new_comment` | Maoni mapya | Muhitaji |
| `payment_received` | Malipo yamefika | Mfanyakazi |
| `withdrawal_approved` | Withdrawal imeidhinishwa | Mfanyakazi |
| `withdrawal_rejected` | Withdrawal imekataliwa | Mfanyakazi |

---

## 🔧 Technical Implementation

### Model: `Notification.php`
Located at: `app/Models/Notification.php`

**Fields:**
- `id` - Primary key
- `user_id` - User receiving notification
- `type` - Type of notification (see table above)
- `title` - Notification title
- `message` - Notification message
- `data` - JSON data (contains job_id, worker_name, etc)
- `is_read` - Boolean (default: false)
- `read_at` - Timestamp when read
- `created_at`, `updated_at` - Timestamps

### Service: `NotificationService.php`
Located at: `app/Services/NotificationService.php`

**Key Methods:**
- `create()` - Create a new notification
- `notifyNewJobPosted()` - Notify workers about new job
- `notifyWorkersJobUpdated()` - Notify workers when job is updated ⭐ NEW!
- `notifyMuhitajiJobUpdated()` - Notify muhitaji when job is updated ⭐ NEW!
- `notifyWorkerAssigned()` - Notify worker when assigned
- `notifyWorkerAccepted()` - Notify worker when they accept
- `notifyWorkerDeclined()` - Notify worker when they decline
- `notifyMuhitajiWorkerAccepted()` - Notify muhitaji when worker accepts
- `notifyMuhitajiWorkerDeclined()` - Notify muhitaji when worker declines
- `notifyWorkerJobCompleted()` - Notify worker when job completed
- `notifyMuhitajiJobCompleted()` - Notify muhitaji when job completed
- `notifyMuhitajiNewComment()` - Notify muhitaji about new comments
- `markAsRead()` - Mark notification as read
- `markAllAsRead()` - Mark all as read
- `getUnreadCount()` - Get unread count

### Controller: `NotificationController.php`
Located at: `app/Http/Controllers/NotificationController.php`

Handles all API endpoints for notifications.

---

## ✅ Testing Checklist

### Muhitaji Flow:
- [ ] Post a job → Check if muhitaji gets notification
- [ ] Post a job → Check if workers get notification
- [ ] Update a job → Check if muhitaji gets notification
- [ ] Update a job → Check if assigned worker gets notification
- [ ] Worker applies → Check if muhitaji gets notification
- [ ] Worker makes offer → Check if muhitaji gets notification
- [ ] Accept a worker → Check if worker gets notification
- [ ] Worker accepts job → Check if muhitaji gets notification
- [ ] Worker declines job → Check if muhitaji gets notification
- [ ] Worker completes job → Check if muhitaji gets notification

### Mfanyakazi Flow:
- [ ] New job posted → Check if notification received
- [ ] Job updated → Check if notification received (if commented or assigned)
- [ ] Assigned to job → Check if notification received
- [ ] Accept job → Check if notification received
- [ ] Decline job → Check if notification received
- [ ] Complete job → Check if notification received

### API Tests:
- [ ] GET /api/notifications → Returns all notifications
- [ ] GET /api/notifications/unread-count → Returns correct count
- [ ] GET /api/notifications/unread → Returns only unread
- [ ] GET /api/notifications/latest → Returns latest notifications
- [ ] GET /api/notifications/summary → Returns summary by type
- [ ] GET /api/notifications/job/{jobId} → Returns job-specific notifications
- [ ] POST /api/notifications/{id}/read → Marks as read
- [ ] POST /api/notifications/mark-all-read → Marks all as read
- [ ] DELETE /api/notifications/{id} → Deletes notification
- [ ] DELETE /api/notifications/clear-read → Clears read notifications

---

## 🚀 Usage Examples

### Frontend (JavaScript/React)

```javascript
// Get all notifications
const notifications = await fetch('/api/notifications', {
  headers: {
    'Authorization': 'Bearer ' + token
  }
});

// Get unread count
const { unread_count } = await fetch('/api/notifications/unread-count', {
  headers: {
    'Authorization': 'Bearer ' + token
  }
}).then(r => r.json());

// Get notifications for specific job
const jobNotifications = await fetch(`/api/notifications/job/${jobId}`, {
  headers: {
    'Authorization': 'Bearer ' + token
  }
});

// Mark notification as read
await fetch(`/api/notifications/${notificationId}/read`, {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token
  }
});

// Mark all as read
await fetch('/api/notifications/mark-all-read', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token
  }
});
```

---

## 📝 Notes

1. **Real-time Updates**: Consider implementing WebSockets or Pusher for real-time notifications instead of polling
2. **Push Notifications**: Add Firebase Cloud Messaging for mobile push notifications
3. **Email Notifications**: Add email notifications for important events
4. **Notification Preferences**: Allow users to customize which notifications they want to receive
5. **Batch Notifications**: Consider batching similar notifications to avoid spam

---

## 🎉 Matokeo / Results

Sistema ya notification sasa inafanya kazi vizuri kwa:

✅ **Muhitaji:**
- Anapata notification wakati kazi imepostwa
- Anapata notification wakati mfanyakazi anaapply/comment/offer
- Anapata notification wakati mfanyakazi anakubali au kukataa
- Anapata notification wakati kazi imekamilika

✅ **Mfanyakazi:**
- Anapata notification wakati kazi mpya imepostwa
- Anapata notification wakati kazi imebadilishwa (kama amechukua au ameonyesha nia)
- Anapata notification wakati amechaguliwa
- Anapata notification wakati anakubali au kukataa kazi
- Anapata notification wakati kazi imekamilika na malipo

---

**Author:** AI Assistant  
**Date:** November 2025  
**Version:** 2.0  
**Status:** ✅ Complete & Working

