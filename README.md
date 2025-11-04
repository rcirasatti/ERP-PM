# 📊 ERP Project Management Dashboard

Sistem ERP Project Management untuk memantau project dari penawaran (quotation) hingga selesai, dengan tampilan dashboard yang intuitif dan responsif menggunakan **Laravel 11** dan **Tailwind CSS**.

---

## ⚡ QUICK START - Login & Test (2 menit)

### 🎯 Langsung Test Sekarang

**Buka browser:**
```
http://127.0.0.1:8000
```

**Login dengan akun test:**
```
Email:    john@example.com
Password: password123
```

**Akun test siap pakai:**
| Email | Password |
|-------|----------|
| john@example.com | password123 |
| jane@example.com | password123 |
| admin@example.com | admin123 |

**Sudah login?** Explore semua halaman: Dashboard, Projects, Quotations, Tasks, Invoices, Reports

**Mau logout?** Click user profile (atas kanan) → Logout

### 📚 Dokumentasi
- **[LOGIN_QUICKSTART.md](LOGIN_QUICKSTART.md)** ⭐ Mulai dari sini
- **[AUTH_SETUP.md](AUTH_SETUP.md)** - Detail authentication
- **[AUTHENTICATION_COMPLETE.md](AUTHENTICATION_COMPLETE.md)** - Apa yang sudah diimplementasi
- **[DEVELOPER_CHECKLIST.md](DEVELOPER_CHECKLIST.md)** - Roadmap development

---

## 🚀 Fitur Utama

### Dashboard
- **KPI Cards**: Total projects, in progress, completed, dan total revenue
- **Recent Projects**: Tabel project terbaru dengan status dan progress
- **Project Distribution**: Distribusi status project dengan statistik
- **Upcoming Tasks**: Daftar tasks yang akan datang dengan prioritas
- **Reminders**: Pengingat penting untuk tim

### Projects
- Tampilan grid project dengan detail lengkap
- Status project: In Progress, Completed, Pending, On Hold
- Progress bar untuk setiap project
- Informasi klien, budget, dan deadline
- Filter berdasarkan status dan tim

### Quotations
- Manajemen penawaran/quotation
- Status quotation: Draft, Sent, Approved, Rejected, Expired
- Tracking nilai penawaran
- Validitas penawaran dengan deadline
- View dan action buttons

### Tasks (Kanban Board)
- 4 kolom: To Do, In Progress, Review, Completed
- Task assignment ke team members dengan avatar
- Priority level: High, Medium, Low
- Progress tracking dengan progress bar
- Due date monitoring

### Invoices
- Manajemen invoice dengan status tracking
- Status: Draft, Sent, Paid, Pending, Overdue, Cancelled
- KPI invoicing: Total Invoiced, Paid, Pending, Overdue
- Export PDF/Excel functionality
- Payment tracking

### Reports
- Analytics dan insights performa project
- Revenue trend chart
- Project status distribution
- Resource utilization metrics
- Top performing projects ranking
- Client satisfaction metrics

## 📋 Struktur Project

```
resources/views/
├── layouts/
│   ├── app.blade.php          ✅ Main layout dengan sidebar & header
│   ├── sidebar.blade.php      ✅ Navigasi sidebar dengan menu items
│   └── header.blade.php       ✅ Top header dengan search & notifications
├── dashboard/
│   └── index.blade.php        ✅ Dashboard page - KPI & overview
├── projects/
│   └── index.blade.php        ✅ Projects list - grid view
├── quotations/
│   └── index.blade.php        ✅ Quotations list - table view
├── tasks/
│   └── index.blade.php        ✅ Tasks kanban board
├── invoices/
│   └── index.blade.php        ✅ Invoices list - table view
└── reports/
    └── index.blade.php        ✅ Reports & analytics
```

## 🛠️ Tech Stack

| Component | Version | Keterangan |
|-----------|---------|-----------|
| **Framework** | Laravel 11 | Backend framework |
| **CSS** | Tailwind CSS v4 | Utility-first CSS |
| **Build Tool** | Vite | Next generation frontend tooling |
| **Icons** | Heroicons (SVG) | Inline SVG icons |
| **Database** | SQLite (default) | Dapat diubah ke MySQL/PostgreSQL |

## 📦 Installation & Setup

### Prerequisites
- PHP 8.2+
- Node.js 16+
- Composer
- Git

### Quick Start

```bash
# 1. Navigate ke project
cd d:\Magang\ERP-PM

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Build assets
npm run build

# 5. Start development server
php artisan serve
```

Server berjalan di: `http://127.0.0.1:8000`

### Development Mode

