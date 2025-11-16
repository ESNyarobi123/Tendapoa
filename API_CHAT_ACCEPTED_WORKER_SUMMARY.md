# API Chat Endpoints - Mfanyakazi Aliye Chaguliwa (Accepted Worker) Summary

## ✅ Hakiki ya API Endpoints

Nimechunguza `routes/api.php` na `ChatController.php` na **zote za API endpoints zipo tayari** na **zinasaidia mfanyakazi aliye chaguliwa** kutuma na kupokea chat messages.

## 📋 API Endpoints Zote za Chat

### 1. ✅ `GET /api/chat` - List Conversations
**Method**: `apiIndex()`
**Authorization**: ✅ Inasaidia accepted workers
**Maelezo**: 
- Inalist conversations zote ambazo user ana messages
- Inaonyesha jobs ambazo user ni accepted_worker_id
- Hakuna authorization check maalum kwa sababu inategemea messages zilizopo

**Example Response**:
```json
{
  "conversations": [
    {
      "job": {
        "id": 1,
        "title": "Kazi ya Plumbing",
        "status": "assigned",
        "price": 50000
      },
      "other_user": {
        "id": 2,
        "name": "John Muhitaji",
        "role": "muhitaji"
      },
      "last_message_at": "2025-01-15 10:30:00",
      "unread_count": 2
    }
  ],
  "status": "success"
}
```

---

### 2. ✅ `GET /api/chat/{job}` - View Chat Messages
**Method**: `apiShow()`
**Authorization**: ✅ **Inacheki accepted_worker_id** (Line 318)
**Maelezo**: 
- Mfanyakazi aliye chaguliwa anaweza kuona messages
- Inacheki: `$isAcceptedWorker = $job->accepted_worker_id === $user->id`
- Hana haja ya kutuma comment kwanza

**Authorization Check**:
```php
// Check if user is accepted worker
$isAcceptedWorker = $job->accepted_worker_id === $user->id;

// Allow access if: muhitaji, commented mfanyakazi, or accepted worker
if (!$isMuhitaji && !$hasCommented && !$isAcceptedWorker) {
    return response()->json([
        'error' => 'Huna ruhusa ya kuona mazungumzo haya. Umepaswa kuchaguliwa na muhitaji au kutuma comment kwanza.',
        'status' => 'forbidden'
    ], 403);
}
```

**Example Request**:
```bash
GET /api/chat/1
Authorization: Bearer {token}
```

**Example Response**:
```json
{
  "job": {
    "id": 1,
    "title": "Kazi ya Plumbing",
    "status": "assigned",
    "price": 50000
  },
  "other_user": {
    "id": 2,
    "name": "John Muhitaji",
    "role": "muhitaji"
  },
  "messages": [
    {
      "id": 1,
      "message": "Habari mfanyakazi",
      "is_read": true,
      "created_at": "2025-01-15 10:00:00",
      "sender": {
        "id": 2,
        "name": "John Muhitaji",
        "role": "muhitaji"
      }
    }
  ],
  "status": "success"
}
```

---

### 3. ✅ `POST /api/chat/{job}/send` - Send Message
**Method**: `apiSend()`
**Authorization**: ✅ **Inacheki accepted_worker_id** (Line 406)
**Maelezo**: 
- **Mfanyakazi aliye chaguliwa anaweza kutuma ujumbe** kwa muhitaji
- Inacheki: `$isAcceptedWorker = $job->accepted_worker_id === $user->id`
- Mfanyakazi anaweza kutuma bila kuwa ame-comment kwanza

**Authorization Check**:
```php
// Check if user is accepted worker
$isAcceptedWorker = $job->accepted_worker_id === $user->id;

// Allow sending if: muhitaji, commented mfanyakazi, or accepted worker
if (!$isMuhitaji && !$hasCommented && !$isAcceptedWorker) {
    return response()->json([
        'error' => 'Huna ruhusa ya kutuma ujumbe. Umepaswa kuchaguliwa na muhitaji au kutuma comment kwanza.',
        'status' => 'forbidden'
    ], 403);
}

// Mfanyakazi always sends to muhitaji
if (!$isMuhitaji) {
    $receiverId = $job->user_id;
}
```

