# 💬 Chat with Job Applicants API

## 📋 Overview

API hii inaruhusu **Muhitaji** (job poster) kupata orodha ya **wote waliotuma offers/applications** kwenye job yake na kuweza **kuchat nao hata kama hawajakubaliwa**.

## 🎯 Problem Solved

### Tatizo la Zamani:
- Muhitaji alikuwa anaweza kuzungumza **TU** na mfanyakazi aliyechaguliwa (accepted worker)
- Hakuweza kuuliza maswali kwa wengine kabla ya kuchagua
- Ilikuwa ngumu ku-compare offers za wafanyakazi different

### Suluhisho Jipya:
- ✅ Muhitaji anaweza kuona **WOTE** waliotuma offers
- ✅ Anaweza **kuchat na yeyote** aliyetuma offer
- ✅ Anaweza kuuliza maswali **kabla ya kuchagua**
- ✅ Better decision making kwa job assignment

---

## 🚀 New API Endpoint

### **GET** `/api/chat/{job}/applicants`

**Description:** Pata orodha ya wafanyakazi wote waliotuma offers/comments kwenye job fulani.

**Auth:** `Bearer Token` (Sanctum)

**Access:** **Muhitaji TU** (job owner)

---

## 📝 Request

### Headers
```http
Authorization: Bearer {your_token}
Accept: application/json
```

### URL Parameters
| Parameter | Type | Description |
|-----------|------|-------------|
| `job` | integer | Job ID (required) |

### Example Request
```bash
curl -X GET "https://yourdomain.com/api/chat/123/applicants" \
  -H "Authorization: Bearer your_token_here" \
  -H "Accept: application/json"
```

---

## ✅ Success Response

**Status Code:** `200 OK`

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
        "avatar": "https://..."
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
    },
    {
      "worker": {
        "id": 67,
        "name": "Mary Worker",
        "email": "mary@example.com",
        "role": "mfanyakazi",
        "avatar": "https://..."
      },
      "comment": {
        "id": 790,
        "body": "Nina vifaa vyote vinavyohitajika.",
        "amount": 45000,
        "created_at": "2024-11-06T09:20:00.000000Z"
      },
      "is_accepted": true,
      "can_chat": true,
      "unread_count": 0,
      "last_message_at": "2024-11-06T12:00:00.000000Z"
    }
  ],
  "total": 2
}
```

### Response Fields Explained

#### `applicants[]` Object:

| Field | Type | Description |
|-------|------|-------------|
| `worker` | object | Taarifa za mfanyakazi |
| `worker.id` | integer | Worker ID (kutumika kwa chat) |
| `worker.name` | string | Jina la mfanyakazi |
| `worker.email` | string | Email ya mfanyakazi |
| `worker.role` | string | Role (`mfanyakazi`) |
| `worker.avatar` | string\|null | Avatar URL |
| `comment` | object | Comment/offer waliyotuma |
| `comment.id` | integer | Comment ID |
| `comment.body` | string | Message yao |
| `comment.amount` | decimal\|null | Bei waliyotoa |
| `comment.created_at` | datetime | Wakati walipotuma |
| `is_accepted` | boolean | Je, wamechaguliwa? |
| `can_chat` | boolean | Je, unaweza kuzungumza nao? (always `true`) |
| `unread_count` | integer | Idadi ya messages zisizosomwa kutoka kwake |
| `last_message_at` | datetime\|null | Last message time |

---

## ❌ Error Responses

### 403 Forbidden
**Cause:** Si wewe mmiliki wa job hii

```json
{
  "error": "Huna ruhusa ya kuona waombaji wa kazi hii.",
  "status": "forbidden"
}
```

### 401 Unauthorized
**Cause:** Token haipo au ni expired

```json
{
  "message": "Unauthenticated."
}
```

### 404 Not Found
**Cause:** Job haipo

```json
{
  "message": "No query results for model [App\\Models\\Job] 123"
}
```

---

## 🔄 How to Use with Existing Chat API

### Step 1: Get Applicants List
```bash
GET /api/chat/{job}/applicants
```

### Step 2: Select a Worker to Chat With
From response, chagua `worker.id` unayetaka kuzungumza naye.

### Step 3: View Chat with That Worker
```bash
GET /api/chat/{job}?worker_id=45
```

### Step 4: Send Message to That Worker
```bash
POST /api/chat/{job}/send
Content-Type: application/json

