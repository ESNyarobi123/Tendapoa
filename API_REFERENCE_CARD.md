# 🎯 API Quick Reference Card

## 🆕 NEW: Chat with Job Applicants

---

### **📡 Endpoint**
```
GET /api/chat/{job_id}/applicants
```

### **🔐 Auth**
```
Authorization: Bearer {token}
```

### **👤 Access**
- ✅ **Muhitaji** (job owner) ONLY
- ❌ Workers cannot access

---

## 📋 Complete Flow

```
┌─────────────────────────────────────────────────────────────┐
│                     CHAT WITH APPLICANTS FLOW               │
└─────────────────────────────────────────────────────────────┘

1️⃣  WORKER APPLIES
    ↓
    POST /api/jobs/{job}/comment
    {
      "body": "Application message",
      "amount": 50000
    }

2️⃣  MUHITAJI VIEWS ALL APPLICANTS ⭐ NEW
    ↓
    GET /api/chat/{job}/applicants
    
    Response:
    {
      "applicants": [
        {
          "worker": { "id": 45, "name": "John" },
          "comment": { "body": "...", "amount": 50000 },
          "is_accepted": false,
          "unread_count": 2,
          "last_message_at": "2024-11-06T11:15:00Z"
        }
      ]
    }

3️⃣  MUHITAJI OPENS CHAT WITH APPLICANT
    ↓
    GET /api/chat/{job}?worker_id=45
    
    Response:
    {
      "messages": [...],
      "other_user": { "id": 45, "name": "John" }
    }

4️⃣  MUHITAJI SENDS MESSAGE
    ↓
    POST /api/chat/{job}/send
    {
      "message": "Je, unaweza kuanza kesho?",
      "receiver_id": 45
    }

5️⃣  WORKER REPLIES
    ↓
    POST /api/chat/{job}/send
    {
      "message": "Ndio, naweza!"
    }

6️⃣  POLL FOR NEW MESSAGES
    ↓
    GET /api/chat/{job}/poll?other_user_id=45&last_id=100

7️⃣  ACCEPT WORKER
    ↓
    POST /api/jobs/{job}/accept/{comment_id}
```

---

## 🔌 All Chat Endpoints

| Method | Endpoint | Description | Access |
|--------|----------|-------------|--------|
| **GET** | `/api/chat` | List all conversations | Auth |
| **GET** | `/api/chat/unread-count` | Total unread messages | Auth |
| **GET** | `/api/chat/{job}` | View chat messages | Auth |
| **POST** | `/api/chat/{job}/send` | Send message | Auth |
| **GET** | `/api/chat/{job}/poll` | Poll new messages | Auth |
| **GET** | `/api/chat/{job}/applicants` | 🆕 **List applicants** | **Muhitaji** |

---

## 💬 Query Parameters

### **View Chat**
```
GET /api/chat/{job}?worker_id=45
```
- `worker_id` (optional): Specific worker to chat with

### **Poll Messages**
```
GET /api/chat/{job}/poll?other_user_id=45&last_id=100
```
- `other_user_id` (optional): Specific user to poll from
- `last_id` (optional): Last message ID received

---

## 📨 Request Bodies

### **Send Message**
```json
POST /api/chat/{job}/send

{
  "message": "Your message here",
  "receiver_id": 45
}
```

**Fields:**
- `message` (required): String, max 5000 chars
- `receiver_id` (optional): For muhitaji to specify receiver

### **Apply for Job**
```json
POST /api/jobs/{job}/comment

{
  "body": "Application message",
  "amount": 50000
}
```

---

## ✅ Response Structures

### **Applicants List Response**
```json
{
  "success": true,
  "job": {
    "id": 123,
    "title": "Job Title",
    "status": "pending"
  },
  "applicants": [
    {
      "worker": {
        "id": 45,
        "name": "Worker Name",
        "email": "worker@example.com",
        "role": "mfanyakazi",
        "avatar": null
      },
      "comment": {
        "id": 789,
        "body": "Application message",
        "amount": 50000,
        "created_at": "2024-11-06T10:30:00Z"
      },
      "is_accepted": false,
      "can_chat": true,
      "unread_count": 2,
      "last_message_at": "2024-11-06T11:15:00Z"
    }
  ],
  "total": 1
}
```

### **Chat Messages Response**
```json
{
  "job": {
    "id": 123,
    "title": "Job Title",
    "status": "pending",
    "price": 50000
  },
  "other_user": {
    "id": 45,
    "name": "Worker Name",
    "role": "mfanyakazi"
  },
  "messages": [
    {
      "id": 1,
      "message": "Message text",
      "is_read": true,
      "created_at": "2024-11-06T10:30:00Z",
      "sender": {
        "id": 45,
        "name": "Worker Name",
        "role": "mfanyakazi"
      }
    }
  ],
  "status": "success"
}
```

### **Send Message Response**
```json
{
  "success": true,
  "message": {
    "id": 100,
    "message": "Message text",
    "created_at": "2024-11-06T11:30:00Z",
    "sender": {
      "id": 10,
      "name": "Your Name",
      "role": "muhitaji"
    }
  },
  "status": "success"
}
```

