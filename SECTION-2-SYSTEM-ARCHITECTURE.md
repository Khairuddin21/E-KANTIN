# SECTION 2 — SYSTEM ARCHITECTURE PLAN

## E-Canteen Pre-Order System: Complete Architecture Design

This document defines the complete system architecture for the E-Canteen Pre-Order System. It is derived from the foundational project understanding established in Section 1 and serves as the technical blueprint for developers building the platform.

**Technology Stack:**
| Layer | Technology |
|---|---|
| Backend Framework | Laravel 12 (PHP 8.2+) |
| Database | MySQL 8.0+ |
| Cache & Queue | Redis 7+ |
| Web Server | Nginx on Linux (Ubuntu 22.04+) |
| Frontend | Blade + Tailwind CSS 4 + Livewire 3 |
| Real-Time | Laravel Echo + Pusher/Soketi (WebSocket) |
| Payment Gateway | Midtrans / Xendit / Tripay (QRIS) |
| QR Code | `simplesoftwareio/simple-qrcode` + `html5-qrcode` (JS scanner) |
| File Storage | Local disk (development), S3-compatible (production) |
| Task Scheduling | Laravel Scheduler via cron |
| Background Jobs | Laravel Queue with Redis driver |

---

## 1. SYSTEM OVERVIEW

### 1.1 High-Level Architecture Diagram (Textual)

```
┌──────────────────────────────────────────────────────────────────────┐
│                          CLIENT LAYER                                │
│                                                                      │
│   ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────────────┐   │
│   │ Student  │  │ Teacher  │  │  Seller  │  │ Cashier / Admin  │   │
│   │ Browser  │  │ Browser  │  │ Browser  │  │    Browser       │   │
│   └────┬─────┘  └────┬─────┘  └────┬─────┘  └───────┬──────────┘   │
│        │              │              │                │              │
└────────┼──────────────┼──────────────┼────────────────┼──────────────┘
         │              │              │                │
         ▼              ▼              ▼                ▼
┌──────────────────────────────────────────────────────────────────────┐
│                        WEB SERVER LAYER                              │
│                                                                      │
│   ┌──────────────────────────────────────────────────────────────┐   │
│   │                    Nginx (Reverse Proxy)                     │   │
│   │            SSL Termination · Rate Limiting · Gzip            │   │
│   └──────────────────────────┬───────────────────────────────────┘   │
│                              │                                       │
│   ┌──────────────────────────▼───────────────────────────────────┐   │
│   │                PHP-FPM (Laravel Application)                 │   │
│   │                                                              │   │
│   │  ┌─────────┐ ┌──────────┐ ┌──────────┐ ┌────────────────┐  │   │
│   │  │ Routes  │ │Middleware│ │Controller│ │  Service Layer  │  │   │
│   │  │         │ │ (Auth,   │ │          │ │  (Business      │  │   │
│   │  │ web.php │ │  RBAC,   │ │  HTTP    │ │   Logic)        │  │   │
│   │  │ api.php │ │  CORS,   │ │  Request │ │                 │  │   │
│   │  │         │ │  Throttle│ │  Response│ │  OrderService   │  │   │
│   │  └─────────┘ └──────────┘ └──────────┘ │  PaymentService │  │   │
│   │                                         │  CartService    │  │   │
│   │                                         │  QueueService   │  │   │
│   │                                         │  BalanceService │  │   │
│   │                                         └────────────────┘  │   │
│   └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
└──────────┬──────────────┬──────────────┬─────────────────────────────┘
           │              │              │
           ▼              ▼              ▼
┌─────────────────┐ ┌──────────┐ ┌────────────────────┐
│    MySQL 8.0    │ │  Redis   │ │  Payment Gateway   │
│                 │ │          │ │  (Midtrans/Xendit)  │
│  Users          │ │ Cache    │ │                     │
│  Orders         │ │ Sessions │ │  QRIS Generation    │
│  Payments       │ │ Queues   │ │  Webhook Callback   │
│  Menus          │ │ Locks    │ │                     │
│  Transactions   │ │ Pub/Sub  │ │                     │
└─────────────────┘ └──────────┘ └────────────────────┘
```

### 1.2 Request Flow — From Login to Order Pickup

The complete interaction cycle operates as follows:

1. **User opens the application** in their browser. Nginx receives the HTTPS request, terminates SSL, and proxies the request to PHP-FPM.

2. **Laravel routes** the request through middleware (session validation, CSRF protection) to the appropriate controller. Unauthenticated users are redirected to the login page.

3. **User authenticates** using email/username + password. Laravel's authentication guard validates credentials against the `users` table. Upon success, a session is created (stored in Redis for performance) and the user is redirected to their role-specific dashboard:
   - Student/Teacher → Customer storefront
   - Seller → Kitchen dashboard
   - Cashier → Pickup scanner interface
   - Super Admin → Admin panel

4. **Customer browses the menu.** The `MenuController` queries `stalls`, `categories`, and `menu_items` tables via Eloquent, filtered by `is_available = true`. Results are cached in Redis (cache key per stall, invalidated on menu updates). The Blade view renders the menu with Tailwind CSS responsive layout.

5. **Customer adds items to cart.** The `CartController` stores cart data in the database (`carts` and `cart_items` tables) tied to the authenticated user. This ensures cart persistence across devices and sessions. Each add/update/remove action is a standard HTTP request processed by Livewire components for instant UI feedback.

6. **Customer selects a pickup time slot.** The `PickupSlotController` queries the `pickup_slots` table, checks current order counts against capacity limits, and enforces cutoff time logic. Available slots are displayed; full or expired slots are disabled.

7. **Customer proceeds to checkout.** The `CheckoutController` validates cart contents (items still available, prices unchanged, slot still open), calculates the final total, and displays the payment method selection page.

8. **Customer pays.** Two paths:
   - **Virtual Balance:** `BalanceService` verifies sufficient funds, wraps the deduction and order creation in a database transaction. Order status becomes `paid` immediately.
   - **QRIS/Gateway:** `PaymentService` calls the payment gateway API to create a payment request. The gateway returns a QRIS image/URL. Order status is `pending`. When the user completes payment externally, the gateway sends a webhook to `/api/webhooks/payment`. The `PaymentWebhookController` validates the signature, verifies the payload, and transitions the order to `paid` via `OrderService`.

9. **Order enters the kitchen queue.** Upon `paid` status, a `OrderPaid` event is dispatched. The `NotifySellerListener` sends a real-time notification (via Laravel Echo/WebSocket) to the seller's dashboard. The order appears in the kitchen queue, sorted by pickup slot and creation time.

10. **Seller prepares the order.** The seller views the queue on their dashboard (`KitchenController`), marks orders as `preparing`, then `ready`. Each status change dispatches an event that updates the customer's order tracking view in real time.

11. **Customer arrives for pickup.** The customer opens their order detail page, which displays a QR code containing the order's unique pickup code (a signed, non-guessable token). The cashier's interface (`PickupController`) accesses the device camera via `html5-qrcode` JS library, scans the QR code, and sends the code to the backend for validation.

12. **Cashier confirms pickup.** The backend verifies the pickup code, confirms the order is in `ready` status, transitions it to `completed`, records the pickup timestamp and cashier ID. The transaction is finalized.

13. **Data flows into reporting.** All completed orders feed the `ReportingService`, which aggregates data for seller dashboards (daily sales), admin dashboards (cross-stall analytics), and exportable reports.

---

## 2. USER ROLES — DEFINITIONS, RESPONSIBILITIES, AND PERMISSIONS

### 2.1 Role Architecture

Roles are implemented using a polymorphic role-permission system stored in the database. Each user has exactly one role. Permissions are assigned to roles, and middleware checks permissions on every request.

```
┌─────────────────────────────────────────────────────────┐
│                    ROLE HIERARCHY                        │
│                                                         │
│   Super Admin ──────── Full system access               │
│       │                                                 │
│       ├── Canteen Seller ── Menu + Kitchen + Reports    │
│       │                                                 │
│       ├── Cashier ────────── Pickup scanning only       │
│       │                                                 │
│       ├── Student ────────── Order + Pay + Track        │
│       │                                                 │
│       └── Teacher ────────── Order + Pay + Track        │
│                              (extended privileges)      │
└─────────────────────────────────────────────────────────┘
```

### 2.2 Super Admin

**Identity:** School IT administrator or system operator.