{
  "message": "Je, unaweza kuanza kesho?",
  "receiver_id": 45
}
```

### Step 5: Poll for New Messages
```bash
GET /api/chat/{job}/poll?other_user_id=45&last_id=100
```

---

## 📱 Complete Usage Flow

### **Scenario:** Muhitaji anataka kuzungumza na wote waliotuma offers

```javascript
// 1. Pata orodha ya applicants
const response = await fetch('/api/chat/123/applicants', {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Accept': 'application/json'
  }
});

const data = await response.json();

// 2. Onyesha list kwa user
data.applicants.forEach(applicant => {
  console.log(`${applicant.worker.name} - ${applicant.comment.amount} TSH`);
  console.log(`Unread: ${applicant.unread_count}`);
  console.log(`Accepted: ${applicant.is_accepted ? 'Yes' : 'No'}`);
});

// 3. User anachagua mfanyakazi wa kuzungumza
const selectedWorkerId = 45;

// 4. Fungua chat na huyo mfanyakazi
const chatResponse = await fetch(`/api/chat/123?worker_id=${selectedWorkerId}`, {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Accept': 'application/json'
  }
});

const chatData = await chatResponse.json();

// 5. Tuma message
await fetch('/api/chat/123/send', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    message: 'Je, unaweza kuanza kesho?',
    receiver_id: selectedWorkerId
  })
});
```

---

## 🎨 UI/UX Recommendations

### **1. Applicants List Screen**
```
┌─────────────────────────────────────────┐
│  📋 Waombaji wa Kazi: "Mfereji wa Bomba" │
├─────────────────────────────────────────┤
│                                         │
│  👤 John Mfanyakazi    🔔 2 unread    │
│     "Naweza kufanya..."                │
│     💰 50,000 TSH      ⏱️ 2 hours ago  │
│     [💬 Chat]  [✅ Chagua]             │
│                                         │
│  ────────────────────────────────────  │
│                                         │
│  👤 Mary Worker       ✓ ACCEPTED      │
│     "Nina vifaa vyote..."              │
│     💰 45,000 TSH     ⏱️ 5 hours ago   │
│     [💬 Chat]                          │
│                                         │
│  ────────────────────────────────────  │
│                                         │
│  👤 Peter Fundi                        │
│     "Napenda sana kazi hii..."         │
│     💰 55,000 TSH     ⏱️ 1 day ago     │
│     [💬 Chat]  [✅ Chagua]             │
│                                         │
└─────────────────────────────────────────┘
```

### **2. Chat Screen with Multiple Workers**
```
┌─────────────────────────────────────────┐
│  ← Back to Applicants                   │
├─────────────────────────────────────────┤
│  Kuzungumza na: John Mfanyakazi        │
│  Offer: 50,000 TSH                     │
│  Status: Not Selected                  │
├─────────────────────────────────────────┤
│                                         │
│  John: Naweza kuanza kesho asubuhi    │
│        10:30 AM                        │
│                                         │
│  You: Je, una vifaa vyote?            │
│       11:15 AM                         │
│                                         │
│  John: Ndio, nina vyote               │
│        11:20 AM                        │
│                                         │
├─────────────────────────────────────────┤
│  [Type message...]            [Send]   │
│                                         │
│  [✅ Chagua Mfanyakazi Huyu]           │
└─────────────────────────────────────────┘
```

---

## 🔐 Security & Permissions

### ✅ Allowed:
- **Muhitaji** anaweza kuona applicants wa job zake tu
- **Muhitaji** anaweza kuzungumza na yeyote aliyetuma comment
- **Mfanyakazi** anaweza kuzungumza na muhitaji kama ametuma comment

### ❌ Not Allowed:
- Mfanyakazi hawezi kuona applicants wengine
- Mtu ambaye si muhitaji hawezi kuona applicants
- Mfanyakazi ambaye hajacomment hawezi kuzungumza

---

## 🧪 Testing Examples

### Test 1: Get Applicants as Muhitaji (Success)
```bash
curl -X GET "http://localhost:8000/api/chat/1/applicants" \
  -H "Authorization: Bearer muhitaji_token" \
  -H "Accept: application/json"
