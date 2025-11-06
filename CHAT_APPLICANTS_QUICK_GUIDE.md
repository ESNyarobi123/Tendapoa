# 🚀 Quick Guide: Chat with Job Applicants

## ✨ What's New?

Sasa **Muhitaji** anaweza:
- ✅ Kuona **WOTE** waliotuma offers kwenye job yake
- ✅ Kuzungumza na **YEYOTE**, hata kama hajachaguliwa
- ✅ Ku-compare offers kabla ya kuchagua

---

## 📡 New API Endpoint

```bash
GET /api/chat/{job_id}/applicants
```

**Example:**
```bash
curl -X GET "http://localhost:8000/api/chat/123/applicants" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## 📝 Response Example

```json
{
  "success": true,
  "job": {
    "id": 123,
    "title": "Mfereji wa Bomba",
    "status": "pending"
  },
  "applicants": [
    {
      "worker": {
        "id": 45,
        "name": "John Mfanyakazi",
        "email": "john@example.com",
        "role": "mfanyakazi",
        "avatar": null
      },
      "comment": {
        "id": 789,
        "body": "Naweza kufanya kazi hii. Nina uzoefu wa miaka 5.",
        "amount": 50000,
        "created_at": "2024-11-06T10:30:00.000000Z"
      },
      "is_accepted": false,
      "can_chat": true,
      "unread_count": 2,
      "last_message_at": "2024-11-06T11:15:00.000000Z"
    }
  ],
  "total": 1
}
```

---

## 🔄 Complete Chat Flow

### **Step 1:** Applicant Sends Offer
```bash
POST /api/jobs/123/comment
{
  "body": "Naweza kufanya kazi hii",
  "amount": 50000
}
```

### **Step 2:** Muhitaji Views Applicants
```bash
GET /api/chat/123/applicants
```

### **Step 3:** Select Worker & Open Chat
```bash
GET /api/chat/123?worker_id=45
```

### **Step 4:** Send Message
```bash
POST /api/chat/123/send
{
  "message": "Je, unaweza kuanza kesho?",
  "receiver_id": 45
}
```

### **Step 5:** Poll for New Messages
```bash
GET /api/chat/123/poll?other_user_id=45&last_id=100
```

### **Step 6:** Accept Worker (Optional)
```bash
POST /api/jobs/123/accept/789
```

---

## 💻 JavaScript Example

```javascript
// 1. Get all applicants for a job
async function getApplicants(jobId, token) {
  const response = await fetch(`/api/chat/${jobId}/applicants`, {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    }
  });
  return await response.json();
}

// 2. Chat with specific worker
async function chatWithWorker(jobId, workerId, token) {
  const response = await fetch(`/api/chat/${jobId}?worker_id=${workerId}`, {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    }
  });
  return await response.json();
}

// 3. Send message to worker
async function sendMessage(jobId, workerId, message, token) {
  const response = await fetch(`/api/chat/${jobId}/send`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      message: message,
      receiver_id: workerId
    })
  });
  return await response.json();
}

// Usage:
const jobId = 123;
const token = 'your_bearer_token';

// Get all applicants
const data = await getApplicants(jobId, token);
console.log(`Total applicants: ${data.total}`);

// Chat with first applicant
const firstWorker = data.applicants[0];
const chat = await chatWithWorker(jobId, firstWorker.worker.id, token);
console.log('Messages:', chat.messages);

// Send a message
await sendMessage(
  jobId, 
  firstWorker.worker.id, 
  'Je, unaweza kuanza kesho?',
  token
);
```

---

## 🎯 Key Features

| Feature | Description |
|---------|-------------|
| **Applicants List** | Wote waliotuma offers/comments |
| **Worker Info** | Name, email, avatar |
| **Offer Details** | Message & amount walizotoa |
| **Unread Count** | Messages zisizosomwa from each worker |
| **Last Message** | Wakati wa last message |
| **Acceptance Status** | Je, worker amechaguliwa? |

---

## 🔐 Permissions

### ✅ Who Can Access?
- **Muhitaji** (job owner) TU

### ❌ Who Cannot?
- Mfanyakazi (workers)
- Other muhitaji (wengine)
- Unauthenticated users

---

## 📱 UI Components Recommendations

### 1. **Applicants List Screen**
```typescript
interface ApplicantCardProps {
  worker: {
    id: number;
    name: string;
    avatar: string | null;
  };
  comment: {
    body: string;
    amount: number;
  };
  isAccepted: boolean;
  unreadCount: number;
  lastMessageAt: string | null;
}
```

### 2. **Chat Header**
```typescript
interface ChatHeaderProps {
  workerName: string;
  offerAmount: number;
  isAccepted: boolean;
  onAccept: () => void;
  onBack: () => void;
}
```

---

## 🧪 Testing Commands

```bash
# 1. Login as Muhitaji
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"muhitaji@example.com","password":"password"}'

