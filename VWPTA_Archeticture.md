VWPTA
======
DVWA/bWAPP (to be like levels or versions of an application) + Hackerone Reports / Bug Bounty Writeups (contains real vulnerabilities and resources will be reports) + Portswigger Labs (in the style of blogs and some ideas of labs) = VWPTA
========================================================================================================================================================================
## 🔐 **VWPTA Structure (Updated with Unique Vulnerabilities)**

### **Main Page [main.php]** ✅

- **Feature**: Landing page with a contact form in footer
- **Vulnerabilities**:
  1. **Blind XSS** → Admin views contact messages → session hijack

------

### **Register [register.php]** ✅

- **Feature**: New user registration
- **Vulnerabilities**:
  1. **Weak Password Policy**
  2. **Account Takeover** → Can re-register using same email ❌
  2. **OTP Bypass** → Predictable/bypassable OTP ❌ => https://hackerone.com/reports/1406471 => Response Manipulation
  3. **Reflected XSS** → Malicious input in firstname reflects in profile

------

### **Login [login.php]** ✅

- **Feature**: User authentication
- **Vulnerabilities**:
  1. **SQL Injection** → Bypass login (admin login possible)
  2. **Brute Force** → No rate limit, weak hashing algorithm

------

### **Reset Password [reset_password.php]** ✅

- **Feature**: Password reset via token/OTP
- **Vulnerabilities**:
  1. **Token Reuse** → Use old token to reset password
  

------

### **Profile [profile.php]** ✅

- **Feature**: Upload avatar, change password, view personal info
- **Vulnerabilities**:
  1. **IDOR** → Edit/Delete other user profiles by changing ID in URL
  2. **Unrestricted File Upload** → Upload `.php` shell → RCE
  3. **Broken Link Hijacking** → Unused/expired external links

------

### **Blog Section** ✅

- **[blog.php]** → Lists all blogs (searchable)
- **[post-view.php]** → Shows blog details, comments, likes/dislikes
- **Vulnerabilities**:
  1. **Stored XSS** → Comment section
  2. **Reflected XSS** → Search bar
  3. **Race Condition** → Abuse like/dislike via concurrent requests ❌

------

### **Food Store Section** ✅

- **[shop.php]** → Lists food items
- **[product-view.php]** → View individual product
- **[cart.php]** → Add/remove items, apply coupons
- **Vulnerabilities**:
  1. **Newsletter Abuse** → Subscribe arbitrary emails
  2. **Local File Inclusion (LFI)** → e.g., `?page=../../etc/passwd` ❌
  3. **Business Logic Flaws**:
     - 4.2 **Promo Code Abuse** (Reuse/stacking)**
     - 4.4 **Tampered Price to 0**

------

### **Admin Dashboard [admin.php]** ✅

- **Feature**: Manage products, users, site-wide content
- **Vulnerabilities**:
  1. **SSTI** → Template injection in product title (e.g., `{{7*7}}`)
  2. Sensitive Data Exposure (/.env , /secret.php)

------

### **Chatbot** ❌

- **Feature**: LLM Chatbot
- **Vulnerabilities**:
    1. **LLM Vulnerabilities**