**Responsibilities:**
- Manage the entire user base: create, edit, deactivate, reset passwords for all roles
- Register and manage canteen stalls: create stalls, assign/reassign sellers, deactivate stalls
- Configure system settings: pickup time slots, cutoff durations, capacity limits per slot, operating days, school calendar integration
- Configure payment gateway: API keys, webhook URLs, enable/disable payment methods
- Manage the virtual balance system: process manual top-ups, approve bulk top-ups, view all balance ledgers
- Access all financial reports: revenue by stall, revenue by time period, transaction logs, payment method breakdown, refund records
- Monitor system health: queue backlogs, failed jobs, error logs
- Manage announcements and notifications pushed to all users

**Permissions (Complete List):**
| Permission Key | Description |
|---|---|
| `users.manage` | Create, edit, deactivate any user |
| `roles.assign` | Assign roles to users |
| `stalls.manage` | Create, edit, delete canteen stalls |
| `settings.manage` | Modify system-wide configuration |
| `payment.configure` | Set payment gateway credentials |
| `balance.topup` | Process virtual balance top-ups |
| `balance.view_all` | View any user's balance history |
| `reports.view_all` | Access all reports across all stalls |
| `orders.view_all` | View any order in the system |
| `orders.cancel_any` | Cancel any order (with refund) |
| `notifications.broadcast` | Send system-wide notifications |

### 2.3 Canteen Seller (Kantin Admin / Penjual)

**Identity:** Canteen stall owner or staff assigned to manage a specific stall.

**Responsibilities:**
- Manage menu items: create, update, delete, toggle availability, set prices, upload images, assign categories
- Manage menu categories for their stall
- Process incoming orders: view kitchen queue, mark orders as `preparing`, mark as `ready`
- View aggregated item preparation list per pickup slot
- View their stall's sales reports: daily/weekly/monthly revenue, item-level analytics, order volume trends
- Manage stall profile: name, description, logo, operating hours

**Permissions:**
| Permission Key | Description |
|---|---|
| `menu.manage_own` | CRUD menu items for own stall only |
| `categories.manage_own` | CRUD categories for own stall only |
| `orders.view_own` | View orders for own stall |
| `orders.update_status` | Change order status (preparing/ready) |
| `reports.view_own` | View own stall's sales reports |
| `stall.edit_profile` | Edit own stall profile |

**Data Isolation:** Sellers can ONLY access data associated with the stall(s) they are assigned to. All queries are scoped by `stall_id`. A global query scope on the Eloquent model enforces this automatically.

### 2.4 Cashier (Kasir)

**Identity:** Staff member stationed at the canteen pickup counter.

**Responsibilities:**
- Scan customer QR codes to identify orders
- Verify order status is `ready` before confirming pickup
- Mark orders as `completed` upon successful pickup
- View a shift log of all pickups they have confirmed
- Flag issues (e.g., customer claims order is wrong)

**Permissions:**
| Permission Key | Description |
|---|---|
| `pickup.scan` | Access QR scanning interface |
| `pickup.confirm` | Mark orders as completed |
| `pickup.view_log` | View own shift pickup history |

**Interface Design:** The cashier interface is intentionally minimal — a full-screen camera viewfinder for scanning, with order details displayed as an overlay upon successful scan, and a single "Confirm Pickup" button. No navigation to other modules.

### 2.5 Student (Siswa)

**Identity:** Enrolled student at the school.

**Responsibilities:**
- Browse canteen menus across all active stalls
- Add items to cart, manage cart (update quantities, remove items, add notes)
- Select pickup time slot
- Pay using virtual balance or QRIS
- Track order status in real time
- View order history and receipts
- View virtual balance and transaction history

**Permissions:**
| Permission Key | Description |
|---|---|
| `menu.browse` | View all active menus |
| `cart.manage` | Add, update, remove cart items |
| `orders.create` | Place new orders |
| `orders.view_own` | View own orders only |
| `orders.cancel_own` | Cancel own pending orders |
| `balance.view_own` | View own balance and history |
| `payment.make` | Initiate payments |

### 2.6 Teacher (Guru)

**Identity:** Teaching staff at the school.

**Permissions:** Identical to Student, plus:
| Permission Key | Description |
|---|---|
| `orders.priority` | Eligible for priority pickup queue |
| `balance.higher_limit` | Higher maximum balance ceiling |

**Differentiation from Student:**
- Teacher accounts may have separate pricing tiers configured per menu item (`teacher_price` column)
- Teacher orders can be flagged as priority in the kitchen queue (configurable by admin)
- Reporting module segments data by `student` vs `teacher` for administrative analysis
- Teachers may have higher daily order limits

---

## 3. CORE SYSTEM MODULES

### 3.1 Authentication System

**Purpose:** Secure user identity verification and session management.

**Functionality:**
- Login via email/username + password using Laravel's built-in `Auth` guard
- Registration flow for students/teachers (admin-approved or self-registration with school code)
- Password hashing using bcrypt (Laravel default)
- "Remember Me" functionality with secure long-lived tokens
- Password reset via email link (school email integration)
- Session management stored in Redis for fast access and horizontal scalability
- CSRF protection on all form submissions (Laravel default)
- Account lockout after 5 failed login attempts (throttled via `RateLimiter`)
- Logout with session invalidation and token revocation

**Key Implementation:**
```
app/Http/Controllers/Auth/
├── LoginController.php
├── RegisterController.php
├── ForgotPasswordController.php
├── ResetPasswordController.php
└── LogoutController.php

app/Http/Middleware/
├── Authenticate.php
├── RedirectBasedOnRole.php
└── EnsureAccountIsActive.php
```

**Post-Login Redirect Logic:**
```
super_admin  → /admin/dashboard
seller       → /seller/dashboard
cashier      → /cashier/scanner
student      → /menu
teacher      → /menu
```

### 3.2 Role & Permission Management

**Purpose:** Enforce access control across every route, controller action, and view element.

**Functionality:**
- Database-driven roles and permissions (not hardcoded)
- `roles` table: id, name, slug, description
- `permissions` table: id, name, slug, description
- `role_permission` pivot table: role_id, permission_id
- Each user has a `role_id` foreign key
- Middleware `CheckPermission` reads the user's role, loads associated permissions (cached in Redis), and verifies access
- Blade directives for view-level control: `@role('seller')`, `@can('menu.manage_own')`
- Super Admin bypasses all permission checks (gate `before` callback)

**Key Implementation:**
```
app/Models/Role.php
app/Models/Permission.php
app/Http/Middleware/CheckPermission.php
app/Providers/AuthServiceProvider.php  (Gate definitions)
database/seeders/RolePermissionSeeder.php
```

### 3.3 Menu Management

**Purpose:** Allow sellers to manage their stall's food offerings and allow customers to browse available items.

**Functionality:**
- **Seller side:**
  - CRUD operations for menu items: name, description, price, teacher_price (optional), image, category_id, stall_id, is_available, preparation_time_minutes
  - CRUD operations for categories: name, slug, icon, sort_order
  - Bulk availability toggle (e.g., "Mark all items as unavailable" at end of day)
  - Image upload with validation (max 2MB, jpg/png/webp) and thumbnail generation
  - Item sort ordering within categories
- **Customer side:**
  - Browse all stalls with active menus
  - Filter by stall, category, or search keyword
  - View item detail with image, description, price, and availability
  - Items marked unavailable are hidden or grayed out
- **Caching:** Menu data is cached per stall in Redis. Cache is invalidated when any menu item in that stall is created, updated, or deleted (via model observer).

**Key Implementation:**
```
app/Http/Controllers/Seller/MenuItemController.php
app/Http/Controllers/Seller/CategoryController.php
app/Http/Controllers/Customer/MenuController.php
app/Models/Stall.php
app/Models/Category.php
app/Models/MenuItem.php
app/Observers/MenuItemObserver.php  (cache invalidation)
```

### 3.4 Cart System

**Purpose:** Allow customers to accumulate items before checkout, with persistent storage.

**Functionality:**
- Add item to cart with quantity and optional note
- Update item quantity
- Remove item from cart
- Clear entire cart
- Cart stored in database (`carts` table linked to user, `cart_items` table linked to cart and menu_item)
- Cart persists across sessions and devices (database-backed, not session-based)
- Validation on add: item must be available, stall must be active
- Validation on checkout: re-verify all items are still available and prices haven't changed
- Display cart summary: items grouped by stall, subtotal per stall, grand total
- Cart item count badge in navigation (via Livewire reactive component)

