<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Dashboard'); ?> - Haryadi System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar:hover {
            width: 16rem;
        }
        .sidebar-text {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar:hover .sidebar-text {
            opacity: 1;
        }
        .sidebar-submenu {
            display: none;
        }
        .sidebar-group:hover .sidebar-submenu {
            display: block;
        }
        .active-menu {
            background-color: #3b82f6;
            color: white;
        }
        .active-menu:hover {
            background-color: #3b82f6 !important;
            color: white !important;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar & Main Content Container -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-white shadow-lg w-16 hover:w-64 transition-all duration-300 overflow-hidden fixed h-full z-40">
            <div class="p-4 border-b">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-sm">H</span>
                    </div>
                    <span class="sidebar-text ml-3 font-semibold text-gray-800 whitespace-nowrap">Haryadi System</span>
                </div>
            </div>

            <nav class="mt-6">
                <!-- Dashboard -->
                <a href="/layouts/dashboard" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 <?php echo (isset($title) && $title === 'Dashboard') ? 'active-menu' : ''; ?>">
                    <i class="fas fa-tachometer-alt w-6 text-center"></i>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Dashboard</span>
                </a>

                <!-- Users -->
                <div class="sidebar-group">
                    <a href="#" class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-users w-6 text-center"></i>
                            <span class="sidebar-text ml-3 whitespace-nowrap">Manajemen User</span>
                        </div>
                        <i class="sidebar-text fas fa-chevron-down text-xs"></i>
                    </a>
                    <div class="sidebar-submenu bg-gray-50">
                        <a href="#" class="block px-4 py-2 pl-12 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">Data User</a>
                        <a href="#" class="block px-4 py-2 pl-12 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">Tambah User</a>
                        <a href="#" class="block px-4 py-2 pl-12 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">Role & Permission</a>
                    </div>
                </div>

                <!-- Products -->
                <div class="sidebar-group">
                    <a href="#" class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-box w-6 text-center"></i>
                            <span class="sidebar-text ml-3 whitespace-nowrap">Produk</span>
                        </div>
                        <i class="sidebar-text fas fa-chevron-down text-xs"></i>
                    </a>
                    <div class="sidebar-submenu bg-gray-50">
                        <a href="#" class="block px-4 py-2 pl-12 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">Daftar Produk</a>
                        <a href="#" class="block px-4 py-2 pl-12 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">Tambah Produk</a>
                        <a href="#" class="block px-4 py-2 pl-12 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">Kategori</a>
                    </div>
                </div>

                <!-- Orders -->
                <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-shopping-cart w-6 text-center"></i>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Pesanan</span>
                    <span class="sidebar-text ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">5</span>
                </a>

                <!-- Reports -->
                <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-chart-bar w-6 text-center"></i>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Laporan</span>
                </a>

                <!-- Settings -->
                <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-cog w-6 text-center"></i>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Pengaturan</span>
                </a>
            </nav>

            <!-- Logout -->
            <div class="absolute bottom-0 w-full p-4 border-t">
                <a href="/logout" class="flex items-center px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200 rounded">
                    <i class="fas fa-sign-out-alt w-6 text-center"></i>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Logout</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="ml-16 flex-1 transition-all duration-300 min-w-0">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-bars text-gray-600"></i>
                        </button>
                        <h1 class="ml-4 text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($title ?? 'Dashboard'); ?></h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 relative">
                                <i class="fas fa-bell text-gray-600"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">
                                        <?php 
                                        if (isset($user['name'])) {
                                            echo substr($user['name'], 0, 1);
                                        } else {
                                            echo 'A';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-800">
                                        <?php echo htmlspecialchars($user['name'] ?? 'Administrator'); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?php echo htmlspecialchars($user['role'] ?? 'Admin'); ?>
                                    </p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border hidden z-50">
                                <div class="p-4 border-b">
                                    <p class="text-sm font-medium text-gray-800">
                                        <?php echo htmlspecialchars($user['name'] ?? 'Administrator'); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?php echo htmlspecialchars($user['email'] ?? 'admin@haryadi.com'); ?>
                                    </p>
                                </div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-user mr-2"></i>Profil Saya
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-cog mr-2"></i>Pengaturan
                                </a>
                                <div class="border-t">
                                    <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="p-6">
                <?php 
                // Include content based on route
                if (isset($content)) {
                    echo $content;
                } else {
                    // Default dashboard content - simplified for testing
                    ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Selamat Datang di Dashboard!</h2>
                        <p class="text-gray-600 mb-6">Halo <strong><?php echo htmlspecialchars($user['name'] ?? 'Administrator'); ?></strong>, selamat datang kembali di sistem Haryadi.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <div class="flex items-center">
                                    <i class="fas fa-users text-blue-600 text-xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-blue-600">Total Users</p>
                                        <p class="text-2xl font-bold text-blue-800">1,248</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <div class="flex items-center">
                                    <i class="fas fa-shopping-cart text-green-600 text-xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-green-600">Total Orders</p>
                                        <p class="text-2xl font-bold text-green-800">356</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                                <div class="flex items-center">
                                    <i class="fas fa-chart-line text-purple-600 text-xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-purple-600">Revenue</p>
                                        <p class="text-2xl font-bold text-purple-800">$24,580</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Dashboard berhasil dimuat. Sistem berjalan dengan normal.
                            </p>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </main>
        </div>
    </div>

    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.ml-16');
            
            if (sidebar.classList.contains('w-16')) {
                sidebar.classList.remove('w-16');
                sidebar.classList.add('w-64');
                mainContent.classList.remove('ml-16');
                mainContent.classList.add('ml-64');
            } else {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-16');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-16');
            }
        });

        // User Dropdown
        document.getElementById('userMenuButton').addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const userMenu = document.getElementById('userMenuButton');
            
            if (!userMenu.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Sidebar group toggle
        document.querySelectorAll('.sidebar-group > a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = this.parentNode.querySelector('.sidebar-submenu');
                submenu.classList.toggle('hidden');
            });
        });

        // Prevent sidebar from closing when clicking inside
        document.querySelector('.sidebar').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
</body>
</html>