```

**Expected:** `200 OK` with applicants list

---

### Test 2: Get Applicants as Non-Owner (Error)
```bash
curl -X GET "http://localhost:8000/api/chat/1/applicants" \
  -H "Authorization: Bearer other_user_token" \
  -H "Accept: application/json"
```

**Expected:** `403 Forbidden`

---

### Test 3: Chat with Specific Applicant
```bash
# Step 1: Get applicants
GET /api/chat/1/applicants

# Step 2: Choose worker_id from response (e.g., 45)
GET /api/chat/1?worker_id=45

# Step 3: Send message
POST /api/chat/1/send
{
  "message": "Hello!",
  "receiver_id": 45
}
```

**Expected:** Chat opens successfully

---

## 📊 Benefits

| Before | After |
|--------|-------|
| ❌ Chat only with accepted worker | ✅ Chat with ALL applicants |
| ❌ Choose blindly | ✅ Ask questions first |
| ❌ Single conversation | ✅ Multiple conversations |
| ❌ No comparison | ✅ Compare workers easily |

---

## 🔗 Related API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/chat/{job}/applicants` | GET | **NEW:** Pata applicants list |
| `/api/chat/{job}` | GET | Fungua chat (with `?worker_id=X`) |
| `/api/chat/{job}/send` | POST | Tuma message (with `receiver_id`) |
| `/api/chat/{job}/poll` | GET | Check new messages |
| `/api/chat/unread-count` | GET | Total unread messages |

---

## 💡 Pro Tips

1. **Sort by Unread Count:** Weka wale wenye unread messages juu
2. **Highlight Accepted Worker:** Onyesha clearly yule aliyechaguliwa
3. **Show Last Message Time:** Waweza ku-sort by most recent
4. **Badge Notifications:** Onyesha unread count kwa kila applicant
5. **Quick Actions:** Weka "Chat" na "Accept" buttons pamoja

---

## 🐛 Troubleshooting

### Q: Applicants list ni empty?
**A:** Check kama job ina comments. Wafanyakazi wanapaswa kutuma comment/offer first.

### Q: Error "Huna ruhusa"?
**A:** Hakikisha wewe ni mmiliki wa job. Token iwe ya muhitaji aliyepost job.

### Q: Can't chat with applicant?
**A:** Hakikisha applicant ametuma comment kwenye job. Use `apiJobApplicants` endpoint to verify.

### Q: Unread count sio correct?
**A:** Messages zinawekwa "read" automatically wakati unafungua chat. Use polling endpoint to keep count updated.

---

## 📚 Summary

### New Features Added:
1. ✅ **GET** `/api/chat/{job}/applicants` - Pata orodha ya applicants
2. ✅ Unread count kwa kila applicant
3. ✅ Last message timestamp
4. ✅ Acceptance status indicator
5. ✅ Chat capability flag

### Existing Features Enhanced:
1. ✅ Chat system now supports `worker_id` parameter
2. ✅ Send message supports `receiver_id` parameter
3. ✅ Poll supports `other_user_id` parameter

### Full Chat Flow:
```
1. POST /api/jobs/{id}/comment → Apply/Offer
2. GET  /api/chat/{job}/applicants → See all applicants
3. GET  /api/chat/{job}?worker_id=X → Open chat
4. POST /api/chat/{job}/send → Send message
5. GET  /api/chat/{job}/poll → Check new messages
6. POST /api/jobs/{job}/accept/{comment} → Accept worker
```

---

**🎉 Happy Coding!** Sasa muhitaji anaweza kuzungumza na wafanyakazi wote kabla ya kuchagua! 🚀

