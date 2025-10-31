

<?php $sections['title'] = 'Haryadi — Framework PHP'; ?>

<?php ob_start(); ?>
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                
                <div class="logo">
                    <img src="/assets/images/ht.png" alt="Haryadi Logo" class="logo">
                </div>
            </div>
            
            <h1 class="title">Haryadi Framework</h1>
            <p class="subtitle">
                Framework PHP modern dan ringan untuk pengembang web. 
                Sintaks yang ekspresif, elegan, dan alat yang powerful untuk membangun aplikasi menakjubkan.
            </p>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Framework Information -->
            <div class="framework-info">
                <div class="info-section">
                    <h3>📊 Informasi Sistem</h3>
                    <ul class="info-list">
                        <li>
                            <span>Versi Framework</span>
                            <span class="badge">v1.0.0</span>
                        </li>
                        <li>
                            <span>Versi PHP</span>
                            <span class="badge success"><?= PHP_VERSION ?></span>
                        </li>
                        <li>
                            <span>Server Software</span>
                            <span class="badge"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Tidak Diketahui' ?></span>
                        </li>
                        <li>
                            <span>Environment</span>
                            <span class="badge warning"><?= env('APP_ENV', 'local') ?></span>
                        </li>
                        <li>
                            <span>Mode Debug</span>
                            <span class="badge <?= env('APP_DEBUG', false) ? 'danger' : 'success' ?>">
                                <?= env('APP_DEBUG', false) ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="info-section">
                    <h3>🔧 Konfigurasi</h3>
                    <ul class="info-list">
                        <li>
                            <span>Database</span>
                            <span class="badge <?= env('DB_HOST') ? 'success' : 'warning' ?>">
                                <?= env('DB_HOST') ? 'Terkonfigurasi' : 'Belum Dikonfigurasi' ?>
                            </span>
                        </li>
                        <li>
                            <span>Cache Driver</span>
                            <span class="badge"><?= env('CACHE_DRIVER', 'file') ?></span>
                        </li>
                        <li>
                            <span>Session Driver</span>
                            <span class="badge"><?= env('SESSION_DRIVER', 'file') ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="quick-links">
                <h3 style="color: var(--primary); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    ⚡ Mulai Cepat
                </h3>
                <div class="links-grid">
                    <a href="/docs" class="link-card">
                        <div class="link-icon">📚</div>
                        <div class="link-content">
                            <h4>Dokumentasi</h4>
                            <p>Panduan lengkap dan referensi API</p>
                        </div>
                    </a>
                    
                    <a href="/api/health" class="link-card">
                        <div class="link-icon">❤️</div>
                        <div class="link-content">
                            <h4>Status API</h4>
                            <p>Periksa kesehatan dan endpoint API</p>
                        </div>
                    </a>
                    
                    <a href="/demo" class="link-card">
                        <div class="link-icon">🎯</div>
                        <div class="link-content">
                            <h4>Demo</h4>
                            <p>Lihat fitur framework dalam aksi</p>
                        </div>
                    </a>
                    
                    <a href="https://github.com/yourusername/haryadi-framework" class="link-card" target="_blank">
                        <div class="link-icon">🐙</div>
                        <div class="link-content">
                            <h4>GitHub</h4>
                            <p>Lihat kode sumber dan berkontribusi</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="features">
            <h2>✨ Mengapa Memilih Haryadi?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h4>Super Cepat</h4>
                    <p>Dioptimalkan untuk performa dengan overhead minimal</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h4>Mudah Digunakan</h4>
                    <p>Sintaks intuitif dan dokumentasi yang komprehensif</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔧</div>
                    <h4>Fleksibel</h4>
                    <p>Arsitektur modular yang tumbuh sesuai kebutuhan Anda</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>
                    <h4>Aman</h4>
                    <p>Fitur keamanan bawaan dan praktik terbaik</p>
                </div>
            </div>
        </div>

        <!-- Documentation Section -->
        <div class="docs-section">
            <h2>🚀 Memulai</h2>
            <p style="text-align: center; margin-bottom: 2rem; color: var(--text-light);">
                Mulai bangun ide hebat Anda berikutnya dengan Haryadi Framework
            </p>
            
            <div class="code-example">
                <span class="code-comment">// Buat route baru</span><br>
                <span class="code-keyword">Router</span>::<span class="code-keyword">get</span>(<span class="code-string">'/halo'</span>, <span class="code-keyword">function</span>($request) {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Halo Dunia!"</span>;<br>
                });<br><br>
                
                <span class="code-comment">// Buat controller</span><br>
                <span class="code-keyword">class</span> <span class="code-keyword">UserController</span> {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">public function</span> index($request) {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-keyword">view</span>(<span class="code-string">'users'</span>, [<span class="code-string">'users'</span> => $users]);<br>
                &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                }
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <a href="/docs" class="btn">
                    📖 Baca Dokumentasi
                </a>
                <a href="/demo" class="btn btn-outline" style="margin-left: 1rem;">
                    🎯 Lihat Demo
                </a>
            </div>
        </div>

    </div>
<?php $sections['content'] = ob_get_clean(); ?>
<?php include 'D:\\haryadi/storage/cache/views/b65cd943f5518a59f4bd171f00c79f3d.php'; ?>