---

## ❌ Error Responses

### **403 Forbidden**
```json
{
  "error": "Huna ruhusa ya kuona waombaji wa kazi hii.",
  "status": "forbidden"
}
```

**Cause:** Not the job owner

---

### **401 Unauthorized**
```json
{
  "message": "Unauthenticated."
}
```

**Cause:** Missing or invalid token

---

### **404 Not Found**
```json
{
  "error": "Mtumiaji mwingine hajapatikana.",
  "status": "not_found"
}
```

**Cause:** Job or user not found

---

## 🧪 cURL Examples

### **1. Get Applicants**
```bash
curl -X GET "http://localhost:8000/api/chat/123/applicants" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### **2. Open Chat with Worker**
```bash
curl -X GET "http://localhost:8000/api/chat/123?worker_id=45" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### **3. Send Message**
```bash
curl -X POST "http://localhost:8000/api/chat/123/send" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "message": "Je, unaweza kuanza kesho?",
    "receiver_id": 45
  }'
```

### **4. Poll Messages**
```bash
curl -X GET "http://localhost:8000/api/chat/123/poll?other_user_id=45&last_id=0" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## 🎯 Key Features

| Feature | Description |
|---------|-------------|
| ✅ **Multiple Chats** | Muhitaji can chat with multiple applicants |
| ✅ **Before Selection** | Chat before accepting worker |
| ✅ **Unread Counts** | Per-applicant unread message count |
| ✅ **Last Message** | Timestamp of last message |
| ✅ **Status Indicator** | Shows which worker is accepted |
| ✅ **Secure** | Only job owner can access |

---

## 💡 Pro Tips

### **Frontend Best Practices**

1. **Sort Applicants:**
   ```js
   // By unread count
   applicants.sort((a, b) => b.unread_count - a.unread_count);
   
   // By last message (most recent first)
   applicants.sort((a, b) => 
     new Date(b.last_message_at) - new Date(a.last_message_at)
   );
   ```

2. **Auto-refresh:**
   ```js
   // Refresh applicants list every 30 seconds
   setInterval(() => fetchApplicants(), 30000);
   ```

3. **Show Notifications:**
   ```js
   const totalUnread = applicants.reduce(
     (sum, app) => sum + app.unread_count, 
     0
   );
   ```

4. **Highlight Accepted:**
   ```jsx
   {applicant.is_accepted && (
     <Badge color="green">✓ ACCEPTED</Badge>
   )}
   ```

---

## 📱 Mobile App Example

```dart
// Flutter/Dart Example

Future<List<Applicant>> fetchApplicants(int jobId) async {
  final response = await http.get(
    Uri.parse('$baseUrl/api/chat/$jobId/applicants'),
    headers: {
      'Authorization': 'Bearer $token',
      'Accept': 'application/json',
    },
  );
  
  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    return (data['applicants'] as List)
        .map((json) => Applicant.fromJson(json))
        .toList();
  }
  
  throw Exception('Failed to load applicants');
}
```

---

## 🔗 Quick Links

- 📖 [Full API Documentation](CHAT_APPLICANTS_API.md)
- ⚡ [Quick Start Guide](CHAT_APPLICANTS_QUICK_GUIDE.md)
- 📮 [Postman Collection](CHAT_APPLICANTS_POSTMAN.json)
- ✅ [Implementation Summary](IMPLEMENTATION_SUMMARY.md)

---

## 🎨 UI Components

### **Applicant Card**
```jsx
<Card>
  <Avatar src={applicant.worker.avatar} />
  <Name>{applicant.worker.name}</Name>
  <Message>{applicant.comment.body}</Message>
  <Amount>{applicant.comment.amount} TSH</Amount>
  <Time>{applicant.last_message_at}</Time>
  {applicant.unread_count > 0 && (
    <Badge>{applicant.unread_count}</Badge>
  )}
  {applicant.is_accepted && <CheckIcon />}
  <Button onClick={() => openChat(applicant.worker.id)}>
    Chat
  </Button>
  {!applicant.is_accepted && (
    <Button onClick={() => accept(applicant.comment.id)}>
      Accept
    </Button>
  )}
</Card>
```

---

## 🚦 Status Codes

| Code | Meaning | Action |
|------|---------|--------|
| **200** | Success | Process response |
| **401** | Unauthorized | Re-authenticate |
| **403** | Forbidden | Check permissions |
| **404** | Not Found | Check IDs |
| **422** | Validation Error | Fix request data |
| **500** | Server Error | Retry later |

---

## 📊 Comparison

| Feature | Before | After |
|---------|--------|-------|
| **Chat Access** | Only accepted worker | ALL applicants ✅ |
| **Selection** | Blind choice | Informed decision ✅ |
| **Conversations** | Single | Multiple ✅ |
| **Unread Tracking** | Global only | Per-applicant ✅ |
| **Worker Status** | Hidden | Visible ✅ |

---

**🎉 You're all set!** Use this card as a quick reference while integrating the API.

---

**Last Updated:** November 6, 2024  
**Version:** 1.0.0  
**Status:** Production Ready ✅

