# TendaPoa API Documentation

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
All API endpoints require authentication using Laravel Sanctum. Include the token in the Authorization header:
```
Authorization: Bearer {your-token}
```

## Endpoints

### 1. Health Check
**GET** `/api/health`
- **Description**: Check API health status
- **Authentication**: Not required
- **Response**:
```json
{
  "status": "ok",
  "timestamp": "2025-01-24T10:30:00Z",
  "version": "1.0.0"
}
```

### 2. Job Management

#### Get My Jobs (Muhitaji)
**GET** `/api/jobs/my`
- **Description**: Get all jobs posted by authenticated user
- **Authentication**: Required (muhitaji/admin)
- **Response**:
```json
{
  "jobs": [
    {
      "id": 1,
      "title": "Usafi wa nyumba",
      "description": "Nahitaji usafi wa nyumba",
      "price": 50000,
      "status": "posted",
      "category": {
        "id": 1,
        "name": "Inside Home Cleaning"
      },
      "accepted_worker": null,
      "comments_count": 0,
      "created_at": "2025-01-24T10:30:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 12,
    "total": 25,
    "has_more": true
  },
  "status": "success"
}
```

#### Get Job Details
**GET** `/api/jobs/{id}`
- **Description**: Get specific job details
- **Authentication**: Required
- **Response**:
```json
{
  "job": {
    "id": 1,
    "title": "Usafi wa nyumba",
    "description": "Nahitaji usafi wa nyumba",
    "price": 50000,
    "lat": -6.771572,
    "lng": 39.231092,
    "address_text": "Mwenge, Dar es Salaam",
    "status": "posted",
    "category": {
      "id": 1,
      "name": "Inside Home Cleaning"
    },
    "muhitaji": {
      "id": 1,
      "name": "John Doe",
      "phone": "0750123456"
    }
  },
  "status": "success"
}
```

#### Create New Job
**POST** `/api/jobs`
- **Description**: Create a new job
- **Authentication**: Required (muhitaji/admin)
- **Request Body**:
```json
{
  "title": "Usafi wa nyumba",
  "description": "Nahitaji usafi wa nyumba",
  "category_id": 1,
  "price": 50000,
  "lat": -6.771572,
  "lng": 39.231092,
  "address_text": "Mwenge, Dar es Salaam",
  "phone": "0750123456"
}
```
- **Response**:
```json
{
  "message": "Kazi imechapishwa! Malipo yanahitajika.",
  "job": {
    "id": 1,
    "title": "Usafi wa nyumba",
    "price": 50000,
    "status": "posted"
  },
  "payment_url": "http://127.0.0.1:8000/jobs/1/wait",
  "status": "success"
}
```

#### Edit Job
**GET** `/api/jobs/{id}/edit`
- **Description**: Get job data for editing
- **Authentication**: Required (job owner only)
- **Response**:
```json
{
  "job": {
    "id": 1,
    "title": "Usafi wa nyumba",
    "description": "Nahitaji usafi wa nyumba",
    "price": 50000,
    "category_id": 1,
    "lat": -6.771572,
    "lng": 39.231092,
    "address_text": "Mwenge, Dar es Salaam",
    "status": "posted"
  },
  "categories": [
    {
      "id": 1,
      "name": "Inside Home Cleaning",
      "slug": "inside-home"
    }
  ],
  "can_edit": true,
  "status": "success"
}
```

#### Update Job
**PUT** `/api/jobs/{id}`
- **Description**: Update job details
- **Authentication**: Required (job owner only)
- **Request Body**:
```json
{
  "title": "Usafi wa nyumba (Updated)",
  "description": "Nahitaji usafi wa nyumba kubwa",
  "category_id": 1,
  "price": 75000,
  "lat": -6.771572,
  "lng": 39.231092,
  "address_text": "Mwenge, Dar es Salaam"
}
```
- **Response** (No price increase):
```json
{
  "message": "Kazi imebadilishwa kwa mafanikio!",
  "job": {
    "id": 1,
    "title": "Usafi wa nyumba (Updated)",
    "price": 75000,
    "status": "posted",
    "updated_at": "2025-01-24T10:30:00Z"
  },
  "status": "success"
}
```
- **Response** (With price increase):
```json
{
  "message": "Kazi imebadilishwa! Malipo ya ziada ya TZS 25,000 yanahitajika.",
  "job": {
    "id": 1,
    "title": "Usafi wa nyumba (Updated)",
    "price": 75000,
    "status": "posted",
    "updated_at": "2025-01-24T10:30:00Z"
  },
  "payment_required": true,
  "payment_amount": 25000,
  "payment_url": "http://127.0.0.1:8000/jobs/1/wait",
  "status": "success"
}
```

#### Check Payment Status
**GET** `/api/jobs/{id}/payment-status`
- **Description**: Check payment status for a job
- **Authentication**: Required
- **Response**:
```json
{
  "done": true,
  "status": "completed",
  "payment": {
    "id": 1,
    "amount": 50000,
    "status": "COMPLETED",
    "reference": "TXN123456",
    "completed_at": "2025-01-24T10:30:00Z"
  }
}
```

