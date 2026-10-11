# An Interactive Web Portal for Aspiring Entrepreneurs

A PHP and MySQL web portal where aspiring entrepreneurs, agents, and admins can share posts, join discussions, vote on comments, leave feedback, and moderate content.

---

## About

This is a web-based discussion hub for entrepreneurs. Users can browse and comment on posts. Agents can publish new posts after admin approval. Admins manage users, approve pending accounts, and remove flagged content.

The project is built as a classic LAMP stack application (PHP, MySQL, Apache) and is designed to run on **XAMPP**.

---

## Features

- **User registration and login** with hashed passwords (`password_hash` / `password_verify`)
- **Role-based access**
  - **User** — join discussions, vote, update profile, leave feedback
  - **Agent** — same as User, plus create and delete their own posts (account stays pending until an admin activates it)
  - **Admin** — manage users, approve/deactivate accounts, review flagged posts, delete discussions
- **Posts** with title and rich-text description (CKEditor)
- **Threaded discussions** on each post
- **Upvote / downvote** on discussion messages
- **Flag content** for admin review
- **Search** posts by title, description, or author
- **User profiles** with bio, profile picture, and ratings/feedback
- **Profile editing** and image upload

---

## Tech Stack

| Layer        | Technology                                      |
| ------------ | ----------------------------------------------- |
| Backend      | PHP 8.x                                         |
| Database     | MySQL / MariaDB                                 |
| Frontend     | HTML, CSS, Bootstrap 5, Bootstrap Icons         |
| Editor       | CKEditor 4                                      |
| Server       | Apache (XAMPP)                                  |

---

## Project Structure

```
aspiring/
├── index.php                 # Login
├── register.php              # Registration
├── dashboard.php             # Home after login
├── project.php               # Create / manage posts (Agent, Admin)
├── discussion.php            # List posts and search
├── project_discussion.php    # Comments, votes, flags
├── vote.php                  # Upvote / downvote handler
├── users.php                 # Admin user management
├── flagged_posts.php         # Admin flagged-content queue
├── user_profile.php          # Public profile + feedback
├── update_profile.php        # Edit own profile
├── upload.php                # CKEditor image upload
├── logout.php
├── navbar2.php
├── footer.php
├── database.php              # MySQL connection
├── css/
├── uploads/                  # Profile pictures and editor images
└── Database sql file/
    └── aspiring.sql          # Database dump
```

---

## Requirements

- [XAMPP](https://www.apachefriends.org/) (Apache, PHP 8.x, MySQL/MariaDB)
- Web browser
- phpMyAdmin (included with XAMPP)

---

## Installation

### 1. Clone or copy the project

Place the project folder inside XAMPP’s `htdocs` directory:

```text
C:\xampp\htdocs\aspiring
```

If you cloned from GitHub:

```bash
cd C:\xampp\htdocs
git clone <your-repository-url> aspiring
```

### 2. Start Apache and MySQL

Open the **XAMPP Control Panel** and start **Apache** and **MySQL**.

### 3. Create the database

1. Open phpMyAdmin: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Create a database named `aspiring`
3. Import `Database sql file/aspiring.sql`

### 4. Database connection

Defaults in `database.php` (standard XAMPP):

```php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "aspiring";
```

Change these if your MySQL username or password is different.

### 5. Uploads folder

Make sure the `uploads` folder exists and is writable so profile pictures and CKEditor images can be saved.

### 6. Run the app

Open:

```text
http://localhost/aspiring/
```

- Login: `index.php`
- Register: `register.php`

---

## User Roles and Access

| Role  | Registration status | What they can do |
| ----- | ------------------- | ---------------- |
| User  | Active immediately  | Discussions, votes, profile, feedback |
| Agent | **Pending** until admin approval | Create posts after activation |
| Admin | Created in the database | User management and flagged-content moderation |

Agents see this message until an admin sets their status to **active**:

> Your account is pending approval. Please wait for admin confirmation.

An admin can activate, deactivate, or delete users from **Manage Users** (`users.php`).

To create an admin account, insert a user in phpMyAdmin with `role = Admin` and `status = active`, using a password hashed with PHP `password_hash()`.

---

## Screens / Pages

| Page | Purpose |
| ---- | ------- |
| Login | Email and password sign-in |
| Register | Name, email, password, role (User or Agent) |
| Dashboard | Role-based shortcuts |
| Add Post | Create and delete your posts |
| Discussions | Browse and search posts |
| Post discussion | Comments, votes, flags |
| Profile | Edit name, bio, and picture |
| User profile | View another user and leave a rating |
| Users | Admin: list, activate, deactivate, delete |
| Flagged posts | Admin: review and remove reported messages |

---

## License

This project is for academic / personal use. All rights reserved unless you add a license file.

---

## Author

**Usman Younis**  
Email: [usmanyounis7475@gmail.com](mailto:usmanyounis7475@gmail.com)
