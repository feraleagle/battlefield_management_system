# 🦅 Eagle BMS — Battlefield Management System

A hierarchical military command and control web application built as a **Database Systems** university project. Designed to simulate real-world command structures with role-based access, mission management, and secure communication between ranks.

> ⚠️ **Note:** This is an academic project. Known limitations are documented below. It reflects learning outcomes at the time, not current best practices.

---

## 🧠 What It Does

Eagle BMS models a real military hierarchy — from Corps level down to Battalion — where each rank can only see and interact with what their position permits.

- A **General** logs in and gets routed to a high-command dashboard
- A **Brigadier** commanding a Brigade can assign missions to subordinate Battalions
- A **Major** receives missions, updates their status, and communicates up the chain
- Every user only sees their own slice of the hierarchy

---

## ⚙️ Core Features

### 🏛️ Hierarchical Command Structure
The most technically interesting part of the project. A dedicated `hierarchy` table stores `userID → superiorID` relationships, allowing recursive traversal of the command chain. Each user's dashboard dynamically resolves their direct senior and count of direct juniors via subqueries.

```sql
-- Resolve direct senior
SELECT name, rank FROM users 
WHERE cnic = (SELECT superiorID FROM hierarchy WHERE userID = ?);
```

### 🎯 Mission Management
- Assign missions to one or multiple subordinates simultaneously
- Track status: `Not Started → In Progress → On Hold → Completed / Failed`
- Mission titles and descriptions are encrypted before storage
- Full mission history filterable by status

### 🔐 Authentication & Sessions
- CNIC-based login (Pakistani national ID format: `XXXXX-XXXXXXX-X`)
- Passwords hashed with `password_hash()` / verified with `password_verify()`
- Session regeneration on login to prevent session fixation
- Role-based routing — rank determines which dashboard loads

### 🔒 Encryption Attempt
Mission-sensitive data (titles, descriptions) are encrypted before being stored in the database using a custom substitution cipher mapping ASCII characters to Unicode symbols. 

**Acknowledged limitation:** This is obfuscation, not real encryption. A production version would use `openssl_encrypt()` with AES-256-CBC and a proper key management strategy.

### 💬 Messaging
Encrypted chain-of-command messaging — users can only message their direct senior or direct juniors, enforcing the hierarchy at the application layer.

---

## 🗄️ Database Schema
https://github.com/Muhammad-Hassan-Tariq/battlefield_management_system/blob/master/schema.svg

Key tables:

| Table | Purpose |
|---|---|
| `users` | All personnel with rank, CNIC, hashed password |
| `hierarchy` | `userID → superiorID` chain of command |
| `missions` | Mission records with status, assigned by/to |
| `messages` | Chain-of-command messages with timestamps |
| `resources` | Resources allocated per user |
| `intel` | Intelligence reports per user |
| `battalions` / `brigades` / `divisions` / `corps` | Positional units |

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.x |
| Database | MySQL |
| Frontend | HTML, CSS, Vanilla JS |
| Server | Apache / Nginx |

---

## 🚀 Setup

```bash
# Clone the repo
git clone https://github.com/Muhammad-Hassan-Tariq/battlefield_management_system.git
cd battlefield_management_system

# Copy env example and fill in your DB credentials
cp .env.example security/connect_db.php  # edit with your values

# Import the database schema
mysql -u root -p eagle_bms < docs/BMS.sql

# Serve with Apache or PHP built-in server
php -S localhost:8080
```

---

## ⚠️ Known Limitations

This was a learning project. With hindsight, here's what I'd do differently:

| Issue | What I'd do now |
|---|---|
| Raw SQL string interpolation | PDO prepared statements everywhere |
| Custom substitution cipher | `openssl_encrypt()` with AES-256-CBC |
| No CSRF tokens on forms | `bin2hex(random_bytes(32))` token validation |
| No MVC separation | Proper Model/View/Controller structure |
| Sidebar duplicated across all pages | Single `include` partial |
| DB credentials in PHP file | `.env` file with `getenv()` |
| No output escaping in places | `htmlspecialchars()` on all echoed data |

---

**Course:** Database Systems — BS Computer Science

---

## 📄 License

Academic project — not licensed for production use.