### 3. Feed (For Workers)

#### Get Jobs Feed
**GET** `/api/feed`
- **Description**: Get jobs feed for workers
- **Authentication**: Required (mfanyakazi/admin)
- **Query Parameters**:
  - `category` (optional): Filter by category slug
  - `distance` (optional): Filter by maximum distance in km
- **Response**:
```json
{
  "jobs": [
    {
      "id": 1,
      "title": "Usafi wa nyumba",
      "price": 50000,
      "lat": -6.771572,
      "lng": 39.231092,
      "distance_info": {
        "distance": 2.5,
        "category": "near",
        "color": "#10b981",
        "bg_color": "#d1fae5",
        "text_color": "#065f46",
        "label": "Karibu sana"
      },
      "category": {
        "id": 1,
        "name": "Inside Home Cleaning"
      },
      "muhitaji": {
        "id": 1,
        "name": "John Doe"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 12,
    "total": 25,
    "has_more": true
  },
  "filters": {
    "category": null,
    "distance": null
  },
  "user_location": {
    "lat": -6.771572,
    "lng": 39.231092
  },
  "status": "success"
}
```

#### Get Jobs for Map
**GET** `/api/feed/map`
- **Description**: Get jobs with coordinates for map display
- **Authentication**: Required (mfanyakazi/admin)
- **Response**:
```json
{
  "jobs": [
    {
      "id": 1,
      "title": "Usafi wa nyumba",
      "price": 50000,
      "lat": -6.771572,
      "lng": 39.231092,
      "distance_info": {
        "distance": 2.5,
        "category": "near",
        "color": "#10b981"
      },
      "category": {
        "id": 1,
        "name": "Inside Home Cleaning"
      }
    }
  ],
  "user_location": {
    "lat": -6.771572,
    "lng": 39.231092
  },
  "total_jobs": 15,
  "status": "success"
}
```

### 4. User Profile

#### Get User Profile
**GET** `/api/profile`
- **Description**: Get authenticated user profile
- **Authentication**: Required
- **Response**:
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "0750123456",
    "role": "muhitaji"
  },
  "role": "muhitaji",
  "has_location": true,
  "location": "-6.771572, 39.231092"
}
```

### 5. Dashboard

#### Get Dashboard Data
**GET** `/api/dashboard`
- **Description**: Get dashboard statistics
- **Authentication**: Required
- **Response** (Muhitaji):
```json
{
  "role": "muhitaji",
  "stats": {
    "posted_jobs": 5,
    "completed_jobs": 3,
    "total_paid": 150000
  }
}
```
- **Response** (Mfanyakazi):
```json
{
  "role": "mfanyakazi",
  "stats": {
    "completed_jobs": 8,
    "total_earnings": 400000,
    "current_jobs": 2
  }
}
```

## Error Responses

### 400 Bad Request
```json
{
  "error": "Huwezi kubadilisha kazi ambayo imeanza au imekamilika.",
  "status": "error"
}
```

### 403 Forbidden
```json
{
  "error": "Huna ruhusa ya kubadilisha kazi hii.",
  "status": "forbidden"
}
```

### 422 Validation Error
```json
{
  "error": "Huwezi kupunguza bei ya kazi. Unaweza kuongeza tu.",
  "status": "validation_error",
  "field": "price"
}
```

### 500 Server Error
```json
{
  "error": "Imeshindikana kuanzisha malipo. Jaribu tena.",
  "status": "payment_error"
}
```

## Business Rules

### Job Editing Rules:
1. **Price Increase Only**: Cannot reduce job price, only increase
2. **Additional Payment**: Pay only the difference when increasing price
3. **Status Restrictions**: Can only edit jobs with status 'posted' or 'assigned'
4. **Owner Only**: Only job owner can edit their jobs
5. **Free Details**: Can edit title, description, location without payment

### Distance Categories:
- **Near (≤5km)**: Green color (#10b981)
- **Moderate (≤10km)**: Orange color (#f59e0b)  
- **Far (>10km)**: Red color (#ef4444)
- **Unknown**: Gray color (#6b7280)

## Rate Limiting
- **Default**: 60 requests per minute per user
- **Job Creation**: 10 requests per minute per user
- **Payment Polling**: 30 requests per minute per user

## Testing
Use tools like Postman or curl to test the API:

```bash
# Get jobs feed
curl -H "Authorization: Bearer {token}" \
     -H "Accept: application/json" \
     http://127.0.0.1:8000/api/feed

# Create job
curl -X POST \
     -H "Authorization: Bearer {token}" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{"title":"Test Job","category_id":1,"price":50000,"lat":-6.771572,"lng":39.231092,"phone":"0750123456"}' \
     http://127.0.0.1:8000/api/jobs
```