**Key Implementation:**
```
app/Http/Controllers/Customer/CartController.php
app/Services/CartService.php
app/Models/Cart.php
app/Models/CartItem.php
resources/views/livewire/cart-icon.blade.php
```

### 3.5 Order Processing System

**Purpose:** Manage the complete order lifecycle from creation through completion.

**Functionality:**
- Create order from cart contents + pickup slot selection
- Split multi-stall carts into separate sub-orders per stall (one parent order, multiple stall-specific child orders)
- Assign unique order number: `ORD-{YYYYMMDD}-{5-digit-sequential}`
- Generate unique pickup code: cryptographically random 32-character token (used for QR code)
- Track order status transitions with timestamp logging
- Enforce valid status transitions (state machine pattern):
  ```
  pending → paid → preparing → ready → completed
  pending → cancelled
  paid → cancelled (with refund)
  ready → unclaimed (after timeout)
  ```
- Prevent invalid transitions (e.g., `pending` → `ready` is not allowed)
- Auto-cancel unpaid orders after configurable timeout (e.g., 15 minutes) via scheduled job
- Mark unclaimed orders after pickup slot expires + grace period via scheduled job
- Order detail view for customers with real-time status updates (Livewire polling or WebSocket)
- Order history with filtering by status and date range

**Key Implementation:**
```
app/Http/Controllers/Customer/OrderController.php
app/Http/Controllers/Seller/KitchenOrderController.php
app/Services/OrderService.php
app/StateMachines/OrderStateMachine.php
app/Models/Order.php
app/Models/OrderItem.php
app/Events/OrderStatusChanged.php
app/Listeners/NotifyCustomerOnStatusChange.php
app/Console/Commands/CancelExpiredPendingOrders.php
app/Console/Commands/MarkUnclaimedOrders.php
```

### 3.6 Kitchen Queue System

**Purpose:** Provide sellers with an organized, real-time view of incoming orders for efficient preparation.

**Functionality:**
- Display all `paid` and `preparing` orders for the seller's stall
- Primary sort: pickup time slot (earliest first)
- Secondary sort: order creation time (earliest first)
- Filter by pickup slot (view only "Break 1" orders, etc.)
- Aggregated preparation view: total quantity per menu item per slot
  ```
  Break 1 (09:30):
    Nasi Goreng   ×12
    Mie Ayam      × 8
    Es Teh        ×15
    Es Jeruk      × 6
  ```
- One-click status update buttons: "Start Preparing" → "Mark Ready"
- Batch action: "Mark all as Ready" for a given pickup slot
- Real-time order injection: new paid orders appear in the queue without page refresh (via Livewire polling every 5 seconds, or WebSocket push)
- Audio/visual notification when a new order arrives
- Counter display: "X orders pending | Y orders preparing | Z orders ready"
- Kitchen display mode: full-screen optimized view for mounted screen in kitchen

**Key Implementation:**
```
app/Http/Controllers/Seller/KitchenQueueController.php
app/Services/KitchenQueueService.php
resources/views/seller/kitchen-queue.blade.php
resources/views/livewire/kitchen-order-list.blade.php
resources/views/livewire/kitchen-aggregation.blade.php
```

### 3.7 Payment System

**Purpose:** Process payments securely via virtual balance and external QRIS payment gateway.

**Functionality:**
- **Payment Method Selection:** Customer chooses between Virtual Balance or QRIS at checkout
- **Virtual Balance Payment:**
  - Validate sufficient balance
  - Deduct amount atomically within a database transaction
  - Create payment record with `method = 'balance'`, `status = 'success'`
  - Transition order to `paid` immediately
- **QRIS Payment:**
  - Call payment gateway API to create a charge/transaction
  - Receive QRIS image URL and payment reference ID
  - Display QRIS code to customer with countdown timer (payment expiry)
  - Order status remains `pending` until webhook confirmation
  - Handle webhook callback:
    1. Verify signature using gateway's server key (HMAC-SHA512 or as specified)
    2. Verify order ID and amount match
    3. Check idempotency: if order already `paid`, return 200 OK without re-processing
    4. Transition order to `paid`
    5. Dispatch `OrderPaid` event
    6. Return HTTP 200 to gateway
  - Handle payment expiry: if webhook not received within timeout, scheduled job checks gateway API for status and cancels if unpaid
- **Refunds:**
  - For virtual balance: credit amount back to user's balance
  - For QRIS: initiate refund via gateway API (if supported) or flag for manual refund
  - Refund record created in transactions table
- **Transaction Logging:** Every payment action creates a record in the `transactions` table: order_id, user_id, amount, method, gateway_reference, status, metadata (JSON), timestamps

**Key Implementation:**
```
app/Http/Controllers/Customer/PaymentController.php
app/Http/Controllers/Api/PaymentWebhookController.php
app/Services/PaymentService.php
app/Services/Gateways/MidtransGateway.php
app/Services/Gateways/XenditGateway.php
app/Services/Gateways/TripayGateway.php
app/Contracts/PaymentGatewayInterface.php
app/Models/Transaction.php
app/Events/OrderPaid.php
config/payment.php
```

**Gateway Abstraction:**
```php
interface PaymentGatewayInterface
{
    public function createCharge(Order $order): PaymentResponse;
    public function verifyWebhook(Request $request): bool;
    public function getPaymentStatus(string $referenceId): string;
    public function refund(Transaction $transaction, int $amount): RefundResponse;
}
```

### 3.8 Virtual Balance System

**Purpose:** Provide an internal e-wallet for fast, cashless payments particularly suited to students.

**Functionality:**
- Every user account has a `balance` field (integer, stored in smallest currency unit — e.g., Rupiah, no decimals)
- **Top-Up Methods:**
  - Admin manual top-up: Super Admin enters user ID and amount, balance is credited
  - Bulk top-up: Admin uploads CSV (user_id, amount) for batch processing via queued job
  - Self-service top-up via QRIS: Customer initiates a top-up, pays via gateway, webhook credits the balance
- **Balance Deduction:** Atomic operation using database transactions with pessimistic locking (`lockForUpdate()`) to prevent race conditions when two concurrent orders attempt to deduct simultaneously
- **Balance Ledger:** Every balance change (top-up, deduction, refund) creates a record in `balance_histories` table: user_id, type (credit/debit), amount, reference_type, reference_id, balance_before, balance_after, description, timestamp
- **Balance Limits:** Configurable maximum balance per role (e.g., students max Rp 500.000, teachers max Rp 2.000.000)
- **Low Balance Alert:** Optional notification when balance falls below a configurable threshold

**Key Implementation:**
```
app/Services/BalanceService.php
app/Models/BalanceHistory.php
app/Http/Controllers/Admin/BalanceController.php
app/Http/Controllers/Customer/BalanceController.php
app/Jobs/ProcessBulkTopUp.php
```

**Critical Deduction Logic (Pseudocode):**
```php
DB::transaction(function () use ($user, $amount, $order) {
    $user = User::lockForUpdate()->find($user->id);

    if ($user->balance < $amount) {
        throw new InsufficientBalanceException();
    }

    $balanceBefore = $user->balance;
    $user->decrement('balance', $amount);

    BalanceHistory::create([
        'user_id'        => $user->id,
        'type'           => 'debit',
        'amount'         => $amount,
        'balance_before' => $balanceBefore,
        'balance_after'  => $balanceBefore - $amount,
        'reference_type' => 'order',
        'reference_id'   => $order->id,
        'description'    => "Payment for Order #{$order->order_number}",
    ]);
});
```

### 3.9 Order Pickup System

**Purpose:** Enable fast, verified order collection at the canteen counter via QR code scanning.

**Functionality:**
- **QR Code Generation:**
  - When an order is created, a unique `pickup_code` is generated (32-character random string via `Str::random(32)`)
  - The pickup code is signed using Laravel's `URL::signedRoute()` or encoded with HMAC to prevent forgery
  - QR code image is generated server-side using `simplesoftwareio/simple-qrcode` and displayed on the customer's order detail page
  - QR code encodes a URL: `{app_url}/pickup/verify/{pickup_code}` or a compact payload: `{order_id}:{pickup_code}`