**Example Request**:
```bash
POST /api/chat/1/send
Authorization: Bearer {token}
Content-Type: application/json

{
  "message": "Nimekubali kazi. Nitaanza kesho asubuhi."
}
```

**Example Response**:
```json
{
  "success": true,
  "message": {
    "id": 5,
    "message": "Nimekubali kazi. Nitaanza kesho asubuhi.",
    "created_at": "2025-01-15 11:00:00",
    "sender": {
      "id": 3,
      "name": "Worker Name",
      "role": "mfanyakazi"
    }
  },
  "status": "success"
}
```

---

### 4. ✅ `GET /api/chat/{job}/poll` - Poll for New Messages
**Method**: `apiPoll()`
**Authorization**: ✅ **Inacheki accepted_worker_id** (Line 469)
**Maelezo**: 
- Mfanyakazi aliye chaguliwa anaweza poll kwa messages mpya
- Inacheki: `$isAcceptedWorker = $job->accepted_worker_id === $user->id`
- Useful kwa real-time chat updates

**Authorization Check**:
```php
// Check if user is muhitaji, has commented, or is accepted worker
$isMuhitaji = $job->user_id === $user->id;
$hasCommented = $job->comments()->where('user_id', $user->id)->exists();
$isAcceptedWorker = $job->accepted_worker_id === $user->id;

if (!$isMuhitaji && !$hasCommented && !$isAcceptedWorker) {
    return response()->json([
        'error' => 'Huna ruhusa. Umepaswa kuchaguliwa na muhitaji au kutuma comment kwanza.',
        'status' => 'forbidden'
    ], 403);
}
```

**Example Request**:
```bash
GET /api/chat/1/poll?last_id=10
Authorization: Bearer {token}
```

**Example Response**:
```json
{
  "messages": [
    {
      "id": 11,
      "message": "Ujumbe mpya kutoka muhitaji",
      "created_at": "2025-01-15 11:05:00",
      "sender": {
        "id": 2,
        "name": "John Muhitaji",
        "role": "muhitaji"
      }
    }
  ],
  "count": 1,
  "status": "success"
}
```

---

### 5. ✅ `GET /api/chat/unread-count` - Get Unread Count
**Method**: `apiUnreadCount()`
**Authorization**: ✅ Inasaidia accepted workers
**Maelezo**: 
- Inarudi unread message count kwa user yeyote
- Inafanya kazi kwa mfanyakazi aliye chaguliwa

**Example Request**:
```bash
GET /api/chat/unread-count
Authorization: Bearer {token}
```

**Example Response**:
```json
{
  "count": 5,
  "status": "success"
}
```

---

### 6. ✅ `GET /api/chat/{job}/applicants` - Get Job Applicants
**Method**: `apiJobApplicants()`
**Authorization**: ❌ **Ni kwa muhitaji tu** (Job owner only)
**Maelezo**: 
- Endpoint hii ni kwa muhitaji tu
- Inaonyesha applicants wote walioomba kazi
- Mfanyakazi hana haja ya endpoint hii

**Authorization Check**:
```php
// Only job owner can see applicants
if ($job->user_id !== $user->id) {
    return response()->json([
        'error' => 'Huna ruhusa ya kuona waombaji wa kazi hii.',
        'status' => 'forbidden'
    ], 403);
}
```

---

## 📊 Muhtasari wa Ufikiaji (Access Summary)

