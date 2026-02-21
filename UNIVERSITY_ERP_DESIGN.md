# University Multi-Campus ERP System Design

## 1. System Architecture Explanation

The system follows a modern **Modular Monolith** architecture, transitioning towards microservices in the future.

### **Backend: Laravel (Modular Service-Oriented)**
- **Modules**: Core functionality is split into self-contained modules (`Academic`, `Finance`, `Users`, `Attendance`).
- **Service Layer**: Business logic resides in `Services`, not Controllers.
- **Repository Pattern**: Data access is abstracted via repositories for flexibility.
- **API First**: All communication is via RESTful APIs consumed by the frontend and mobile apps.
- **Authentication**: Laravel Sanctum for API token authentication.
- **RBAC**: Spatie Permission (or custom robust implementation) for role management.
- **Queues & Events**: Redis/Database driven queues for notifications, report generation, and heavy processing.

### **Frontend: Vue.js (SPA)**
- **State Management**: Pinia for managing global state (User session, cart, settings).
- **Routing**: Vue Router with role-based navigation guards.
- **UI Framework**: PrimeVue + TailwindCSS for a premium, responsive design.
- **Internationalization (i18n)**: `vue-i18n` for multi-language support (English, Khmer).

### **Infrastructure**
- **Cloud-Ready**: Dockerized environment, AWS S3 for storage.
- **Scalability**: Horizontal scaling via load balancers.
- **Database**: MySQL 8.0+ with proper indexing and foreign keys.

---

## 2. Database Schema (Core Tables)

### **I. User Management & RBAC**
- `users`: id, name, email, password, phone, avatar, campus_id, status
- `roles`: id, name, guard_name
- `permissions`: id, name, guard_name
- `model_has_roles`: user_id, role_id
- `user_profiles`: user_id, dob, address, gender, nationality, emergency_contact

### **II. Multi-Campus Structure**
- `campuses`: id, name, code, address, logo, contact_info, status
- `faculties`: id, campus_id, name, code
- `departments`: id, faculty_id, name, head_of_department_id
- `programs`: id, department_id, name, degree_level (Bachelor, Master), duration_years

### **III. Academic Structure**
- `academic_years`: id, name (2025-2026), start_date, end_date, status
- `semesters`: id, academic_year_id, name (Sem 1), start_date, end_date
- `subjects`: id, program_id, code, name, credit, type (Core/Elective)
- `classrooms`: id, campus_id, room_number, capacity, type (Lab, Lecture Hall)
- `classes`: id, subject_id, teacher_id, semester_id, classroom_id, schedule_json
- `enrollments`: id, student_id, class_id, status (Active, Dropped, Completed), grade

### **IV. Attendance System**
- `attendance_sessions`: id, class_id, teacher_id, date, start_time, end_time, qr_code_hash, status (Open, Closed)
- `attendances`: id, session_id, student_id, status (Present, Late, Absent, Excused), check_in_time, location_lat, location_long

### **V. Finance & Fee Management**
- `fee_structures`: id, program_id, academic_year_id, name, amount, currency, type (Tuition, Lab, Reg)
- `discounts`: id, name, type (Percentage, Fixed), value, start_date, end_date
- `scholarships`: id, name, provider, percentage, conditions (Min GPA)
- `student_scholarships`: id, student_id, scholarship_id, status, grant_date
- `invoices`: id, student_id, invoice_number, total_amount, due_date, status (Unpaid, Partial, Paid)
- `invoice_items`: id, invoice_id, description, amount
- `payments`: id, invoice_id, amount, method (Cash, Bank, KHQR), transaction_id, status, verified_by
- `wallets`: id, user_id, balance, currency

### **VI. Corporate Billing**
- `companies`: id, name, contact_person, email, contract_start, contract_end
- `corporate_contracts`: id, company_id, discount_percentage, max_students
- `corporate_enrollments`: id, company_id, student_id, contract_id

---

## 3. ERD Explanation

The database centers around the `users` table, which links to `campuses`.
- **Academic Hierarchy**: `Campuses` -> `Faculties` -> `Departments` -> `Programs` -> `Subjects` -> `Classes`.
- **Time Dimension**: `Academic Years` -> `Semesters` governs when `Classes` happen.
- **Participation**: `Students` (Users) enroll in `Classes` via `Enrollments`.
- **Attendance**: `Attendance Sessions` are created for `Classes`, and `Attendances` record individual student presence.
- **Finance**: `Fee Structures` define costs. `Invoices` are generated for `Students`. `Payments` settle `Invoices`. `Scholarships` and `Discounts` modify `Invoice` amounts. `Wallets` handle overpayments/refunds.

---

## 4. API Route Structure

### **Public**
- `POST /api/login` (Standard Login)
- `POST /api/login/google` (OAuth)
- `POST /api/login/telegram` (Telegram Auth)
- `GET /api/public/programs`

### **Protected (Sanctum Auth)**
- `GET /api/user/profile`
- `POST /api/user/fcm-token` (Mobile Push)

#### **Admin Routes** (`/api/admin`)
- `apiResources` for users, campuses, programs, financial-reports, settings.

#### **Teacher Routes** (`/api/teacher`)
- `GET /classes`
- `POST /classes/{id}/attendance-session` (Generate QR)
- `POST /classes/{id}/scores`
- `GET /schedule`