- **Scanning Interface (Cashier):**
  - Full-screen camera interface using `html5-qrcode` JavaScript library
  - Supports rear-facing camera on mobile devices and external USB barcode scanners
  - On successful scan, the decoded payload is sent to the backend via AJAX
- **Verification Flow:**
  1. Backend receives the pickup code
  2. Looks up the order by `pickup_code`
  3. Validates: order exists, order belongs to the correct stall, order status is `ready`
  4. If valid: displays order summary to cashier (customer name, items, amount)
  5. Cashier clicks "Confirm Pickup"
  6. Order status transitions to `completed`, `pickup_at` timestamp is recorded, `cashier_id` is set
  7. Customer's order page updates to show "Completed"
- **Edge Cases:**
  - Order is `preparing` (not ready yet): Display message "Order still being prepared, please wait"
  - Order is already `completed`: Display message "Order already picked up"
  - Invalid QR code: Display error "Invalid order code"
  - Order is `cancelled`: Display message "This order was cancelled"

**Key Implementation:**
```
app/Http/Controllers/Cashier/PickupController.php
app/Services/PickupService.php
resources/views/cashier/scanner.blade.php
resources/views/customer/order-qr.blade.php
```

### 3.10 Reporting & Analytics System

**Purpose:** Provide data-driven insights to sellers and administrators for operational decisions.

**Functionality:**
- **Seller Reports (own stall only):**
  - Daily sales summary: total orders, total revenue, average order value
  - Item performance: most sold items, least sold items, revenue per item
  - Hourly/slot distribution: orders per pickup time slot
  - Trend charts: daily revenue over 30 days, weekly comparison
  - Export to CSV/PDF
- **Admin Reports (all stalls):**
  - Cross-stall revenue comparison
  - Total platform revenue by day/week/month
  - Payment method breakdown (balance vs. QRIS percentage)
  - User activity: active users by day, new registrations
  - Order status distribution: completed vs. cancelled vs. unclaimed rates
  - Top customers by order count and spend
  - Peak ordering time analysis
  - Stall performance ranking
- **Data Aggregation:**
  - Real-time queries for today's data
  - Pre-computed daily summaries stored in `daily_reports` table (generated by nightly scheduled job) for historical data
  - Chart rendering via client-side library (Chart.js or ApexCharts)

**Key Implementation:**
```
app/Http/Controllers/Seller/ReportController.php
app/Http/Controllers/Admin/ReportController.php
app/Services/ReportingService.php
app/Console/Commands/GenerateDailyReport.php
app/Models/DailyReport.php
```

### 3.11 Notification System

**Purpose:** Keep users informed of order status changes and system events in real time.