Untuk development dengan hot reload:

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server (untuk CSS/JS changes)
npm run dev
```

## 📱 Responsive Design

✅ Fully responsive untuk:
- **Desktop**: 1920px+
- **Laptop**: 1366px - 1920px
- **Tablet**: 768px - 1366px
- **Mobile**: 320px - 768px

**Mobile Features**:
- Collapsible sidebar dengan toggle button
- Touch-friendly buttons & spacing
- Optimized layouts untuk small screens

## 🎨 Customization

### Colors
Edit `tailwind.config.js`:

```javascript
theme: {
  extend: {
    colors: {
      primary: '#3B82F6',      // Blue
      secondary: '#8B5CF6',    // Purple
      success: '#10B981',      // Green
      warning: '#F59E0B',      // Amber
      danger: '#EF4444',       // Red
    },
  },
}
```

### Navigation Menu
Edit `resources/views/layouts/sidebar.blade.php` untuk menambah/ubah menu items.

## 🔗 Routes & Navigation

| Route | Name | Deskripsi |
|-------|------|-----------|
| `/` | dashboard | Dashboard utama |
| `/projects` | projects.index | Daftar projects |
| `/quotations` | quotations.index | Daftar quotations |
| `/tasks` | tasks.index | Kanban board tasks |
| `/invoices` | invoices.index | Daftar invoices |
| `/reports` | reports.index | Reports & analytics |

## 📊 Data Integration Ready

Project saat ini menggunakan **dummy data**. Untuk integrasi dengan database:

### 1. Buat Models & Controllers
```bash
php artisan make:model Project -mcr
php artisan make:model Quotation -mcr
php artisan make:model Task -mcr
php artisan make:model Invoice -mcr
php artisan make:model Report -mcr
```

### 2. Update Routes (routes/web.php)
```php
Route::apiResources([
    'projects' => ProjectController::class,
    'quotations' => QuotationController::class,
    'tasks' => TaskController::class,
    'invoices' => InvoiceController::class,
]);
```

### 3. Update Views
Ganti dummy data dengan query dari database:

```blade
@forelse($projects as $project)
    <div class="bg-white rounded-lg shadow-md">
        <!-- project item -->
    </div>
@empty
    <p>No projects found</p>
@endforelse
```

## 🔐 Security Features (Rekomendasi)

- [ ] Implementasi authentication middleware
- [ ] Authorization policies untuk resource access
- [ ] CSRF protection (sudah otomatis di Laravel)
- [ ] SQL injection prevention (gunakan Eloquent)
- [ ] XSS protection
- [ ] Rate limiting untuk API endpoints

## 📈 Performance Tips

1. **Optimize Queries**: Gunakan eager loading
   ```php
   $projects = Project::with('client', 'tasks')->paginate(15);
   ```

2. **Caching**: Cache data yang jarang berubah
   ```php
   $projects = Cache::remember('projects', 3600, fn() => Project::all());
   ```

3. **Pagination**: Gunakan pagination untuk large datasets
   ```blade
   {{ $projects->links() }}
   ```

4. **Assets**: Sudah terkompresi & minified di build

## 📁 File Structure

```
d:\Magang\ERP-PM/
├── app/                      # Laravel app directory
│   ├── Models/              # Data models (ready)
│   ├── Http/
│   │   └── Controllers/     # Controllers (ready)
│   └── Policies/            # Authorization (ready)
├── routes/
│   └── web.php              # ✅ Routes defined
├── resources/
│   ├── views/               # ✅ 7 complete pages
│   ├── css/app.css         # ✅ Tailwind setup
│   └── js/app.js           # ✅ JavaScript
├── public/build/            # ✅ Compiled assets
├── database/
│   └── migrations/          # Ready untuk migrasi Anda
├── tailwind.config.js       # ✅ Tailwind config
├── vite.config.js          # ✅ Vite config
└── package.json            # ✅ Dependencies setup
```

## 🐛 Troubleshooting

### CSS tidak tampil
```bash
npm run build    # atau npm run dev untuk development
php artisan cache:clear
```

### Routes tidak ditemukan
```bash
php artisan route:list  # List semua routes
```

### Database error
```bash
php artisan migrate              # Run migrations
php artisan db:seed             # Seed data (jika ada)
```

## 🚀 Next Steps - Development Roadmap

1. **Database Setup** ✅ Migration files ready
   - Buat migration untuk setiap tabel
   - Setup relationships di Models

2. **Backend Logic** 🔄 Controllers ready
   - Implement CRUD operations
   - Add business logic

3. **Authentication** 🔒 Middleware ready
   - Setup login/register
   - Add authorization

4. **Real Data Integration** 📊
   - Connect views to database
   - Replace dummy data

5. **Validation & Error Handling** ✔️
   - Form validation
   - Error messages

6. **Testing** 🧪
   - Unit tests
   - Feature tests

7. **Deployment** 🚀
   - Staging environment
   - Production setup

## 📚 Useful Resources

- [Laravel Docs](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Vite Docs](https://vitejs.dev/)
- [Blade Templates](https://laravel.com/docs/blade)
- [Heroicons](https://heroicons.com/)

## 📋 Checklist Implementasi

**UI/Frontend** ✅
- [x] Main layout & navigation
- [x] Dashboard page
- [x] Projects listing
- [x] Quotations management
- [x] Tasks kanban board
- [x] Invoices listing
- [x] Reports analytics
- [x] Responsive design
- [x] Tailwind CSS styling

**Backend Integration** 🔄
- [ ] Database models
- [ ] Controllers
- [ ] API endpoints
- [ ] Form validation
- [ ] Authentication
- [ ] Authorization
- [ ] Data seeding

## 💡 Tips Development

- **Hot Reload**: Gunakan `npm run dev` untuk instant CSS/JS updates
- **Debug**: Gunakan `php artisan tinker` untuk testing queries
- **Database**: Gunakan `php artisan migrate:fresh --seed` untuk reset

---

**Status**: ✅ **UI/Layout Complete** - Ready untuk Backend Integration

**Last Updated**: November 4, 2025

**Created for**: ERP-PM Project Management System
