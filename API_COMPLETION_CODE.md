# 🔐 Completion Code API Documentation

## Muhtasari (Summary)

APIs mpya za kusimamia **completion codes** - code ambazo Muhitaji anampa Mfanyakazi ili kuthibitisha kazi imekamilika na kulipa.

---

## 📋 Mtiririko Kamili wa Kazi (Complete Job Flow)

```
1. Muhitaji → Chapisha kazi
   POST /api/jobs

2. Mfanyakazi → Omba kazi / Comment
   POST /api/jobs/{job}/apply
   POST /api/jobs/{job}/comment

3. Muhitaji → Chagua mfanyakazi
   POST /api/jobs/{job}/accept/{comment}
   Status: 'assigned'

4. Mfanyakazi → Kubali kazi (CODE INATENGENEZWA HAPA!)
   POST /api/worker/jobs/{job}/accept
   Status: 'in_progress'
   ✅ Completion code (6 digits) generated: e.g., "123456"

5. Muhitaji → Pata completion code
   GET /api/jobs/{job}/completion-code
   ✅ Muhitaji anaona code kumpa mfanyakazi

6. Mfanyakazi → Fanya kazi...

7. Mfanyakazi → Weka code & Kamilisha
   POST /api/worker/jobs/{job}/complete
   Body: { "code": "123456" }
   ✅ Kazi completed + PESA kwenye wallet!
```

---

## 🆕 API Mpya Zilizoongezwa

### 1️⃣ **GET /api/jobs/{job}/completion-code**
**Kazi ya Muhitaji kupata completion code**

#### Authentication
Bearer Token (Sanctum) - Lazima uwe owner wa kazi au admin

#### Response - Mafanikio (200)
```json
{
  "success": true,
  "completion_code": "123456",
  "job": {
    "id": 45,
    "title": "Fua nguo za familia",
    "status": "in_progress",
    "worker": {
      "id": 12,
      "name": "Juma Mwanakazi",
      "phone": "0712345678"
    }
  },
  "instructions": "Mpe mfanyakazi code hii akimaliza kazi. Atatumia code hii kuthibitisha na kupata malipo.",
  "status": "success"
}
```

#### Error Responses
```json
// 403 - Si kazi yako
{
  "success": false,
  "error": "Huna ruhusa ya kuona code hii. Ni kazi yako tu.",
  "status": "forbidden"
}

// 400 - Hakuna worker
{
  "success": false,
  "error": "Kazi hii haijapewa mfanyakazi bado.",
  "status": "no_worker"
}

// 400 - Status si sahihi
{
  "success": false,
  "error": "Code haijapatikana bado. Mfanyakazi lazima akubali kazi kwanza.",
  "status": "invalid_status",
  "current_status": "assigned"
}
```

#### Matumizi (Usage Example - JavaScript)
```javascript
// Muhitaji kupata code
async function getCompletionCode(jobId) {
  const response = await fetch(`/api/jobs/${jobId}/completion-code`, {
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    }
  });
  
  const data = await response.json();
  
  if (data.success) {
    console.log('Completion Code:', data.completion_code);
    // Onyesha code kwa muhitaji
    alert(`Code ya kazi: ${data.completion_code}\nMpe mfanyakazi: ${data.job.worker.name}`);
  }
}
```

---

### 2️⃣ **POST /api/jobs/{job}/regenerate-code**
**Tengeneza code mpya (ikiwa code imepotea/kuna tatizo)**

#### Authentication
Bearer Token (Sanctum) - Lazima uwe owner wa kazi au admin

#### Masharti (Conditions)
- Kazi lazima iwe status `in_progress`
- Haiwezi ku-regenerate kazi zilizokamilika

#### Response - Mafanikio (200)
```json
{
  "success": true,
  "message": "Code mpya imetengenezwa kwa mafanikio!",
  "completion_code": "789012",
  "old_code": "123456",
  "job": {
    "id": 45,
    "title": "Fua nguo za familia",
    "status": "in_progress"
  },
  "warning": "Hakikisha unamjulisha mfanyakazi kuhusu code mpya!",
  "status": "success"
}
```

#### Error Responses
```json
// 403 - Si kazi yako
{
  "success": false,
  "error": "Huna ruhusa ya kubadilisha code. Ni kazi yako tu.",
  "status": "forbidden"
}

// 400 - Kazi imekamilika
{
  "success": false,
  "error": "Kazi imekamilika tayari. Hauwezi kubadilisha code.",
  "status": "already_completed"
}

// 400 - Status si sahihi
{
  "success": false,
  "error": "Unaweza kubadilisha code wakati kazi iko 'in_progress' tu.",
  "status": "invalid_status",
  "current_status": "assigned"
}
```

