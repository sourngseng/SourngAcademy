# University ERP - Database Schema

## 1. Core System & Auth

### `users`
- `id` (bigint, pk)
- `campus_id` (bigint, fk -> campuses.id, nullable for Super Admin)
- `name` (string)
- `email` (string, unique)
- `username` (string, unique, nullable)
- `password` (string)
- `phone` (string, nullable)
- `avatar` (string, nullable)
- `status` (enum: active, inactive, banned)
- `telegram_chat_id` (string, nullable)
- `google_id` (string, nullable)
- `created_at`, `updated_at`, `deleted_at`

### `campuses`
- `id` (bigint, pk)
- `name` (string)
- `code` (string, unique)
- `address` (text)
- `logo` (string, nullable)
- `contact_email` (string)
- `contact_phone` (string)
- `currency` (string, default: "USD")
- `status` (boolean, default: true)
- `created_at`, `updated_at`

### `settings`
- `id` (bigint, pk)
- `campus_id` (bigint, fk -> campuses.id, nullable)
- `key` (string, index)
- `value` (text)
- `type` (string, default: "string")
- `created_at`, `updated_at`

## 2. Academic Structure

### `faculties`
- `id` (bigint, pk)
- `campus_id` (bigint, fk)
- `name` (string)
- `code` (string)
- `dean_name` (string, nullable)
- `created_at`, `updated_at`

### `departments`
- `id` (bigint, pk)
- `faculty_id` (bigint, fk)
- `name` (string)
- `code` (string)
- `head_of_department_id` (bigint, fk -> users.id, nullable)
- `created_at`, `updated_at`

### `programs` (Majors)
- `id` (bigint, pk)
- `department_id` (bigint, fk)
- `name` (string, index)
- `code` (string)
- `degree_level` (enum: Associate, Bachelor, Master, PhD)
- `duration_years` (decimal: 4.0)
- `total_credits` (integer)
- `created_at`, `updated_at`

### `academic_years`
- `id` (bigint, pk)
- `campus_id` (bigint, fk)
- `name` (string)
- `start_date` (date)
- `end_date` (date)
- `status` (boolean, default: true)
- `created_at`, `updated_at`

### `semesters`
- `id` (bigint, pk)
- `academic_year_id` (bigint, fk)
- `name` (string)
- `start_date` (date)
- `end_date` (date)
- `is_active` (boolean, default: false)
- `created_at`, `updated_at`

### `subjects`
- `id` (bigint, pk)
- `program_id` (bigint, fk, nullable - meant for general subjects)
- `code` (string, unique)
- `name` (string)
- `description` (text, nullable)
- `credit` (integer)
- `type` (enum: Core, Elective, General)
- `created_at`, `updated_at`

### `classrooms`
- `id` (bigint, pk)
- `campus_id` (bigint, fk)
- `room_number` (string)
- `capacity` (integer)
- `type` (enum: Lecture Hall, Lab, Classroom, Online)
- `created_at`, `updated_at`

### `classes` (Sections)
- `id` (bigint, pk)
- `campus_id` (bigint, fk)
- `subject_id` (bigint, fk)
- `teacher_id` (bigint, fk -> users.id)
- `semester_id` (bigint, fk)
- `classroom_id` (bigint, fk)
- `name` (string, e.g., "Group A")
- `schedule_json` (json)
- `max_students` (integer)
- `status` (enum: Scheduled, Ongoing, Completed, Cancelled)
- `created_at`, `updated_at`

### `enrollments`
- `id` (bigint, pk)
- `student_id` (bigint, fk -> users.id)
- `class_id` (bigint, fk)
- `status` (enum: Enrolled, Dropped, Completed, Failed)
- `enrolled_at` (datetime)
- `grade_points` (decimal, nullable)
- `grade_letter` (string, nullable)
- `created_at`, `updated_at`

## 3. Attendance System

### `attendance_sessions`
- `id` (bigint, pk)
- `class_id` (bigint, fk)
- `teacher_id` (bigint, fk -> users.id)
- `date` (date)
- `start_time` (time)
- `end_time` (time)
- `qr_code_hash` (string, unique)
- `status` (enum: Open, Closed)
- `created_at`, `updated_at`