#### **Student Routes** (`/api/student`)
- `GET /feed` (Announcements)
- `GET /grades`
- `GET /classes`
- `POST /attendance/scan` (Scan QR)
- `GET /finance/invoices`
- `POST /finance/pay` (Initiate Payment)

---

## 5. Role Permission Matrix

| Module | Super Admin | Campus Admin | Staff | Teacher | Student |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Manage Campuses** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Manage Users** | ✅ | ✅ (Own Campus) | ⚠️ (Limited) | ❌ | ❌ |
| **Financials** | ✅ | ✅ (View Only) | ✅ (Process) | ❌ | ✅ (Own) |
| **Academics** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Class Mgmt** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Grading** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Attendance** | ✅ | ✅ | ✅ | ✅ (Create) | ✅ (Scan) |

---

## 6. Module Breakdown

1. **Auth Module**: Login, Register, 2FA, OAuth.
2. **Core Module**: Campus, Settings, Languages.
3. **User Module**: Admin, Staff, Teacher, Student profiles.
4. **Academic Module**: Curriculum management, Classes, Scheduling.
5. **Enrollment Module**: Student course registration.
6. **Attendance Module**: QR Code generation, scanning, validation, reporting.
7. **Examination Module**: Scores, GPA, Transcripts.
8. **Finance Module**: Invoices, Payments, Wallets, Reports.
9. **Discount/Scholarship Module**: Management and application logic.
10. **Corporate Module**: B2B contracts and billing.
11. **Communication Module**: Notifications, Telegram integration.
12. **Reporting Module**: Financial, Academic, Attendance analytics.

---

## 7. Folder Structure

### **Laravel**
```
app/
├── Modules/                  # Modular Structure
│   ├── Academic/
│   │   ├── Controllers/
│   │   ├── Models/
│   │   ├── Routes/
│   │   └── Services/
│   ├── Finance/
│   └── User/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Services/                 # Shared Services
│   ├── PaymentGateway/
│   ├── QrCodeService.php
│   └── TelegramService.php
```

### **Vue**
```
src/
├── api/                      # API Connectors
├── components/               # Reusable UI
│   ├── common/
│   ├── finance/
│   └── academic/
├── layouts/                  # App Layouts
├── modules/                  # Frontend Modules (matches Backend)
│   ├── academic/
│   ├── finance/
│   └── student-portal/
├── router/
├── stores/                   # Pinia Stores
└── views/                    # Page Views
```

---

## 8. QR Attendance Workflow

1. **Teacher**: Opens App > Selects Class > Clicks "Start Attendance".
2. **System**: Generates a unique, time-limited token (JWT) embedding Session ID + Timestamp.
3. **Display**: Shows QR Code (Dynamic, refreshes every 10s to prevent photo-sharing).
4. **Student**: Opens App > Scans QR.
5. **Validation**: Server checks:
   - Is Token Valid?
   - Is Student Enrolled?
   - Is Student physically close? (Optional GPS check).
   - Is Session Open?
6. **Success**: Records attendance > Push Notification to Student "Checked In".

---

## 9. Telegram Integration Flow

- **Bot Setup**: Admin adds Bot Token in Settings.
- **Linking**: User (Student/Teacher) clicks "Link Telegram" in Profile > Redirects to Bot > Start.
- **Webhook**: Telegram sends `chat_id` to Laravel webhook > System maps `chat_id` to User.
- **Notifications**:
  - **Grades Released**: "Hello [Name], you got an A in Math."
  - **Payment Received**: "Receipt #1234 PAIF."
  - **Attendance Warning**: "You missed 3 classes."
- **Commands**: `/schedule`, `/grades`, `/balance`.

---

## 10. Finance Calculation Logic

- **Invoice Generation**:
  `Total = (CreditHours * CostPerCredit + FixedFees) - (ScholarshipAmount + DiscountAmount)`
- **Late Fees**:
  Cron Job runs daily > Checks `due_date` > If unpaid & `date > due_date` > Add Late Fee (Fixed or %).
- **Prorated Refunds**: Based on policy (e.g., Drop before Week 2 = 80%).

---

## 11. Scalability & SaaS Expansion Plan

- **Multi-Tenancy (Future)**: Use `campus_id` broadly or move to separate Schemas per University for true SaaS.
- **Caching**: Redis for config, permissions, and course lists.
- **Database**: Read Replicas for standard SELECT queries (Reporting).
- **Storage**: Move from local `storage/` to AWS S3 / MinIO.

---

## 12. Security Best Practices

- **Sanctum Tokens**: Short-lived access tokens, Refresh tokens.
- **RBAC Middleware**: Strict checks on every route.
- **Audit Logs`: Log every CREATE, UPDATE, DELETE on financial records.
- **Input Sanitization**: Standard Laravel validation.
- **Rate Limiting**: Implementation on Login and API endpoints.

---

## 13. Future Upgrade Roadmap

- **Phase 1 (MVP)**: Core Academic, Enrollment, Basic Finance, QR Attendance.
- **Phase 2**: Advanced Reports, Telegram Full Integration, Corporate Module.
- **Phase 3**: Mobile App (Flutter/React Native), AI Analytics (Dropout prediction).