# Save token from response
TOKEN="your_token_here"

# 2. Create a job
curl -X POST http://localhost:8000/api/jobs \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Job",
    "description": "Test description",
    "category_id": 1,
    "price": 50000
  }'

# Save job_id from response
JOB_ID=123

# 3. Login as Worker & Apply
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"worker@example.com","password":"password"}'

WORKER_TOKEN="worker_token_here"

curl -X POST http://localhost:8000/api/jobs/$JOB_ID/comment \
  -H "Authorization: Bearer $WORKER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "body": "I can do this job",
    "amount": 45000
  }'

# 4. View Applicants as Muhitaji
curl -X GET http://localhost:8000/api/chat/$JOB_ID/applicants \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"

# 5. Chat with Worker
WORKER_ID=45

curl -X GET "http://localhost:8000/api/chat/$JOB_ID?worker_id=$WORKER_ID" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"

# 6. Send Message
curl -X POST http://localhost:8000/api/chat/$JOB_ID/send \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "message": "Je, unaweza kuanza kesho?",
    "receiver_id": '$WORKER_ID'
  }'
```

---

## 🎨 UI Flow Example

```
User Flow (Muhitaji):

1. Post Job
   ↓
2. Receive Applications/Offers
   ↓
3. View "Applicants" Tab → GET /api/chat/{job}/applicants
   ↓
   [List of Applicants]
   - John: 50,000 TSH (2 unread) [Chat] [Accept]
   - Mary: 45,000 TSH (0 unread) ✓ Accepted [Chat]
   - Peter: 55,000 TSH (1 unread) [Chat] [Accept]
   ↓
4. Click [Chat] on John
   ↓
5. Open Chat → GET /api/chat/{job}?worker_id=45
   ↓
   [Chat Screen]
   Messages with John...
   [Type message...] [Send]
   [✅ Accept John]
   ↓
6. Send Message → POST /api/chat/{job}/send
   ↓
7. Poll for Response → GET /api/chat/{job}/poll
   ↓
8. Accept Worker (if satisfied) → POST /api/jobs/{job}/accept/{comment}
```

---

## 📊 Benefits Summary

| Before | After |
|--------|-------|
| Chat only with accepted worker | Chat with ALL applicants |
| Choose without discussion | Ask questions first |
| One conversation | Multiple conversations |
| Blind decision | Informed decision |

---

## 🔗 All Related Endpoints

```
# Jobs
POST   /api/jobs                     → Create job
GET    /api/jobs/{job}               → View job
GET    /api/jobs/my                  → My jobs

# Comments/Offers
POST   /api/jobs/{job}/comment       → Submit offer (Worker)

# Applicants (NEW)
GET    /api/chat/{job}/applicants    → List all applicants (Muhitaji)

# Chat
GET    /api/chat/{job}               → View chat (with ?worker_id)
POST   /api/chat/{job}/send          → Send message (with receiver_id)
GET    /api/chat/{job}/poll          → Poll messages
GET    /api/chat/unread-count        → Total unread

# Acceptance
POST   /api/jobs/{job}/accept/{comment} → Accept worker
```

---

## ✅ Summary

### What Changed:
1. ✅ Added new endpoint: `GET /api/chat/{job}/applicants`
2. ✅ Muhitaji can now see ALL applicants
3. ✅ Muhitaji can chat with ANY applicant
4. ✅ Shows unread counts per applicant
5. ✅ Shows last message timestamp
6. ✅ Indicates which worker is accepted

### Files Modified:
- `app/Http/Controllers/ChatController.php` - Added `apiJobApplicants()` method
- `routes/api.php` - Added route `/api/chat/{job}/applicants`

### Documentation Created:
- `CHAT_APPLICANTS_API.md` - Full API documentation
- `CHAT_APPLICANTS_QUICK_GUIDE.md` - This file

---

**🎉 Ready to Use!** Test the new API and integrate with your frontend! 🚀

