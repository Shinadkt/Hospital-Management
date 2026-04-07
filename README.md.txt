# 🏥 MedLife Hospital Management System

A web-based Hospital Management System developed using **PHP and MySQL** that simplifies appointment booking and healthcare administration through role-based dashboards for **Admin, Doctor, and Patient**.

---

## 🚀 Features

### 👤 Authentication & Security

* Secure login system for Admin, Doctor, and Patient
* Password hashing for data protection
* Session-based access control

### 🧑‍💼 Admin Panel

* Add and manage doctors
* Manage patients and user accounts
* View all appointments
* Dashboard statistics with charts

### 👨‍⚕️ Doctor Panel

* View assigned appointments
* Update appointment status (Scheduled, Completed, Rejected)
* Manage profile information

### 🧑‍🤝‍🧑 Patient Panel

* Register and login securely
* Book appointments with doctors
* Cancel appointments
* View appointment history
* Update profile and password

### 📊 System Features

* Role-based dashboards
* Department-wise doctor organization
* Double-booking prevention
* Responsive UI design
* Data visualization using Chart.js

---

## 🛠️ Technologies Used

* **Backend:** PHP
* **Database:** MySQL
* **Frontend:** HTML, CSS, JavaScript
* **Charts:** Chart.js

---

## 📂 Project Structure

```
/project-root
│
├── index.php
├── login.php
├── signup.php
├── auth.php
├── logout.php
│
├── admin_dashboard.php
├── manage_patients.php
├── manage_user.php
├── add_doctor.php
├── admin_appointments.php
│
├── doctor_dashboard.php
├── doctor_appointment.php
│
├── patient_dashboard.php
├── book_appointments.php
│
├── profile.php
│
├── /includes
│   ├── db_connect.php
│   ├── header.php
│   ├── footer.php
│   ├── sidebar.php
│
└── /images
```

---

## ⚙️ Installation

1. Clone the repository

```
git clone https://github.com/your-username/medlife-hospital-system.git
```

2. Move project folder to your server directory

```
htdocs (XAMPP)
www (WAMP)
```

3. Create database in phpMyAdmin

4. Import SQL file

```
database.sql
```

5. Update database connection

```
includes/db_connect.php
```

6. Start Apache and MySQL

7. Open in browser

```
http://localhost/medlife-hospital-system
```

---

## 🔑 Default Roles

* Admin
* Doctor
* Patient

Admin can create doctor accounts manually.

---

## 🎯 Purpose

This project demonstrates:

* CRUD operations
* Authentication systems
* Role-based access control
* Database relationships
* Backend development using PHP & MySQL

---

## 📸 Screenshots (optional)

Add screenshots here for:

* Login page
* Admin dashboard
* Doctor dashboard
* Patient dashboard

---

## 📄 License

This project is for educational purposes.

---

## 👨‍💻 Author

Developed as part of a learning project for practicing full-stack web development.