**Functionality:**
- **Channels:**
  - In-app notifications (Laravel's database notification channel): stored in `notifications` table, displayed in navbar dropdown
  - Real-time push via WebSocket (Laravel Echo + Pusher/Soketi): instant UI updates without polling
  - Optional: email notifications for critical events (payment confirmation, order cancellation)
- **Notification Events:**
  | Event | Recipient | Channel |
  |---|---|---|
  | New order placed (paid) | Seller | WebSocket + In-app |
  | Order status → preparing | Customer | WebSocket + In-app |
  | Order status → ready | Customer | WebSocket + In-app |
  | Order completed (picked up) | Customer | In-app |
  | Order auto-cancelled (payment timeout) | Customer | In-app + Email |
  | Balance top-up received | Customer | In-app |
  | Low balance warning | Customer | In-app |
  | System announcement | All users | In-app |
- **Implementation:** Laravel's `Notification` facade with custom notification classes per event. Each notification implements the relevant channel interfaces (`toDatabase`, `toBroadcast`, `toMail`).

**Key Implementation:**
```
app/Notifications/OrderPaidNotification.php
app/Notifications/OrderReadyNotification.php
app/Notifications/OrderCancelledNotification.php
app/Notifications/BalanceTopUpNotification.php
app/Notifications/LowBalanceNotification.php
app/Events/OrderStatusChanged.php
app/Listeners/SendOrderStatusNotification.php
```

### 3.12 Admin Management Panel

**Purpose:** Centralized control center for the Super Admin to manage all system entities and configuration.

**Functionality:**
- **User Management:** CRUD for all user accounts, role assignment, account activation/deactivation, password reset, search/filter by role/status/name
- **Stall Management:** Register new stalls, assign sellers, view stall menus (read-only), activate/deactivate stalls
- **Pickup Slot Management:** Define time slots (name, start_time, end_time, capacity, cutoff_minutes), activate/deactivate slots, adjust capacity dynamically
- **Payment Configuration:** Set gateway API keys (encrypted in database via Laravel's `Crypt` facade), enable/disable payment methods, view webhook logs
- **Balance Management:** Manual top-up interface, bulk top-up via CSV upload, view all users' balance histories
- **Order Oversight:** View all orders system-wide, filter by stall/status/date/user, cancel orders with refund
- **System Settings:** Application name, logo, school name, operating days, maintenance mode toggle
- **Audit Log:** Log of all admin actions (user created, order cancelled, settings changed) stored in `audit_logs` table

**Key Implementation:**
```
app/Http/Controllers/Admin/DashboardController.php
app/Http/Controllers/Admin/UserController.php
app/Http/Controllers/Admin/StallController.php
app/Http/Controllers/Admin/PickupSlotController.php
app/Http/Controllers/Admin/SettingController.php
app/Http/Controllers/Admin/AuditLogController.php
```

---

## 4. ORDER LIFECYCLE

### 4.1 Status Definitions

| Status | Code | Description | Triggered By |
|---|---|---|---|
| **Pending** | `pending` | Order created, awaiting payment. Cart items locked. | Customer submits checkout |
| **Paid** | `paid` | Payment confirmed. Order enters kitchen queue. | Payment webhook or balance deduction |
| **Preparing** | `preparing` | Seller has begun food preparation. | Seller clicks "Start Preparing" |
| **Ready** | `ready` | Food is prepared, waiting for customer pickup. | Seller clicks "Mark Ready" |
| **Completed** | `completed` | Customer picked up the food. Transaction finalized. | Cashier confirms pickup scan |
| **Cancelled** | `cancelled` | Order was cancelled. Refund issued if applicable. | Customer, admin, or auto-cancel job |
| **Unclaimed** | `unclaimed` | Customer did not pick up within grace period. | Scheduled job after slot expiry |

### 4.2 State Transition Diagram

```
                    ┌───────────┐
                    │  PENDING   │
                    └─────┬─────┘
                          │
              ┌───────────┼───────────┐
              │                       │
              ▼                       ▼
        ┌───────────┐          ┌───────────┐
        │   PAID    │          │ CANCELLED │
        └─────┬─────┘          └───────────┘
              │                  (timeout or
              │                   user request)
              ▼
        ┌───────────┐
        │ PREPARING │
        └─────┬─────┘
              │
              ▼
        ┌───────────┐
        │   READY   │
        └─────┬─────┘
              │
      ┌───────┼───────┐
      │               │
      ▼               ▼
┌───────────┐   ┌───────────┐
│ COMPLETED │   │ UNCLAIMED │
└───────────┘   └───────────┘
```

### 4.3 Valid Transitions

| From | To | Condition |
|---|---|---|
| `pending` | `paid` | Payment confirmed (webhook or balance) |
| `pending` | `cancelled` | Customer cancels, or payment timeout (15 min) |
| `paid` | `preparing` | Seller starts preparation |
| `paid` | `cancelled` | Admin cancels (refund issued) |
| `preparing` | `ready` | Seller marks food as done |
| `ready` | `completed` | Cashier scans QR and confirms pickup |
| `ready` | `unclaimed` | Grace period after pickup slot expires (auto) |

### 4.4 Detailed Lifecycle Walkthrough

**Step 1 — Cart to Order (Pending):**
The customer has a populated cart and a selected pickup slot. They click "Place Order." The `OrderService` executes the following within a database transaction:
1. Validate all cart items are still available (re-query `menu_items` with `is_available = true`)
2. Validate pickup slot is still open (capacity not exceeded, cutoff time not passed)
3. Create the `Order` record: user_id, stall_id, pickup_slot_id, order_number, pickup_code, status = `pending`, total_amount
4. Create `OrderItem` records for each cart item: order_id, menu_item_id, quantity, unit_price, subtotal, notes
5. Increment the pickup slot's current order count (atomic increment to prevent race condition)
6. Clear the user's cart
7. Record the `pending` status in `order_status_histories` with timestamp
8. Return the order with payment options

**Step 2 — Payment (Pending → Paid):**
The customer selects a payment method and completes payment (see Payment Lifecycle in Section 5). Upon confirmation:
1. `Order.status` transitions to `paid`
2. `order_status_histories` records the transition with timestamp
3. `OrderPaid` event is dispatched
4. Listeners: notify the seller (WebSocket + in-app), notify the customer (confirmation)

**Step 3 — Preparation (Paid → Preparing):**
The seller views the order in their kitchen queue and clicks "Start Preparing":
1. `Order.status` transitions to `preparing`
2. `order_status_histories` records with timestamp and seller_id
3. `OrderStatusChanged` event dispatched
4. Customer receives real-time update: "Your order is being prepared"

**Step 4 — Ready (Preparing → Ready):**
The seller completes preparation and clicks "Mark Ready":
1. `Order.status` transitions to `ready`
2. `order_status_histories` records with timestamp
3. `OrderStatusChanged` event dispatched
4. Customer receives real-time notification: "Your order is ready for pickup!"

**Step 5 — Pickup (Ready → Completed):**
The customer goes to the canteen and shows their QR code. The cashier scans it:
1. Backend validates the pickup code and order status
2. `Order.status` transitions to `completed`
3. `Order.picked_up_at` = current timestamp
4. `Order.cashier_id` = authenticated cashier's user ID
5. `order_status_histories` records the final transition
6. Transaction is fully finalized

**Step 6 — Cancellation (Pending/Paid → Cancelled):**
- **Customer-initiated (pending only):** Customer clicks "Cancel Order." Order status → `cancelled`. Pickup slot capacity is decremented. No refund needed (no payment made).
- **Auto-cancel (pending → cancelled):** Scheduled command `CancelExpiredPendingOrders` runs every minute. Any order in `pending` status older than 15 minutes is cancelled. Pickup slot capacity restored.
- **Admin-initiated (paid → cancelled):** Admin cancels a paid order. Refund is processed (balance credit or gateway refund). Order status → `cancelled`. Pickup slot capacity restored.

**Step 7 — Unclaimed (Ready → Unclaimed):**
Scheduled command `MarkUnclaimedOrders` runs every 5 minutes. Any order in `ready` status whose pickup slot end time + grace period (e.g., 30 minutes) has passed is marked `unclaimed`. This data feeds into operational reports.

---

## 5. PAYMENT LIFECYCLE

### 5.1 Payment Flow — Virtual Balance

```
Customer                    Laravel App                    Database
   │                            │                             │
   │  Select "Virtual Balance"  │                             │
   │──────────────────────────►│                             │
   │                            │  BEGIN TRANSACTION          │
   │                            │────────────────────────────►│
   │                            │  SELECT ... FOR UPDATE      │
   │                            │  (lock user row)            │
   │                            │◄────────────────────────────│
   │                            │                             │
   │                            │  Check: balance >= amount?  │
   │                            │  Yes ──►                    │
   │                            │  UPDATE balance -= amount   │
   │                            │────────────────────────────►│
   │                            │  INSERT balance_history     │
   │                            │────────────────────────────►│
   │                            │  INSERT transaction         │
   │                            │────────────────────────────►│
   │                            │  UPDATE order status='paid' │
   │                            │────────────────────────────►│
   │                            │  COMMIT TRANSACTION         │
   │                            │────────────────────────────►│
   │                            │                             │
   │                            │  Dispatch OrderPaid event   │
   │  ◄── Redirect to order    │                             │
   │       tracking page        │                             │
```

### 5.2 Payment Flow — QRIS via Payment Gateway

```
Customer              Laravel App            Payment Gateway         Database
   │                      │                        │                     │
   │  Select "QRIS"       │                        │                     │
   │─────────────────────►│                        │                     │
   │                      │  POST /create-charge   │                     │
   │                      │───────────────────────►│                     │
   │                      │  ◄── QRIS image URL    │                     │
   │                      │       + reference_id   │                     │
   │                      │                        │                     │
   │                      │  INSERT transaction    │                     │
   │                      │  (status='pending')    │                     │
   │                      │───────────────────────────────────────────►  │
   │                      │                        │                     │
   │  ◄── Display QRIS    │                        │                     │
   │      code + timer    │                        │                     │
   │                      │                        │                     │
   │  ══════ Customer scans QRIS with banking app ═══════                │
   │                      │                        │                     │
   │                      │  POST /webhook         │                     │
   │                      │  ◄─────────────────────│                     │
   │                      │                        │                     │
   │                      │  1. Verify signature   │                     │
   │                      │  2. Verify amount      │                     │
   │                      │  3. Check idempotency  │                     │
   │                      │                        │                     │
   │                      │  UPDATE transaction    │                     │
   │                      │  (status='success')    │                     │
   │                      │  UPDATE order          │                     │
   │                      │  (status='paid')       │                     │
   │                      │───────────────────────────────────────────►  │
   │                      │                        │                     │
   │                      │  Dispatch OrderPaid    │                     │
   │                      │  Return HTTP 200       │                     │
   │                      │───────────────────────►│                     │
   │                      │                        │                     │
   │  ◄── Real-time       │                        │                     │
   │      update: "Paid"  │                        │                     │
```

### 5.3 Webhook Security

The payment webhook endpoint (`/api/webhooks/payment`) requires special security measures because it is publicly accessible (no auth session):

1. **Signature Verification:** Every webhook request from the gateway includes a signature header (e.g., `X-Callback-Signature`). The system reconstructs the expected signature using the raw request body and the gateway's server key (stored encrypted in the database), then compares using `hash_equals()` to prevent timing attacks. If the signature does not match, the request is rejected with HTTP 403.

2. **IP Whitelisting (Optional):** Restrict the webhook endpoint to the gateway's known IP ranges via Nginx configuration or Laravel middleware.

3. **Idempotency:** The handler checks if the transaction has already been processed (status is already `success`). If so, it returns HTTP 200 without re-processing. This safely handles duplicate webhook deliveries.

4. **Amount Verification:** The webhook payload includes the paid amount. The system verifies it matches the order's `total_amount`. Mismatches are logged and flagged for admin review.

5. **CSRF Exemption:** The webhook route is excluded from CSRF middleware (added to `$except` in `VerifyCsrfToken` middleware or defined in `api.php` routes which are CSRF-exempt by default).

6. **Rate Limiting:** The webhook endpoint is rate-limited to prevent abuse (e.g., 60 requests per minute per IP).

### 5.4 Payment Expiry Handling

If a QRIS payment is not completed within the timeout period (e.g., 15 minutes):

1. **Client-side:** The payment page shows a countdown timer. When it reaches zero, the UI displays "Payment expired" and offers a "Try Again" option.
2. **Server-side:** The scheduled command `CancelExpiredPendingOrders` checks for orders in `pending` status older than 15 minutes. For each:
   - If the order has a gateway transaction, it queries the gateway API for the final status
   - If truly unpaid, the order is cancelled and the pickup slot capacity is restored
   - The transaction record is updated to `expired`

---

## 6. KITCHEN QUEUE SYSTEM

### 6.1 Queue Architecture

The kitchen queue is a database-driven, real-time system that presents sellers with an ordered list of work items.

**Data Source:** Orders with `status IN ('paid', 'preparing')` and `stall_id = {seller's stall}`.

**Sorting Algorithm:**
```sql
SELECT orders.*
FROM orders
JOIN pickup_slots ON orders.pickup_slot_id = pickup_slots.id
WHERE orders.stall_id = :stall_id
  AND orders.status IN ('paid', 'preparing')
ORDER BY
  pickup_slots.start_time ASC,   -- Earliest pickup slot first
  orders.created_at ASC           -- Within slot: first-come first-served
```

### 6.2 Queue Views

The seller dashboard provides three queue views:

**View 1 — Individual Order Queue (Default)**
Each order is displayed as a card showing:
- Order number (`ORD-20260309-00042`)
- Customer name
- Pickup slot label ("Break 1 — 09:30")
- List of items with quantities and notes
- Current status badge (Paid / Preparing)
- Time since order was placed
- Action button: "Start Preparing" or "Mark Ready"

**View 2 — Aggregated Preparation List**
Grouped by pickup slot, shows total quantities of each item needed:
```
━━━ Break 1 (09:30) ━━━━━━━━━━━━━━━━━━━━
Nasi Goreng Spesial     ×12
Mie Ayam Bakso          × 8
Ayam Geprek             ×15
Es Teh Manis            ×22
Es Jeruk                × 6
─────────────────────────────────────────
Total orders: 34

━━━ Break 2 (12:00) ━━━━━━━━━━━━━━━━━━━━
Nasi Goreng Spesial     × 5
Bakso                   ×10
Es Teh Manis            ×14
─────────────────────────────────────────
Total orders: 18
```

This aggregation query:
```sql
SELECT
    ps.name AS slot_name,
    mi.name AS item_name,
    SUM(oi.quantity) AS total_quantity
FROM order_items oi
JOIN orders o ON oi.order_id = o.id
JOIN pickup_slots ps ON o.pickup_slot_id = ps.id
JOIN menu_items mi ON oi.menu_item_id = mi.id
WHERE o.stall_id = :stall_id
  AND o.status IN ('paid', 'preparing')
GROUP BY ps.id, mi.id
ORDER BY ps.start_time ASC, total_quantity DESC
```

**View 3 — Completed Orders (History)**
Orders marked as `ready` or `completed` today, for reference and reconciliation.

### 6.3 Real-Time Updates

New orders entering the queue (status transitioning to `paid`) trigger a broadcast event via Laravel Echo:

```php
// Broadcasting on a private channel for the seller
class NewOrderForSeller implements ShouldBroadcast
{
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('seller.' . $this->order->stall_id);
    }
}
```

The seller's Livewire component listens for this event and prepends the new order to the queue list with a highlight animation and an audible notification sound.

### 6.4 Capacity and Cutoff Logic

**Capacity enforcement** happens at order creation time:
```php
$slot = PickupSlot::lockForUpdate()->find($slotId);

if ($slot->current_orders >= $slot->max_capacity) {
    throw new SlotFullException();
}

$slot->increment('current_orders');
```

**Cutoff enforcement:**
```php
$cutoffTime = $slot->start_time->subMinutes($slot->cutoff_minutes);

if (now()->greaterThanOrEqualTo($cutoffTime)) {
    throw new SlotCutoffPassedException();
}
```

---

## 7. ORDER PICKUP PROCESS

### 7.1 End-to-End Pickup Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                      PICKUP FLOW                                │
│                                                                 │
│  ┌──────────┐    ┌───────────────┐    ┌──────────────────────┐ │
│  │ Customer │    │   Cashier     │    │      Backend         │ │
│  │          │    │   Scanner     │    │                      │ │
│  │ Opens    │    │               │    │                      │ │
│  │ order    │───►│ Scans QR     │───►│ Validate pickup_code │ │
│  │ page on  │    │ code from    │    │ Check status = ready  │ │
│  │ phone    │    │ customer's   │    │ Load order details    │ │
│  │          │    │ screen       │    │                      │ │
│  │ Shows    │    │               │    │ Return order summary │ │
│  │ QR code  │    │ Sees order   │◄───│                      │ │
│  │          │    │ details      │    │                      │ │
│  │          │    │               │    │                      │ │
│  │          │    │ Clicks       │───►│ Status → completed   │ │
│  │          │    │ "Confirm"    │    │ Record cashier_id    │ │
│  │          │    │               │    │ Record picked_up_at  │ │
│  │ ◄─────────── │ Hands over   │    │ Dispatch event       │ │
│  │ Receives     │ food         │    │                      │ │
│  │ food         │               │    │                      │ │
│  └──────────┘    └───────────────┘    └──────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

### 7.2 QR Code Specification

- **Content:** `{order_id}|{pickup_code}|{hmac_signature}`
  - `order_id`: Integer order ID
  - `pickup_code`: 32-character random string stored on the order
  - `hmac_signature`: `HMAC-SHA256(order_id + pickup_code, APP_KEY)` — prevents forgery of QR codes
- **Format:** QR Code version auto-detected, error correction level M (15% recovery)
- **Size:** 300×300 pixels, rendered as SVG for crisp display on any screen
- **Display:** Centered on the customer's order detail page with order number displayed above and a "Show to cashier" instruction below
- **Brightness:** Page background is forced to white with maximum screen brightness hint (via CSS/JS) to optimize camera scanning

### 7.3 Scanner Implementation

The cashier scanner page uses the `html5-qrcode` JavaScript library:

```javascript
const scanner = new Html5QrcodeScanner("reader", {
    fps: 10,
    qrbox: { width: 300, height: 300 },
    rememberLastUsedCamera: true,
    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
});

scanner.render(onScanSuccess, onScanFailure);

function onScanSuccess(decodedText) {
    // Send to backend for verification
    fetch('/cashier/pickup/verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ code: decodedText })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            showOrderDetails(data.order);
            showConfirmButton(data.order.id);
        } else {
            showError(data.message);
        }
    });
}
```

### 7.4 Verification API Response

**Successful verification:**
```json
{
    "valid": true,
    "order": {
        "id": 42,
        "order_number": "ORD-20260309-00042",
        "customer_name": "Ahmad Rizki",
        "status": "ready",
        "pickup_slot": "Break 1 (09:30)",
        "items": [
            { "name": "Nasi Goreng Spesial", "quantity": 1, "notes": "Extra pedas" },
            { "name": "Es Teh Manis", "quantity": 2, "notes": null }
        ],
        "total_amount": 25000,
        "paid_via": "virtual_balance"
    }
}
```

**Failed verification (various reasons):**
```json
{ "valid": false, "message": "Order is still being prepared. Please wait." }
{ "valid": false, "message": "This order has already been picked up." }
{ "valid": false, "message": "Invalid order code." }
{ "valid": false, "message": "This order was cancelled." }
```

---

## 8. SECURITY CONSIDERATIONS

### 8.1 Authentication & Session Security

| Measure | Implementation |
|---|---|
| Password hashing | bcrypt with cost factor 12 (Laravel default) |
| Session storage | Redis (prevents session file bloat, enables centralized invalidation) |
| Session fixation | `session()->regenerate()` on login (Laravel default) |
| Session hijacking | `secure`, `httpOnly`, `SameSite=Lax` cookie flags |
| Brute force protection | `RateLimiter`: 5 attempts per minute per email, lockout for 60 seconds |
| CSRF protection | Synchronizer token pattern on all POST/PUT/DELETE forms (Laravel default) |
| Remember me token | 60-character random string, hashed in DB, cookie encrypted |
| Account deactivation | `is_active` flag; middleware `EnsureAccountIsActive` blocks deactivated users |

### 8.2 Authorization & Access Control

| Measure | Implementation |
|---|---|
| Role-based access | Middleware `CheckPermission` on all protected routes |
| Permission caching | User permissions loaded once per request, cached in Redis per role |
| Data isolation | Global Eloquent scopes filter by `stall_id` for seller queries |
| Super Admin gate | `Gate::before()` returns true for super_admin, bypassing other checks |
| API authorization | Webhook routes use signature verification, not session auth |
| Direct object access | Every model access verifies ownership (user_id or stall_id) via policy classes |

### 8.3 Payment Security

| Measure | Implementation |
|---|---|
| Webhook signature | HMAC-SHA512 verification using `hash_equals()` (constant-time comparison) |
| Idempotent webhooks | Transaction record checked before processing; duplicate callbacks are safe no-ops |
| Amount verification | Webhook amount compared to order total; mismatches flagged for review |
| Balance atomicity | `lockForUpdate()` + DB transaction for all balance operations |
| Gateway credentials | Stored encrypted using `Crypt::encryptString()` in `settings` table |
| Pickup code security | 32-char random string + HMAC signature in QR code prevents forgery |
| Payment timeout | Orders auto-cancelled after 15 minutes if unpaid |

### 8.4 Protection Against Common Vulnerabilities (OWASP Top 10)

| Vulnerability | Protection |
|---|---|
| **SQL Injection** | Eloquent ORM parameterized queries exclusively; no raw SQL with user input |
| **XSS (Cross-Site Scripting)** | Blade `{{ }}` auto-escapes output; `{!! !!}` never used with user input |
| **CSRF** | Laravel CSRF middleware on all web routes; `@csrf` in every form |
| **Broken Access Control** | Per-route middleware + Eloquent global scopes + policy classes |
| **Insecure Direct Object Reference** | Order lookup by `pickup_code` (non-sequential), not by `id` in public contexts |
| **Security Misconfiguration** | `.env` excluded from version control; `APP_DEBUG=false` in production; directory listing disabled in Nginx |
| **Sensitive Data Exposure** | HTTPS enforced via Nginx redirect; password hashed; API keys encrypted at rest |
| **Mass Assignment** | `$fillable` arrays on all Eloquent models; `$guarded` for sensitive fields |
| **File Upload Attacks** | Image validation (mime type + extension), max size limit (2MB), storage outside public root with symbolic link |
| **SSRF** | No user-supplied URLs used in server-side HTTP requests; webhook IP whitelist |
| **Rate Limiting** | `ThrottleRequests` middleware on login (5/min), API routes (60/min), webhook (60/min) |

### 8.5 Data Protection

- **Personal data minimization:** Only collect necessary student data (name, email/school ID, role). No home address, ID card photos, etc.
- **Database backups:** Automated daily via scheduled job (`mysqldump`), stored encrypted, rotated (keep 30 days)
- **Audit logging:** All admin actions logged in `audit_logs` table (who, what, when, from where)
- **Environment secrets:** All secrets in `.env`, never committed to version control, loaded via `config()` only

---

## 9. SCALABILITY CONSIDERATIONS

### 9.1 Performance Targets

| Metric | Target |
|---|---|
| Concurrent users during peak | 500+ simultaneous |
| Order throughput | 200 orders within a 5-minute pre-break window |
| Page load time (menu browsing) | < 1 second (cached) |
| Payment processing time | < 3 seconds (virtual balance), < 5 seconds (QRIS display) |
| Kitchen queue refresh | < 2 seconds |
| QR scan to confirmation | < 1 second |

### 9.2 Caching Strategy (Redis)

| Cache Target | Key Pattern | TTL | Invalidation |
|---|---|---|---|
| Menu items per stall | `menu:stall:{id}` | 1 hour | On menu item create/update/delete (observer) |
| Pickup slot availability | `slots:available:{date}` | 30 seconds | On order create/cancel |
| User permissions | `permissions:role:{id}` | 24 hours | On permission change |
| Dashboard counters | `dashboard:{stall_id}:counts` | 10 seconds | TTL-based |
| Daily report data | `report:{stall_id}:{date}` | 1 hour | On new completed order |

### 9.3 Queue Processing (Redis-backed)

Background jobs offloaded from the HTTP request cycle:

| Job | Queue Name | Description |
|---|---|---|
| `SendOrderNotification` | `notifications` | Push notification to seller/customer |
| `ProcessPaymentWebhook` | `payments` | Handle payment confirmation (high priority) |
| `GenerateQRCode` | `default` | Generate QR code image for order |
| `ProcessBulkTopUp` | `default` | Process CSV bulk top-up |
| `GenerateDailyReport` | `reports` | Compile daily aggregated reports |
| `SendEmailNotification` | `emails` | Send transactional emails |

**Queue configuration:**
```
# /etc/supervisor/conf.d/e-canteen-worker.conf
[program:e-canteen-payments]
command=php artisan queue:work redis --queue=payments --tries=3 --timeout=30
numprocs=2
priority=10

[program:e-canteen-notifications]
command=php artisan queue:work redis --queue=notifications --tries=3 --timeout=15
numprocs=2
priority=20

[program:e-canteen-default]
command=php artisan queue:work redis --queue=default,reports,emails --tries=3 --timeout=60
numprocs=1
priority=30
```

### 9.4 Database Optimization

| Strategy | Implementation |
|---|---|
| **Indexing** | Composite indexes on `orders(stall_id, status, pickup_slot_id)`, `orders(user_id, status)`, `order_items(order_id)`, `menu_items(stall_id, is_available)` |
| **Eager loading** | All controller queries use `with()` to prevent N+1 queries |
| **Pagination** | All list views paginated (15-25 items per page) |
| **Query optimization** | Complex reports use raw queries or database views for performance |
| **Connection pooling** | PHP-FPM persistent connections to MySQL |
| **Read replicas** | Future: reporting queries directed to read replica |

### 9.5 Nginx Configuration for Performance

```nginx
server {
    listen 443 ssl http2;
    server_name ecanteen.school.sch.id;

    root /var/www/e-canteen/public;
    index index.php;

    # SSL
    ssl_certificate /etc/letsencrypt/live/ecanteen.school.sch.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/ecanteen.school.sch.id/privkey.pem;

    # Gzip compression
    gzip on;
    gzip_types text/css application/javascript application/json image/svg+xml;
    gzip_min_length 1024;

    # Static file caching
    location ~* \.(css|js|png|jpg|jpeg|webp|gif|ico|svg|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
    limit_req_zone $binary_remote_addr zone=api:10m rate=60r/m;
    limit_req_zone $binary_remote_addr zone=webhook:10m rate=30r/m;

    location /login {
        limit_req zone=login burst=3;
        try_files $uri /index.php?$query_string;
    }

    location /api/webhooks {
        limit_req zone=webhook burst=10;
        try_files $uri /index.php?$query_string;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 30;
    }

    # Block access to sensitive files
    location ~ /\.(?!well-known) {
        deny all;
    }

    location ~ \.(env|log|md)$ {
        deny all;
    }
}

# HTTP to HTTPS redirect
server {
    listen 80;
    server_name ecanteen.school.sch.id;
    return 301 https://$server_name$request_uri;
}
```

### 9.6 Horizontal Scaling Path

For future growth beyond a single server:

```
                    ┌─────────────┐
                    │ Load Balancer│
                    │  (Nginx)    │
                    └──────┬──────┘
                           │
              ┌────────────┼────────────┐
              ▼            ▼            ▼
        ┌──────────┐ ┌──────────┐ ┌──────────┐
        │ App      │ │ App      │ │ App      │
        │ Server 1 │ │ Server 2 │ │ Server 3 │
        │ (PHP-FPM)│ │ (PHP-FPM)│ │ (PHP-FPM)│
        └────┬─────┘ └────┬─────┘ └────┬─────┘
             │             │             │
             └──────┬──────┘──────┬──────┘
                    ▼             ▼
            ┌──────────┐  ┌──────────┐
            │  MySQL   │  │  Redis   │
            │ Primary  │  │ Cluster  │
            │ + Replica│  │          │
            └──────────┘  └──────────┘
```

Requirements for horizontal scaling:
- Sessions stored in Redis (already configured)
- File uploads stored on S3-compatible storage (not local disk)
- Queue workers run on dedicated worker node(s)
- Database on dedicated server with read replicas for reporting

---

## 10. FUTURE EXPANSION POSSIBILITIES

### 10.1 Multi-School Platform (Multi-Tenancy)

**Approach:** Add a `school_id` column to all primary tables (`users`, `stalls`, `orders`, `pickup_slots`, etc.). Every query is scoped by `school_id` via a global Eloquent scope derived from the authenticated user's school. A new **School Admin** role manages a single school's data, while the Super Admin manages all schools.

**Database addition:**
```sql
CREATE TABLE schools (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    domain VARCHAR(255) NULL,          -- Optional custom subdomain
    address TEXT NULL,
    logo VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    settings JSON NULL,                -- School-specific config
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Add school_id to existing tables
ALTER TABLE users ADD COLUMN school_id BIGINT UNSIGNED AFTER id;
ALTER TABLE stalls ADD COLUMN school_id BIGINT UNSIGNED AFTER id;
-- ... etc.
```

**Access pattern:** `ecanteen.school.sch.id` or `{school-slug}.ecanteen.id` for multi-tenant SaaS model.

### 10.2 Mobile Application

The Laravel backend already serves as an API layer. A dedicated mobile app would:
- Consume the same API endpoints using **Laravel Sanctum** token authentication
- Receive push notifications via **Firebase Cloud Messaging (FCM)** through a `mobile_push` notification channel
- Use the phone camera natively for QR code scanning (cashier role)
- Provide offline menu browsing with local caching

**API versioning:** Routes prefixed with `/api/v1/` to support backward compatibility when the API evolves.

### 10.3 Advanced Analytics Dashboard

- **Demand forecasting:** Use historical order data to predict order volumes per stall per time slot, enabling sellers to pre-purchase ingredients accurately. Implementable with simple moving averages initially, ML models later.
- **Menu optimization:** Identify underperforming items (low sales, high cancellation rate) and suggest removal or price adjustment.
- **Revenue anomaly detection:** Alert admins when daily revenue deviates significantly from the historical average.
- **Student nutrition tracking:** If menu items have nutritional data (calories, macros), provide optional nutrition reports per student (privacy-gated, parent-accessible only).

### 10.4 Parent Portal

- **Balance management:** Parents can view their child's balance, top it up remotely via QRIS, and set daily spending limits.
- **Order visibility:** Parents can see what their child ordered (optional, privacy setting).
- **Spending reports:** Weekly email with spending summary.

### 10.5 Loyalty & Gamification

- **Points system:** Earn points per order (1 point per Rp 1.000 spent). Redeem points for discounts or free items.
- **Referral rewards:** Students who invite classmates to the platform earn bonus points.
- **Streak rewards:** Order every school day for a week → bonus points.

### 10.6 Meal Plan Subscriptions

- **Weekly/monthly meal packages:** Bundled offerings (e.g., lunch every day for a week at 10% discount).
- **Automatic ordering:** System places the recurring order automatically each day unless cancelled by previous evening.

### 10.7 Integration with School Information Systems

- **Student account provisioning:** Sync with existing SIS to automatically create student accounts, linked by student ID number.
- **Class schedule integration:** Only show pickup slots that align with the student's actual break schedule.
- **Attendance data:** If the student is absent, auto-cancel their pre-orders for the day (optional integration).

---

## APPENDIX A: Laravel Directory Structure

```
app/
├── Console/
│   └── Commands/
│       ├── CancelExpiredPendingOrders.php
│       ├── MarkUnclaimedOrders.php
│       └── GenerateDailyReport.php
├── Contracts/
│   └── PaymentGatewayInterface.php
├── Events/
│   ├── OrderPaid.php
│   └── OrderStatusChanged.php
├── Exceptions/
│   ├── InsufficientBalanceException.php
│   ├── SlotFullException.php
│   └── SlotCutoffPassedException.php
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── UserController.php
│   │   │   ├── StallController.php
│   │   │   ├── PickupSlotController.php
│   │   │   ├── BalanceController.php
│   │   │   ├── ReportController.php
│   │   │   ├── SettingController.php
│   │   │   └── AuditLogController.php
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   ├── RegisterController.php
│   │   │   ├── ForgotPasswordController.php
│   │   │   └── ResetPasswordController.php
│   │   ├── Cashier/
│   │   │   └── PickupController.php
│   │   ├── Customer/
│   │   │   ├── MenuController.php
│   │   │   ├── CartController.php
│   │   │   ├── OrderController.php
│   │   │   ├── PaymentController.php
│   │   │   └── BalanceController.php
│   │   ├── Seller/
│   │   │   ├── DashboardController.php
│   │   │   ├── MenuItemController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── KitchenQueueController.php
│   │   │   └── ReportController.php
│   │   └── Api/
│   │       └── PaymentWebhookController.php
│   ├── Middleware/
│   │   ├── CheckPermission.php
│   │   ├── RedirectBasedOnRole.php
│   │   └── EnsureAccountIsActive.php
│   └── Requests/
│       ├── StoreMenuItemRequest.php
│       ├── UpdateMenuItemRequest.php
│       ├── PlaceOrderRequest.php
│       ├── ProcessPaymentRequest.php
│       └── TopUpBalanceRequest.php
├── Jobs/
│   ├── ProcessPaymentWebhook.php
│   ├── ProcessBulkTopUp.php
│   ├── SendOrderNotification.php
│   └── GenerateQRCode.php
├── Listeners/
│   ├── NotifySellerOnNewOrder.php
│   ├── NotifyCustomerOnStatusChange.php
│   └── LogOrderStatusChange.php
├── Models/
│   ├── User.php
│   ├── Role.php
│   ├── Permission.php
│   ├── School.php            (future: multi-tenancy)
│   ├── Stall.php
│   ├── Category.php
│   ├── MenuItem.php
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── OrderStatusHistory.php
│   ├── PickupSlot.php
│   ├── Transaction.php
│   ├── BalanceHistory.php
│   ├── DailyReport.php
│   ├── AuditLog.php
│   └── Setting.php
├── Notifications/
│   ├── OrderPaidNotification.php
│   ├── OrderPreparingNotification.php
│   ├── OrderReadyNotification.php
│   ├── OrderCancelledNotification.php
│   └── BalanceTopUpNotification.php
├── Observers/
│   └── MenuItemObserver.php
├── Policies/
│   ├── OrderPolicy.php
│   ├── MenuItemPolicy.php
│   └── StallPolicy.php
├── Providers/
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php
│   └── EventServiceProvider.php
├── Services/
│   ├── OrderService.php
│   ├── CartService.php
│   ├── PaymentService.php
│   ├── BalanceService.php
│   ├── KitchenQueueService.php
│   ├── PickupService.php
│   ├── ReportingService.php
│   └── Gateways/
│       ├── MidtransGateway.php
│       ├── XenditGateway.php
│       └── TripayGateway.php
└── StateMachines/
    └── OrderStateMachine.php

config/
├── payment.php               (gateway keys, timeouts, methods)
└── ecanteen.php              (app-specific: slot defaults, limits, etc.)

database/
├── migrations/
│   ├── create_roles_table.php
│   ├── create_permissions_table.php
│   ├── create_role_permission_table.php
│   ├── add_role_to_users_table.php
│   ├── create_stalls_table.php
│   ├── create_categories_table.php
│   ├── create_menu_items_table.php
│   ├── create_carts_table.php
│   ├── create_cart_items_table.php
│   ├── create_pickup_slots_table.php
│   ├── create_orders_table.php
│   ├── create_order_items_table.php
│   ├── create_order_status_histories_table.php
│   ├── create_transactions_table.php
│   ├── create_balance_histories_table.php
│   ├── create_daily_reports_table.php
│   ├── create_audit_logs_table.php
│   └── create_settings_table.php
└── seeders/
    ├── RolePermissionSeeder.php
    ├── PickupSlotSeeder.php
    ├── DemoStallSeeder.php
    └── DemoUserSeeder.php

routes/
├── web.php                   (all authenticated web routes)
├── api.php                   (webhook routes, future mobile API)
└── channels.php              (broadcast channel authorization)
```

---

## APPENDIX B: Key Database Tables Summary

| Table | Purpose | Key Columns |
|---|---|---|
| `users` | All system users | id, name, email, password, role_id, balance, is_active |
| `roles` | Role definitions | id, name, slug |
| `permissions` | Permission definitions | id, name, slug |
| `role_permission` | Role ↔ Permission pivot | role_id, permission_id |
| `stalls` | Canteen stalls | id, name, description, logo, seller_id, is_active |
| `categories` | Menu categories per stall | id, stall_id, name, slug, sort_order |
| `menu_items` | Food items | id, stall_id, category_id, name, description, price, teacher_price, image, is_available, preparation_time |
| `carts` | User shopping carts | id, user_id |
| `cart_items` | Cart line items | id, cart_id, menu_item_id, quantity, notes |
| `pickup_slots` | Break time definitions | id, name, start_time, end_time, max_capacity, current_orders, cutoff_minutes, is_active |
| `orders` | Customer orders | id, user_id, stall_id, pickup_slot_id, order_number, pickup_code, status, total_amount, paid_at, picked_up_at, cashier_id |
| `order_items` | Order line items | id, order_id, menu_item_id, quantity, unit_price, subtotal, notes |
| `order_status_histories` | Status change audit trail | id, order_id, from_status, to_status, changed_by, changed_at |
| `transactions` | Payment records | id, order_id, user_id, amount, method, gateway_reference, status, metadata |
| `balance_histories` | Balance ledger | id, user_id, type, amount, balance_before, balance_after, reference_type, reference_id, description |
| `daily_reports` | Pre-computed daily stats | id, stall_id, date, total_orders, total_revenue, items_data (JSON) |
| `audit_logs` | Admin action log | id, user_id, action, target_type, target_id, old_values, new_values, ip_address |
| `settings` | Key-value config | id, key, value (encrypted for sensitive), group |
| `notifications` | In-app notifications | id, type, notifiable_type, notifiable_id, data, read_at (Laravel default) |

---

**Document Version:** 1.0
**Section:** 2 — System Architecture Plan
**Project:** E-Canteen Pre-Order System
**Stack:** Laravel 12 · MySQL 8 · Redis 7 · Nginx · Livewire 3
**Authored for:** Developer implementation team
