# 🧪 Notification Flow Testing Guide

## Jinsi ya Kutest Notification System

### Prerequisites
1. Uwe na user account ya **Muhitaji**
2. Uwe na user account ya **Mfanyakazi**
3. Uwe na Bearer token kwa wote wawili

---

## Test Flow 1: Job Posting Notification

### Step 1: Muhitaji Posts a Job
```bash
POST /api/jobs
Headers: Authorization: Bearer {muhitaji_token}
Body:
{
  "title": "Kujenga nyumba",
  "description": "Nahitaji mfanyakazi wa kujenga chumba kimoja",
  "category_id": 1,
  "price": 50000,
  "lat": -6.7924,
  "lng": 39.2083,
  "address_text": "Dar es Salaam, Tanzania"
}
```

**Expected Result:**
- ✅ Muhitaji anapata notification: "Kazi Yako Imepostwa! 📢"
- ✅ Wafanyakazi wote wanapata notification: "Kazi Mpya Imepostwa! 🎉"

### Step 2: Check Muhitaji Notifications
```bash
GET /api/notifications
Headers: Authorization: Bearer {muhitaji_token}
```

**Expected Response:**
```json
{
  "success": true,
  "notifications": [
    {
      "id": 1,
      "type": "job_posted",
      "title": "Kazi Yako Imepostwa! 📢",
      "message": "Kazi 'Kujenga nyumba' imepostwa kwa mafanikio. Wafanyakazi wataanza kutoa maoni.",
      "is_read": false,
      "created_at": "2025-11-13T10:00:00.000000Z"
    }
  ]
}
```

### Step 3: Check Mfanyakazi Notifications
```bash
GET /api/notifications
Headers: Authorization: Bearer {mfanyakazi_token}
```

**Expected Response:**
```json
{
  "success": true,
  "notifications": [
    {
      "id": 2,
      "type": "job_available",
      "title": "Kazi Mpya Imepostwa! 🎉",
      "message": "Kazi mpya: Kujenga nyumba. Bei: TZS 50,000",
      "is_read": false,
      "created_at": "2025-11-13T10:00:00.000000Z"
    }
  ]
}
```

---

## Test Flow 2: Job Update Notification ⭐ NEW!

### Step 1: Muhitaji Updates Job
```bash
PUT /api/jobs/{jobId}
Headers: Authorization: Bearer {muhitaji_token}
Body:
{
  "title": "Kujenga nyumba kubwa",
  "description": "Nahitaji mfanyakazi wa kujenga vyumba viwili",
  "category_id": 1,
  "price": 75000,
  "lat": -6.7924,
  "lng": 39.2083,
  "address_text": "Dar es Salaam, Tanzania"
}
```

**Expected Result:**
- ✅ Muhitaji anapata notification: "Kazi Imebadilishwa! ✏️"
- ✅ Mfanyakazi (kama amechukua au ameonyesha nia) anapata notification

### Step 2: Check Notifications
```bash
GET /api/notifications/job/{jobId}
Headers: Authorization: Bearer {token}
```

**Expected Response:**
```json
{
  "success": true,
  "job_id": 1,
  "notifications": [
    {
      "id": 3,
      "type": "job_updated",
      "title": "Kazi Imebadilishwa! ✏️",
      "message": "Kazi 'Kujenga nyumba kubwa' imebadilishwa kwa mafanikio.",
      "data": {
        "job_id": 1,
        "changes": {
          "title": {
            "old": "Kujenga nyumba",
            "new": "Kujenga nyumba kubwa"
          },
          "price": {
            "old": 50000,
            "new": 75000
          }
        }
      }
    }
  ]
}
```

---

## Test Flow 3: Worker Application & Assignment

### Step 1: Mfanyakazi Applies for Job
```bash
POST /api/jobs/{jobId}/apply
Headers: Authorization: Bearer {mfanyakazi_token}
Body:
{
  "message": "Ninajua kujenga vizuri. Nina uzoefu wa miaka 5.",
  "bid_amount": 45000
}
```

**Expected Result:**
- ✅ Muhitaji anapata notification: "Maoni Mapya! 💬"

### Step 2: Muhitaji Accepts Worker
```bash
POST /api/jobs/{jobId}/accept/{commentId}
Headers: Authorization: Bearer {muhitaji_token}
```

**Expected Result:**
- ✅ Mfanyakazi anapata notification: "Umechaguliwa Kufanya Kazi! 🎯"

### Step 3: Check Unread Count
```bash
GET /api/notifications/unread-count
Headers: Authorization: Bearer {mfanyakazi_token}
```

**Expected Response:**
```json
{
  "success": true,
  "unread_count": 1
}
```

---

## Test Flow 4: Worker Accept/Decline

### Step 1: Mfanyakazi Accepts Job
```bash
POST /api/worker/jobs/{jobId}/accept
Headers: Authorization: Bearer {mfanyakazi_token}
```

**Expected Result:**
- ✅ Mfanyakazi anapata notification: "Umekubali Kazi! ✅"
- ✅ Muhitaji anapata notification: "Mfanyakazi Amekubali! ✅"

### Step 2: Check Latest Notifications
```bash
GET /api/notifications/latest?limit=5
Headers: Authorization: Bearer {muhitaji_token}
```

**Expected Response:**
```json
{
  "success": true,
  "notifications": [
    {
      "type": "job_accepted",
      "title": "Mfanyakazi Amekubali! ✅",
      "message": "John Doe amekubali kufanya kazi 'Kujenga nyumba'",
      "created_at": "2025-11-13T10:30:00.000000Z"
    }
  ],
  "unread_count": 1
}
```