### `attendances`
- `id` (bigint, pk)
- `session_id` (bigint, fk)
- `student_id` (bigint, fk -> users.id)
- `status` (enum: Present, Late, Absent, Excused)
- `check_in_time` (datetime)
- `location_lat` (decimal, nullable)
- `location_long` (decimal, nullable)
- `device_info` (string, nullable)
- `created_at`, `updated_at`

## 4. Finance & Fee Management

### `fee_structures`
- `id` (bigint, pk)
- `program_id` (bigint, fk)
- `academic_year_id` (bigint, fk)
- `semester_id` (bigint, fk, nullable)
- `name` (string)
- `amount` (decimal, 10, 2)
- `currency` (string, default: "USD")
- `type` (enum: Tuition, Registration, Lab, Library, Late Fee)
- `description` (text, nullable)
- `created_at`, `updated_at`

### `discounts`
- `id` (bigint, pk)
- `campus_id` (bigint, fk)
- `name` (string)
- `type` (enum: Percentage, Fixed)
- `value` (decimal, 10, 2)
- `code` (string, unique, nullable)
- `start_date` (date, nullable)
- `end_date` (date, nullable)
- `applies_to` (enum: Tuition, Registration, All)
- `created_at`, `updated_at`

### `scholarships`
- `id` (bigint, pk)
- `campus_id` (bigint, fk)
- `name` (string)
- `provider` (string, nullable)
- `percentage` (decimal, 5, 2)
- `min_gpa_renewal` (decimal, 3, 2, nullable)
- `description` (text, nullable)
- `created_at`, `updated_at`

### `student_financial_profiles`
- `id` (bigint, pk)
- `student_id` (bigint, fk -> users.id)
- `scholarship_id` (bigint, fk, nullable)
- `discount_id` (bigint, fk, nullable)
- `vip_category_id` (bigint, fk, nullable)
- `corporate_contract_id` (bigint, fk, nullable)
- `wallet_balance` (decimal, 10, 2, default: 0)
- `created_at`, `updated_at`

### `invoices`
- `id` (bigint, pk)
- `campus_id` (bigint, fk)
- `student_id` (bigint, fk -> users.id)
- `invoice_number` (string, unique)
- `total_amount` (decimal, 10, 2)
- `paid_amount` (decimal, 10, 2, default: 0)
- `due_date` (date)
- `status` (enum: Unpaid, Partially Paid, Paid, Overdue, Cancelled)
- `generated_by` (bigint, fk -> users.id, nullable)
- `created_at`, `updated_at`

### `invoice_items`
- `id` (bigint, pk)
- `invoice_id` (bigint, fk)
- `fee_structure_id` (bigint, fk, nullable)
- `description` (string)
- `amount` (decimal, 10, 2)
- `created_at`, `updated_at`

### `payments`
- `id` (bigint, pk)
- `invoice_id` (bigint, fk)
- `student_id` (bigint, fk -> users.id)
- `amount` (decimal, 10, 2)
- `payment_method` (enum: Cash, Bank Transfer, KHQR, Stripe)
- `transaction_reference` (string, nullable)
- `proof_image` (string, nullable)
- `status` (enum: Pending, Verified, Rejected)
- `verified_by` (bigint, fk -> users.id, nullable)
- `verified_at` (datetime, nullable)
- `created_at`, `updated_at`

## 5. Corporate & VIP

### `companies`
- `id` (bigint, pk)
- `name` (string)
- `contact_person` (string)
- `email` (string)
- `phone` (string)
- `address` (text, nullable)
- `logo` (string, nullable)
- `created_at`, `updated_at`

### `corporate_contracts`
- `id` (bigint, pk)
- `company_id` (bigint, fk)
- `campus_id` (bigint, fk)
- `name` (string)
- `discount_percentage` (decimal, 5, 2)
- `max_students` (integer, nullable)
- `start_date` (date)
- `end_date` (date)
- `status` (enum: Active, Expired)
- `created_at`, `updated_at`

