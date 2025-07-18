# 💆‍♀️ CreativeSpa - Spa Management System

**CreativeSpa** is a full-featured, branch-wise spa management system built using Laravel. It helps spa businesses manage their day-to-day operations including customers, therapists, receipts, expenses, savings, and more — all with role-based access (Admin & Receptionist).

---

## 👥 User Roles

- **Admin** – Has full control over the system and all branches.
- **Receptionist** – Restricted to their assigned branch with limited permissions.

---

## 🖥️ Admin Panel Features

### 📊 Dashboard
- View overall **sales**, **expenses**, and **revenue** (calculated as: `sales - expenses`)
- Overview of key metrics like branches, therapists, receipts, etc.

### 1. 🏢 Branch Management
- Add/edit/delete spa branches.

### 2. 👩‍💼 User Management
- Manage receptionists assigned to branches.

### 3. 🧖 Therapist Management
- Add/edit therapists per branch.

### 4. 👥 Customer Management
- Manage branch-wise customers.
- Import/export customers via Excel.

### 5. 🧾 Receipt Management
- Create receipts using:
  - Customer (new or existing)
  - Service type: **Therapy** or **Package**
  - Payment type: **COD** or **Online**
- Assign therapist and time tracking.

### 6. 💸 Expense Management
- Record branch-wise expenses.

### 7. 🏦 Savings Management
- Add savings per branch.

### 8. 📞 Telecaller Module
- Add telecaller data manually or via Excel import.

### 9. 🔐 Roles Management
- Create user roles (e.g., Admin, Receptionist, etc.)

### 10. 📋 Permission Management
- Assign specific permissions to roles.

### 11. 🧖 Therapy Module
- **Therapy Management:** Add/edit therapies with pricing.
- **Therapy Usage:** Track therapy usage by customers.

### 12. 🎁 Package Module
- **Package Management:** Create packages with multiple therapies.
- **Package Usage:** Monitor package usage and remaining sessions.

### 13. 📊 Reports
- **Branch Report:** View data per branch.
- **Sales Summary:** Total receipts and amounts.
- **Customer Report:** Total and active customers (last 30 days).
- **Package Report:** Purchased & expired packages.
- **Appointment Report:** Receipt date-based appointments.

---

## 💼 Receptionist Panel Features

1. **Add Customer** – Limited to their branch.
2. **Add Receipt** – Limited to their branch.
3. **Add Expense** – Limited to their branch.
4. **Add Savings** – Limited to their branch.
5. **Telecaller** – Can call customers only (masked data if restricted).
6. **Package Usage** – View package usage for branch customers.

---

## 🧰 Tech Stack

| Layer        | Technology         |
|--------------|--------------------|
| Backend      | Laravel (MVC)      |
| Frontend     | Vuexy Theme
| Database     | MySQL              |
| Interactivity| AJAX               |
| Import/Export| Laravel Excel      |

---