| Endpoint | Method | Accepted Worker | Muhitaji | Commented Worker |
|----------|--------|----------------|----------|------------------|
| `GET /api/chat` | `apiIndex()` | ✅ | ✅ | ✅ |
| `GET /api/chat/{job}` | `apiShow()` | ✅ | ✅ | ✅ |
| `POST /api/chat/{job}/send` | `apiSend()` | ✅ | ✅ | ✅ |
| `GET /api/chat/{job}/poll` | `apiPoll()` | ✅ | ✅ | ✅ |
| `GET /api/chat/unread-count` | `apiUnreadCount()` | ✅ | ✅ | ✅ |
| `GET /api/chat/{job}/applicants` | `apiJobApplicants()` | ❌ | ✅ | ❌ |

**Legend**:
- ✅ = Anaweza kutumia endpoint
- ❌ = Hana ruhusa

---

## 🔐 Authorization Flow kwa Accepted Worker

```
1. Mfanyakazi Anachaguliwa
   ↓
   accepted_worker_id = worker_id
   
2. Mfanyakazi Anatumia API
   ↓
   GET /api/chat/{job}
   POST /api/chat/{job}/send
   
3. System Inacheki
   ↓
   $isAcceptedWorker = $job->accepted_worker_id === $user->id
   
4. Authorization Passed ✅
   ↓
   Mfanyakazi Anaweza Chat
```

---

## 🧪 Testing Checklist kwa Accepted Worker

### ✅ Test Case 1: List Conversations
```bash
# Mfanyakazi aliye chaguliwa
GET /api/chat
Authorization: Bearer {worker_token}

# Expected: Conversation inaonekana na job ambayo mfanyakazi amechaguliwa
```

### ✅ Test Case 2: View Chat
```bash
# Mfanyakazi aliye chaguliwa
GET /api/chat/1
Authorization: Bearer {worker_token}

# Expected: 200 OK, messages zinaonekana
```

### ✅ Test Case 3: Send Message
```bash
# Mfanyakazi aliye chaguliwa
POST /api/chat/1/send
Authorization: Bearer {worker_token}
Content-Type: application/json
{
  "message": "Test message from accepted worker"
}

# Expected: 200 OK, message imetumwa
```

### ✅ Test Case 4: Poll for Messages
```bash
# Mfanyakazi aliye chaguliwa
GET /api/chat/1/poll?last_id=0
Authorization: Bearer {worker_token}

# Expected: 200 OK, messages mpya zinarudi
```

### ❌ Test Case 5: Access Denied (Not Accepted Worker)
```bash
# Mfanyakazi ambaye HAJACHAGULIWA
GET /api/chat/1
Authorization: Bearer {not_accepted_worker_token}

# Expected: 403 Forbidden
# Error: "Huna ruhusa ya kuona mazungumzo haya. Umepaswa kuchaguliwa na muhitaji au kutuma comment kwanza."
```

---

## 📝 Hitimisho (Conclusion)

**✅ Zote za API endpoints zipo tayari na zinasaidia mfanyakazi aliye chaguliwa:**

1. ✅ `GET /api/chat` - Inaonyesha conversations zote
2. ✅ `GET /api/chat/{job}` - Inaruhusu mfanyakazi aliye chaguliwa kuona messages
3. ✅ `POST /api/chat/{job}/send` - Inaruhusu mfanyakazi aliye chaguliwa kutuma messages
4. ✅ `GET /api/chat/{job}/poll` - Inaruhusu mfanyakazi aliye chaguliwa ku-poll messages
5. ✅ `GET /api/chat/unread-count` - Inaonyesha unread count

**Authorization checks zote ziko kwa kila endpoint:**
- ✅ `apiShow()` - Checks `accepted_worker_id`
- ✅ `apiSend()` - Checks `accepted_worker_id`
- ✅ `apiPoll()` - Checks `accepted_worker_id`

**Hakuna mabadiliko yanayohitajika kwenye API - zote zipo tayari!** 🎉

---

**Tarehe**: Januari 2025
**Mwendelezo**: API endpoints zote zinafanya kazi vizuri na zinasaidia mfanyakazi aliye chaguliwa.

