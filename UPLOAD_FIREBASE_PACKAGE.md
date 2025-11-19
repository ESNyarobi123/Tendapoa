# 📦 Upload Firebase Package Manually kwenye Hosting

## 🎯 Njia Rahisi: Upload Vendor Folders

Kwa sababu huna SSH access, tuta-upload folders za vendor manually.

## 📋 Folders za Ku-upload

Upload folders hizi kutoka `vendor/` directory yako kwenye server:

### Folders Muhimu (Required):

1. **`kreait/`** - Firebase package
   - `kreait/firebase-php/`
   - `kreait/firebase-tokens/`

2. **`google/`** - Google Cloud dependencies
   - `google/auth/`
   - `google/cloud-core/`
   - `google/cloud-storage/`
   - `google/common-protos/`
   - `google/gax/`
   - `google/grpc-gcp/`
   - `google/longrunning/`
   - `google/protobuf/`

3. **`firebase/`** - Firebase JWT
   - `firebase/php-jwt/`

4. **`lcobucci/`** - JWT library
   - `lcobucci/jwt/`

5. **`beste/`** - Helper libraries
   - `beste/clock/`
   - `beste/in-memory-cache/`
   - `beste/json/`

6. **`cuyz/`** - Valinor
   - `cuyz/valinor/`

7. **`fig/`** - HTTP utilities
   - `fig/http-message-util/`

8. **`grpc/`** - gRPC
   - `grpc/grpc/`

9. **`rize/`** - URI template
   - `rize/uri-template/`

10. **`mtdowling/`** - JMESPath
    - `mtdowling/jmespath.php/`

### Files Muhimu:

1. **`vendor/composer/autoload_psr4.php`** - Lazima i-update
2. **`vendor/composer/installed.json`** - Lazima i-update
3. **`vendor/autoload.php`** - Lazima i-update

## 🚀 Hatua kwa Hatua

### Hatua 1: Prepare Files kwenye Local

1. **Nenda kwenye project directory:**
   ```
   D:\LARAVEL PROJECT\complete\vendor\
   ```

2. **Zip folders hizi:**
   - `kreait/`
   - `google/`
   - `firebase/`
   - `lcobucci/`
   - `beste/`
   - `cuyz/`
   - `fig/`
   - `grpc/`
   - `rize/`
   - `mtdowling/`

### Hatua 2: Upload kwenye Server

**Kwa FTP (FileZilla, WinSCP):**

1. **Connect kwenye server**
2. **Nenda kwenye:**
   ```
   /home/tendapb8/public_html/api/vendor/
   ```
   (Au path yako halisi)

3. **Upload folders zote** moja kwa moja
   - Extract zip files
   - Au upload folders directly

4. **Hakikisha structure:**
   ```
   vendor/
     ├── kreait/
     ├── google/
     ├── firebase/
     ├── lcobucci/
     ├── beste/
     ├── cuyz/
     ├── fig/
     ├── grpc/
     ├── rize/
     └── mtdowling/
   ```

### Hatua 3: Update Composer Autoload Files

**Muhimu:** Lazima u-update files hizi:

1. **Download `vendor/composer/autoload_psr4.php`** kutoka server
2. **Add entries hizi** (kama hazipo):

```php
'Kreait\\Firebase\\' => array($vendorDir . '/kreait/firebase-php/src'),
'Kreait\\Firebase\\JWT\\' => array($vendorDir . '/kreait/firebase-tokens/src'),
'Google\\Auth\\' => array($vendorDir . '/google/auth/src'),
'Google\\Cloud\\Core\\' => array($vendorDir . '/google/cloud-core/src'),
// ... nk
```

3. **Upload file updated** kwenye server

**Au upload `vendor/composer/` folder nzima** kutoka local (kama ina nafasi)

### Hatua 4: Upload Credentials File

1. **File:** `tendapoa-eb234-firebase-adminsdk-fbsvc-c3b97c7be3.json`
2. **Upload kwenye project root** (sawa na `artisan` file)
3. **Set permissions:** `644`

### Hatua 5: Clear Caches

**Kama una cPanel Terminal:**

```bash
cd ~/public_html/api
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

**Kama huna Terminal:**
- Delete `storage/framework/cache/data/` (folder nzima)
- Delete `bootstrap/cache/config.php` (kama ipo)

### Hatua 6: Test

1. Visit: `https://apis.tendapoa.com/admin/push-notifications`
2. Alert haipaswi kuonekana
3. Tuma test notification

## ⚠️ Alternative: Upload Vendor Folder Nzima

**Kama ina nafasi na bandwidth:**

1. **Zip `vendor/` folder nzima** kutoka local
2. **Upload kwenye server**
3. **Extract kwenye server**
4. **Set permissions:**
   ```bash
   chmod -R 755 vendor/
   ```

## 📝 Quick Checklist

- [ ] Zip vendor folders (kreait, google, firebase, nk)
- [ ] Upload kwenye server `/vendor/` directory
- [ ] Update `vendor/composer/autoload_psr4.php`
- [ ] Upload credentials file
- [ ] Clear caches
- [ ] Test kwenye browser

## 🎯 Kama Bado Kuna Tatizo

**Mwambie hosting provider:**
> "Nahitaji ku-install Composer package. Tafadhali install: kreait/firebase-php
> 
> Command: composer require kreait/firebase-php --ignore-platform-req=ext-sodium --no-dev
> 
> Path: /home/tendapb8/public_html/api"

