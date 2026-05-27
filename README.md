# 🏠 Hostel Harmony System

> A comprehensive web-based hostel management platform built with PHP & MySQL

![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-10.4-orange?logo=mysql)
![HTML/CSS](https://img.shields.io/badge/Frontend-HTML%2FCSS-green)
![License](https://img.shields.io/badge/License-Academic-purple)

---

## 📌 Table of Contents

- [About the Project](#about-the-project)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Database Structure](#database-structure)
- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Screenshots](#screenshots)
- [Team](#team)

---

## 📖 About the Project

**Hostel Harmony System** is a web-based application developed to simplify and automate hostel management operations. It replaces traditional paper-based methods with a centralized digital solution that handles room allocation, fee management, complaint resolution, and communication between residents and administrators.

> Developed as a TYBSc Computer Science project at **Modern College of Art, Commerce and Science, Ganeshkhind, Pune-16 (Autonomous)** for the academic year **2024-2025**, under **Savitribai Phule Pune University**.

---

## ✨ Features

### 👨‍💼 Admin Panel
- Secure admin login
- Register new students with room and payment details
- Manage rooms (add, view, update availability)
- View all registered students and payment status
- Mark student payments as paid/unpaid
- View and resolve student complaints
- Dashboard with total rooms and student count

### 🎓 Student Portal
- Student registration and login
- Book available rooms with payment
- File complaints (confidential)
- View personal room details
- Dashboard with personalized greeting

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML, CSS, JavaScript |
| Backend | PHP 8.2 |
| Database | MySQL (MariaDB 10.4) |
| Server | Apache (XAMPP) |
| Fonts & Icons | Google Fonts (Poppins), Font Awesome |

---

## 🗄 Database Structure

The system uses the `hostel_management` database with the following tables:

### `students`
| Column | Type | Description |
|---|---|---|
| id | INT (PK) | Auto-increment |
| name | VARCHAR(100) | Student name |
| email | VARCHAR(100) | Unique email |
| password | VARCHAR(255) | Hashed password |
| room_number | VARCHAR(10) | Assigned room |
| payment_status | ENUM | `unpaid` / `paid` |
| created_at | TIMESTAMP | Registration time |

### `rooms`
| Column | Type | Description |
|---|---|---|
| id | INT (PK) | Auto-increment |
| room_number | VARCHAR(10) | Unique room number |
| type | ENUM | `single` / `double` |
| fee | DECIMAL(10,2) | Fee per semester |
| status | ENUM | `available` / `booked` |

### `payments`
| Column | Type | Description |
|---|---|---|
| id | INT (PK) | Auto-increment |
| student_id | INT (FK) | References students.id |
| amount | DECIMAL(10,2) | Payment amount |
| payment_method | VARCHAR(50) | Cash / UPI / Card etc. |
| status | ENUM | `unpaid` / `paid` |
| payment_date | TIMESTAMP | Payment time |

### `complaints`
| Column | Type | Description |
|---|---|---|
| id | INT (PK) | Auto-increment |
| student_id | INT (FK) | References students.id |
| complaint | TEXT | Complaint description |
| status | ENUM | `pending` / `resolved` |
| created_at | TIMESTAMP | Submission time |

### `admins`
| Column | Type | Description |
|---|---|---|
| id | INT (PK) | Auto-increment |
| username | VARCHAR(100) | Unique username |
| password | VARCHAR(255) | Admin password |

---

## ⚙️ Installation

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP)
- Web browser (Chrome, Firefox)

### Steps

**1. Clone or download the project**
```bash
git clone https://github.com/mansi153-wq/Hostel_Harmony_System.git
```
Or download the ZIP and extract it.

**2. Move to XAMPP's web directory**
```
Copy the project folder to: C:\xampp\htdocs\project_new\
```

**3. Start XAMPP**
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**

**4. Import the database**
- Open [phpMyAdmin](http://localhost/phpmyadmin)
- Create a new database named `hostel_management`
- Import the provided SQL file

Or run this SQL manually:
```sql
CREATE DATABASE hostel_management;

-- Then run the table creation queries from the sql/ folder
```

**5. Configure database connection**

Edit `includes/db.php`:
```php
$host = 'localhost';
$db   = 'hostel_management';
$user = 'root';
$pass = 'your_password';
```

**6. Open in browser**
```
http://localhost/project_new/
```

---

## 🚀 Usage

### Admin Login
```
URL: http://localhost/project_new/admin/login.php
Username: admin
Password: admin123
```

### Student Login
```
URL: http://localhost/project_new/student/login.php
Use email and password registered by admin
```

### Workflow
```
Admin → Register Student → Assign Room → Process Payment
Student → Login → Book Room → File Complaint → View Room Details
```

---

## 📁 Project Structure

```
project_new/
│
├── includes/
│   └── db.php                  # Database connection
│
├── admin/
│   ├── ind.php                 # Admin dashboard
│   ├── register_student.php    # Register new student
│   ├── view_students.php       # View all students
│   ├── manage_rooms.php        # Room management
│   ├── view_complaints.php     # View complaints
│   └── update_payment_status.php
│
├── student/
│   ├── index.php               # Student dashboard
│   ├── book_room.php           # Room booking
│   ├── file_complaint.php      # Submit complaint
│   └── room_details.php        # View room info
│
├── logout.php                  # Session logout
└── index.php                   # Landing page
```

---

## 👥 Team

| Name | Roll No. |
|---|---|
| Shree Nigade | 2433321078 |
| Ashish Ranmale | 2433321094 |
| Mansi Kawale | 2433321051 |

**Project Guide:** Prof. Prerana Sarode

**Institution:** Modern College of Art, Commerce and Science, Ganeshkhind, Pune-16 (Autonomous)

**University:** Savitribai Phule Pune University

---

## 🔮 Future Scope

- Real-time notifications via email/SMS
- Predictive maintenance alerts
- Mobile-responsive UI
- Payment gateway integration (Razorpay / PayU)
- Attendance and visitor management module
- Enhanced security with 2FA

---

## 📚 References

- [W3Schools PHP](https://www.w3schools.com/php/)
- [GeeksforGeeks MySQL](https://www.geeksforgeeks.org/python-mysql-create-database)
- [GitHub - Hostel Management Reference](https://github.com/mda1458/hostel-management-system-php-mysql)

---

<p align="center">© 2025 Hostel Harmony System · Modern College, Pune</p>