#### Matumizi (Usage Example - JavaScript)
```javascript
// Muhitaji ku-regenerate code
async function regenerateCode(jobId) {
  const confirmed = confirm('Je, una uhakika unataka code mpya? Code ya zamani haitafanya kazi tena.');
  
  if (!confirmed) return;
  
  const response = await fetch(`/api/jobs/${jobId}/regenerate-code`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    }
  });
  
  const data = await response.json();
  
  if (data.success) {
    alert(`Code mpya: ${data.completion_code}\n\n${data.warning}`);
    // Call worker/send SMS with new code
    notifyWorker(data.job.id, data.completion_code);
  }
}
```

---

### 3️⃣ **GET /api/jobs/{job}** (IMPROVED)
**Pata maelezo ya kazi (including completion code)**

#### Changes
Sasa response inaonyesha `completion_code` kama:
- User ni muhitaji wa kazi hiyo OR ni worker aliyechaguliwa
- Status ni `in_progress` au `completed`
- Code ipo

#### Response Example
```json
{
  "success": true,
  "job": {
    "id": 45,
    "title": "Fua nguo za familia",
    "status": "in_progress",
    "completion_code": "123456",  // ✅ NEW FIELD
    "completed_at": null,
    // ... other fields
  }
}
```

---

## 🔄 APIs Zilizopo (Existing APIs)

### 4️⃣ **POST /api/worker/jobs/{job}/accept**
**Mfanyakazi kukubali kazi (generates code automatically)**

#### Request
```javascript
POST /api/worker/jobs/{job}/accept
Authorization: Bearer {token}
```

#### Response
```json
{
  "success": true,
  "message": "Umeikubali kazi.",
  "job": {
    "id": 45,
    "status": "in_progress",
    "completion_code": "123456"  // 6-digit code generated
  },
  "status": "success"
}
```

---

### 5️⃣ **POST /api/worker/jobs/{job}/complete**
**Mfanyakazi kuweka code na kamilisha kazi**

#### Request
```javascript
POST /api/worker/jobs/{job}/complete
Authorization: Bearer {token}
Content-Type: application/json

{
  "code": "123456"
}
```

#### Response - Mafanikio (200)
```json
{
  "success": true,
  "message": "Kazi imethibitishwa! Utapokea malipo yako.",
  "job": {
    "id": 45,
    "status": "completed",
    "completed_at": "2025-11-05T14:30:00.000000Z"
  },
  "status": "success"
}
```

#### Error - Code si sahihi (400)
```json
{
  "success": false,
  "error": "Code si sahihi. Angalia code uliyopewa na mteja.",
  "status": "invalid_code"
}
```

---

## 🛠️ Complete Frontend Integration Example

### React/React Native Component

