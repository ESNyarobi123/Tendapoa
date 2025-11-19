# 📦 Manual Upload ya Firebase Package

## ⚠️ Kumbuka
Hii si njia bora, lakini inaweza kufanya kazi kama huna SSH access.

## 📋 Hatua kwa Hatua

### Hatua 1: Download Package kwenye Local Machine

Kwenye local machine yako (kompyuta yako):

```bash
# Nenda kwenye project directory
cd "D:\LARAVEL PROJECT\complete"

# Download package peke yake (bila ku-install dependencies zote)
composer require kreait/firebase-php --ignore-platform-req=ext-sodium --no-scripts --no-plugins
```

### Hatua 2: Zip Vendor Folder

Baada ya ku-download, zip folder ya `vendor/kreait`:

**Kwenye Windows:**
1. Nenda kwenye `vendor/kreait`
2. Right-click → Send to → Compressed (zipped) folder
3. Jina: `kreait-firebase.zip`

**Au kwa PowerShell:**
```powershell
cd "D:\LARAVEL PROJECT\complete\vendor"
Compress-Archive -Path kreait -DestinationPath kreait-firebase.zip
```

### Hatua 3: Upload kwenye Server

1. **Fungua FTP client** (FileZilla, WinSCP, au cPanel File Manager)

2. **Nenda kwenye server path:**
   ```
   /home/tendapb8/public_html/api/vendor/
   ```
   (Au path yako halisi)

3. **Upload folder `kreait`** kwenye `vendor/` directory
   - Extract zip file kwenye server
   - Au upload folder moja kwa moja

4. **Hakikisha structure:**
   ```
   vendor/
     └── kreait/
         ├── firebase-php/
         └── firebase-tokens/
   ```

### Hatua 4: Upload Dependencies

Firebase package inahitaji dependencies zingine. Upload folders hizi pia:

**Folders za ku-upload kutoka `vendor/`:**
- `kreait/` (Firebase package)
- `google/` (Google Cloud dependencies)
- `firebase/` (Firebase JWT)
- `lcobucci/` (JWT library)
- `beste/` (Helper libraries)
- `cuyz/` (Valinor)
- `fig/` (HTTP utilities)
- `grpc/` (gRPC)
- `rize/` (URI template)
- `mtdowling/` (JMESPath)

**Au upload vendor folder nzima** (kama ina nafasi)

### Hatua 5: Upload Composer Autoload Files

**Muhimu:** Upload files hizi pia:

1. `vendor/autoload.php`
2. `vendor/composer/` folder (nzima)

**Kwenye server, hakikisha:**
```
vendor/
  ├── autoload.php
  ├── composer/
  │   ├── autoload_classmap.php
  │   ├── autoload_files.php
  │   ├── autoload_namespaces.php
  │   ├── autoload_psr4.php
  │   └── installed.json
  └── kreait/
```

### Hatua 6: Regenerate Autoloader (kama una Terminal)

Kama una cPanel Terminal:

```bash
cd ~/public_html/api
composer dump-autoload --optimize
```

**Kama huna Terminal:**
- Upload `vendor/composer/` folder kutoka local
- Hakikisha `autoload_psr4.php` ina entries za Firebase

### Hatua 7: Upload Firebase Credentials File

**Muhimu:** Upload file ya credentials:

1. File: `tendapoa-eb234-firebase-adminsdk-fbsvc-c3b97c7be3.json`
2. Upload kwenye project root (sawa na `artisan` file)
3. Set permissions: `644`

### Hatua 8: Clear Caches

Kama una cPanel Terminal:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

**Kama huna Terminal:**
- Delete files kwenye `storage/framework/cache/`
- Delete files kwenye `storage/framework/views/`
- Delete `bootstrap/cache/config.php` (kama ipo)

### Hatua 9: Test

1. Visit: `https://apis.tendapoa.com/admin/push-notifications`
2. Alert haipaswi kuonekana tena
3. Tuma test notification

## ⚠️ Important Notes

1. **PHP Version:** Hakikisha server ina PHP 8.2+ (sawa na local)
2. **File Permissions:** 
   - Files: `644`
   - Folders: `755`
3. **Vendor Size:** Vendor folder inaweza kuwa kubwa (100MB+)
4. **Alternative:** Tuma request kwa hosting provider ku-install package

## 🎯 Alternative: Request Hosting Provider

Mwambie hosting provider:

> "Nahitaji ku-install Composer package kwenye server. Package: kreait/firebase-php
> 
> Command: composer require kreait/firebase-php --ignore-platform-req=ext-sodium --no-dev
> 
> Project path: /home/tendapb8/public_html/api"

## 📝 Checklist

- [ ] Download package kwenye local
- [ ] Zip vendor folders
- [ ] Upload kwenye server
- [ ] Upload credentials file
- [ ] Set permissions
- [ ] Clear caches
- [ ] Test kwenye browser

