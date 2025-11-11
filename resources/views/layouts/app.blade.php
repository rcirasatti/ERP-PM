<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ERP PM Dashboard') - ERP Project Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Add Choices.js for searchable selects -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    
    <!-- Custom Scrollbar Styles -->
    <style>
        /* Webkit browsers (Chrome, Safari, Edge) */
        nav::-webkit-scrollbar {
            width: 6px;
        }
        
        nav::-webkit-scrollbar-track {
            background: #1e293b;
            border-radius: 3px;
        }
        
        nav::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 3px;
        }
        
        nav::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            @include('layouts.header')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-auto">
                <div class="container mx-auto px-6 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="hidden fixed inset-0 z-40 flex items-center justify-center backdrop-blur-sm bg-black/50">
        <div class="bg-white rounded-lg shadow-xl max-w-sm w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2" id="confirmTitle">Konfirmasi</h3>
            <p class="text-gray-600 mb-6" id="confirmMessage">Apakah Anda yakin?</p>
            <div class="flex gap-3">
                <button onclick="confirmCancel()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                    Batal
                </button>
                <button onclick="confirmOK()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        let confirmCallback = null;

        // Toggle sidebar untuk mobile
        const toggleSidebar = () => {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }

        // Confirmation dialog
        function showConfirm(message, title = 'Konfirmasi', callback) {
            confirmCallback = callback;
            document.getElementById('confirmTitle').textContent = title;
            document.getElementById('confirmMessage').textContent = message;
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function confirmOK() {
            document.getElementById('confirmModal').classList.add('hidden');
            if (confirmCallback) confirmCallback();
        }

        function confirmCancel() {
            document.getElementById('confirmModal').classList.add('hidden');
            confirmCallback = null;
        }

        // Toast notification function
        function showToast(message, type = 'success', duration = 3000) {
            const container = document.getElementById('toastContainer');
            
            // Determine colors based on type
            let bgColor = 'bg-green-500';
            let icon = '✓';
            
            if (type === 'error') {
                bgColor = 'bg-red-500';
                icon = '✕';
            } else if (type === 'warning') {
                bgColor = 'bg-yellow-500';
                icon = '!';
            } else if (type === 'info') {
                bgColor = 'bg-blue-500';
                icon = 'ℹ';
            }
            
            const toast = document.createElement('div');
            toast.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3 animate-slide-in`;
            toast.innerHTML = `
                <span class="text-lg font-bold">${icon}</span>
                <span>${message}</span>
            `;
            
            container.appendChild(toast);
            
            // Remove toast after duration
            setTimeout(() => {
                toast.classList.add('animate-fade-out');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, duration);
        }

        // Auto show session flash messages
        @if (session('success'))
            showToast('{{ session('success') }}', 'success', 3000);
        @endif
        
        @if (session('error'))
            showToast('{{ session('error') }}', 'error', 3000);
        @endif
        
        @if (session('warning'))
            showToast('{{ session('warning') }}', 'warning', 3000);
        @endif
        
        @if (session('info'))
            showToast('{{ session('info') }}', 'info', 3000);
        @endif

        // Initialize Choices.js for all selects with class 'searchable-select'
        document.querySelectorAll('.searchable-select').forEach(select => {
            new Choices(select, {
                removeItemButton: true,
                searchResultLimit: 6,
                shouldSort: true,
                placeholder: true,
                noResultsText: 'Tidak ada hasil pencarian',
                noChoicesText: 'Tidak ada opsi tersedia',
                itemSelectText: 'Tekan Enter untuk memilih'
            });
        });
    </script>

    <style>
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(400px);
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-in-out;
        }
        
        .animate-fade-out {
            animation: fadeOut 0.3s ease-in-out;
        }

        /* Hide scrollbar but keep scrolling */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</body>
</html>