---

## Test Flow 5: Job Completion

### Step 1: Mfanyakazi Completes Job
```bash
POST /api/worker/jobs/{jobId}/complete
Headers: Authorization: Bearer {mfanyakazi_token}
Body:
{
  "code": "123456"
}
```

**Expected Result:**
- ✅ Mfanyakazi anapata notification: "Kazi Imekamilika! 🎊"
- ✅ Muhitaji anapata notification: "Kazi Imekamilika! 🎊"

### Step 2: Check Summary
```bash
GET /api/notifications/summary
Headers: Authorization: Bearer {token}
```

**Expected Response:**
```json
{
  "success": true,
  "summary": [
    {
      "type": "job_posted",
      "count": 1,
      "unread_count": 0
    },
    {
      "type": "job_updated",
      "count": 1,
      "unread_count": 0
    },
    {
      "type": "job_accepted",
      "count": 2,
      "unread_count": 0
    },
    {
      "type": "job_completed",
      "count": 2,
      "unread_count": 1
    }
  ],
  "total_unread": 1
}
```

---

## Test Flow 6: Mark as Read & Clear

### Step 1: Mark Single Notification as Read
```bash
POST /api/notifications/{notificationId}/read
Headers: Authorization: Bearer {token}
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Notification imesomwa.",
  "unread_count": 0
}
```

### Step 2: Mark All as Read
```bash
POST /api/notifications/mark-all-read
Headers: Authorization: Bearer {token}
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Notifications 5 zimesomwa.",
  "marked_count": 5,
  "unread_count": 0
}
```

### Step 3: Clear Read Notifications
```bash
DELETE /api/notifications/clear-read
Headers: Authorization: Bearer {token}
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Notifications 5 zimefutwa.",
  "deleted_count": 5
}
```

---

## Quick Test Commands (Using curl)

```bash
# Set your tokens
MUHITAJI_TOKEN="your_muhitaji_bearer_token"
MFANYAKAZI_TOKEN="your_mfanyakazi_bearer_token"
API_URL="http://localhost:8000/api"

# 1. Get all notifications
curl -X GET "${API_URL}/notifications" \
  -H "Authorization: Bearer ${MUHITAJI_TOKEN}"

# 2. Get unread count
curl -X GET "${API_URL}/notifications/unread-count" \
  -H "Authorization: Bearer ${MUHITAJI_TOKEN}"

# 3. Get latest notifications
curl -X GET "${API_URL}/notifications/latest?limit=5" \
  -H "Authorization: Bearer ${MUHITAJI_TOKEN}"

# 4. Get notifications for specific job
curl -X GET "${API_URL}/notifications/job/1" \
  -H "Authorization: Bearer ${MUHITAJI_TOKEN}"

# 5. Get summary
curl -X GET "${API_URL}/notifications/summary" \
  -H "Authorization: Bearer ${MUHITAJI_TOKEN}"

# 6. Mark as read
curl -X POST "${API_URL}/notifications/1/read" \
  -H "Authorization: Bearer ${MUHITAJI_TOKEN}"

# 7. Mark all as read
curl -X POST "${API_URL}/notifications/mark-all-read" \
  -H "Authorization: Bearer ${MUHITAJI_TOKEN}"

# 8. Delete notification
curl -X DELETE "${API_URL}/notifications/1" \
  -H "Authorization: Bearer ${MUHITAJI_TOKEN}"

# 9. Clear read notifications
curl -X DELETE "${API_URL}/notifications/clear-read" \
  -H "Authorization: Bearer ${MUHITAJI_TOKEN}"
```

---

## Expected Behavior Checklist

### ✅ Muhitaji Should Receive Notifications For:
- [x] When they post a job
- [x] When they update a job
- [x] When a worker applies to their job
- [x] When a worker makes an offer
- [x] When a worker comments on their job
- [x] When a worker accepts their job offer
- [x] When a worker declines their job offer
- [x] When a worker completes their job

### ✅ Mfanyakazi Should Receive Notifications For:
- [x] When a new job is posted (all workers)
- [x] When a job they're interested in is updated
- [x] When they're assigned to a job
- [x] When they accept a job
- [x] When they decline a job
- [x] When they complete a job

---

## Common Issues & Solutions

### Issue 1: Notifications not appearing
**Solution:** Check if notification service is properly injected in controllers

### Issue 2: Wrong user receiving notification
**Solution:** Verify user_id in notification creation

### Issue 3: Duplicate notifications
**Solution:** Check if notification methods are called multiple times

### Issue 4: JSON extraction not working for job_id
**Solution:** Ensure data field is properly cast as JSON in model

---

## Performance Tips

1. **Pagination:** Always use pagination for notification lists
2. **Indexing:** Add database index on `user_id` and `is_read` columns
3. **Caching:** Cache unread count to reduce database queries
4. **Cleanup:** Regularly delete old read notifications

```php
// Add to scheduler (app/Console/Kernel.php)
$schedule->call(function () {
    app(NotificationService::class)->deleteOldNotifications(30);
})->daily();
```

---

## Database Queries to Verify

```sql
-- Check all notifications for a user
SELECT * FROM notifications WHERE user_id = 1 ORDER BY created_at DESC;

-- Check unread notifications
SELECT * FROM notifications WHERE user_id = 1 AND is_read = 0;

-- Check notifications by type
SELECT type, COUNT(*) as count FROM notifications WHERE user_id = 1 GROUP BY type;

-- Check notifications for specific job
SELECT * FROM notifications WHERE JSON_EXTRACT(data, '$.job_id') = 1;
```

---

**Happy Testing! 🚀**