```javascript
import { useState, useEffect } from 'react';
import { useAuth } from './context/AuthContext';

function JobCompletionManager({ jobId }) {
  const { token, user } = useAuth();
  const [job, setJob] = useState(null);
  const [completionCode, setCompletionCode] = useState('');
  const [loading, setLoading] = useState(false);

  // 1. Load job details
  useEffect(() => {
    loadJob();
  }, [jobId]);

  const loadJob = async () => {
    const response = await fetch(`/api/jobs/${jobId}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });
    const data = await response.json();
    if (data.success) {
      setJob(data.job);
      setCompletionCode(data.job.completion_code || '');
    }
  };

  // 2. Muhitaji: Get completion code
  const getCode = async () => {
    setLoading(true);
    const response = await fetch(`/api/jobs/${jobId}/completion-code`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });
    const data = await response.json();
    setLoading(false);
    
    if (data.success) {
      setCompletionCode(data.completion_code);
      alert(`Code: ${data.completion_code}\n${data.instructions}`);
    } else {
      alert(data.error);
    }
  };

  // 3. Muhitaji: Regenerate code
  const regenerateCode = async () => {
    if (!confirm('Tengeneza code mpya? Code ya zamani haitafanya kazi.')) return;
    
    setLoading(true);
    const response = await fetch(`/api/jobs/${jobId}/regenerate-code`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });
    const data = await response.json();
    setLoading(false);
    
    if (data.success) {
      setCompletionCode(data.completion_code);
      alert(`Code mpya: ${data.completion_code}\n\n${data.warning}`);
      loadJob(); // Refresh job
    } else {
      alert(data.error);
    }
  };

  // 4. Mfanyakazi: Accept job (generates code)
  const acceptJob = async () => {
    setLoading(true);
    const response = await fetch(`/api/worker/jobs/${jobId}/accept`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });
    const data = await response.json();
    setLoading(false);
    
    if (data.success) {
      alert(`Umekubali kazi!\nCompletion Code: ${data.job.completion_code}`);
      loadJob();
    }
  };

  // 5. Mfanyakazi: Complete job with code
  const completeJob = async (code) => {
    setLoading(true);
    const response = await fetch(`/api/worker/jobs/${jobId}/complete`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ code })
    });
    const data = await response.json();
    setLoading(false);
    
    if (data.success) {
      alert('🎉 Hongera! Kazi imekamilika. Pesa zitaingia wallet yako.');
      loadJob();
    } else {
      alert(`❌ ${data.error}`);
    }
  };

  if (!job) return <div>Loading...</div>;

  return (
    <div className="job-completion">
      <h2>{job.title}</h2>
      <p>Status: <strong>{job.status}</strong></p>

      {/* MUHITAJI VIEW */}
      {user.role === 'muhitaji' && job.user_id === user.id && (
        <div className="muhitaji-actions">
          {job.status === 'in_progress' && (
            <>
              <div className="code-display">
                <h3>🔐 Completion Code</h3>
                {completionCode ? (
                  <div className="code-box">
                    <h1>{completionCode}</h1>
                    <button onClick={() => navigator.clipboard.writeText(completionCode)}>
                      📋 Copy Code
                    </button>
                  </div>
                ) : (
                  <button onClick={getCode} disabled={loading}>
                    Get Completion Code
                  </button>
                )}
              </div>
              
              <button onClick={regenerateCode} disabled={loading}>
                🔄 Generate New Code
              </button>
              
              <p className="instructions">
                ℹ️ Mpe mfanyakazi code hii akimaliza kazi. 
                Atatumia code hii kupata malipo yake.
              </p>
            </>
          )}
        </div>
      )}

      {/* MFANYAKAZI VIEW */}
      {user.role === 'mfanyakazi' && job.accepted_worker_id === user.id && (
        <div className="worker-actions">
          {job.status === 'assigned' && (
            <button onClick={acceptJob} disabled={loading}>
              ✅ Accept Job & Start Working
            </button>
          )}
          
          {job.status === 'in_progress' && (
            <>
              <div className="code-input">
                <h3>🔐 Enter Completion Code</h3>
                <input
                  type="text"
                  maxLength="6"
                  placeholder="123456"
                  value={completionCode}
                  onChange={(e) => setCompletionCode(e.target.value)}
                />
                <button 
                  onClick={() => completeJob(completionCode)} 
                  disabled={loading || completionCode.length !== 6}
                >
                  ✅ Complete Job & Get Paid
                </button>
              </div>
              
              <p className="instructions">
                ℹ️ Omba code kutoka kwa mteja akimaliza kazi. 
                Weka code ili kuthibitisha na kupata malipo yako.
              </p>
            </>
          )}
        </div>
      )}
    </div>
  );
}

export default JobCompletionManager;
```

---

## ✅ Testing Checklist

### Muhitaji Tests
- [ ] Ona completion code wakati kazi iko `in_progress`
- [ ] Get error kama kazi bado `assigned` (worker hajakubali)
- [ ] Get error kama si kazi yako
- [ ] Regenerate code successfully
- [ ] Get error kama unataka regenerate kazi completed
- [ ] Code mpya ni different na ya zamani

### Mfanyakazi Tests
- [ ] Accept job successfully generates 6-digit code
- [ ] See completion code in job details
- [ ] Complete job with correct code = success + payment
- [ ] Complete job with wrong code = error
- [ ] Cannot complete kazi isiyo `in_progress`
- [ ] Cannot complete kazi ya mtu mwingine

### Edge Cases
- [ ] Code ni string ya digits 6 exactly
- [ ] Code generated ni unique (very unlikely collision)
- [ ] Payment processed after successful completion
- [ ] Logging iko for debugging (check Laravel logs)

---

## 📝 Notes

1. **Security**: 
   - Completion codes ni 6-digit numbers (100000-999999)
   - Only owner/worker/admin wanaweza kuona code
   - Codes validated strictly (must match exactly)

2. **Payment Flow**:
   - Payment inafanya automatically baada ya code verification
   - Money credited to worker's wallet via WalletService
   - Transaction history recorded

3. **Logging**:
   - Code validation attempts logged
   - Code regeneration logged with old/new values
   - Payment processing logged

4. **Best Practices**:
   - Always notify worker when regenerating code
   - Use SMS/push notification to send codes
   - Display code prominently in UI
   - Add copy-to-clipboard functionality

---

## 🚀 Summary

### API Mpya:
1. `GET /api/jobs/{job}/completion-code` - Muhitaji kupata code
2. `POST /api/jobs/{job}/regenerate-code` - Muhitaji kubadilisha code
3. `GET /api/jobs/{job}` - Improved to include completion_code

### API Zilizopo (Improved):
4. `POST /api/worker/jobs/{job}/accept` - Generates code automatically
5. `POST /api/worker/jobs/{job}/complete` - Validates code & pays worker

**Mtiririko Kamili**: Muhitaji → Chagua worker → Worker accepts (CODE) → Worker works → Muhitaji gives CODE → Worker submits CODE → PAID! 💰

---

**Created by:** AI Assistant  
**Date:** November 5, 2025  
**Version:** 1.0

