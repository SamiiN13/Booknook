<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Booknook - Share Your Books')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
            color: #2c3e50 !important;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .book-card {
            transition: transform 0.3s ease;
            height: 100%;
        }
        .book-card:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background-color: #667eea;
            border-color: #667eea;
        }
        .btn-primary:hover {
            background-color: #5a6fd8;
            border-color: #5a6fd8;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('books.index') }}">
                <i class="fas fa-book-open me-2"></i>Booknook
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('books.index') }}">Browse Books</a>
                    </li>
                    <li class="nav-item" id="share-book-nav" style="display: none;">
                        <a class="nav-link" href="{{ route('books.create') }}">Share a Book</a>
                    </li>
                    <li class="nav-item" id="my-books-nav" style="display: none;">
                        <a class="nav-link" href="{{ route('books.my-books') }}">My Books</a>
                    </li>
                    <li class="nav-item dropdown" id="requests-nav" style="display: none;">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Requests
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('book-requests.my-requests') }}">My Requests</a></li>
                            <li><a class="dropdown-item" href="{{ route('book-requests.received') }}">Received Requests</a></li>
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item" id="login-nav">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item" id="register-nav">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                    <li class="nav-item" id="admin-nav" style="display: none;">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                    </li>
                    <li class="nav-item dropdown" id="user-nav" style="display: none;">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <span id="user-name">User</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button type="button" class="dropdown-item" onclick="logout()">Logout</button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Booknook</h5>
                    <p>Share your books with the community</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2024 Booknook. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Multi-tab session management
        class TabSessionManager {
            constructor() {
                this.tabId = this.generateTabId();
                this.init();
            }

            generateTabId() {
                return 'tab_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            }

            init() {
                // Store tab ID in localStorage
                localStorage.setItem('current_tab_id', this.tabId);
                
                // Check for existing session
                this.checkSession();
                
                // Listen for storage changes (other tabs)
                window.addEventListener('storage', (e) => {
                    if (e.key === 'current_tab_id') {
                        this.checkSession();
                    }
                });
            }

            checkSession() {
                const sessionData = localStorage.getItem('tab_session_' + this.tabId);
                if (sessionData) {
                    const session = JSON.parse(sessionData);
                    this.updateUI(session);
                } else {
                    this.updateUI(null);
                }
            }

            setSession(userData) {
                const sessionData = {
                    user: userData,
                    timestamp: Date.now()
                };
                localStorage.setItem('tab_session_' + this.tabId, JSON.stringify(sessionData));
                this.updateUI(sessionData);
            }

            clearSession() {
                localStorage.removeItem('tab_session_' + this.tabId);
                this.updateUI(null);
            }

            updateUI(session) {
                const isLoggedIn = session && session.user;
                
                // Show/hide navigation elements
                document.getElementById('login-nav').style.display = isLoggedIn ? 'none' : 'block';
                document.getElementById('register-nav').style.display = isLoggedIn ? 'none' : 'block';
                document.getElementById('user-nav').style.display = isLoggedIn ? 'block' : 'none';
                document.getElementById('share-book-nav').style.display = isLoggedIn ? 'block' : 'none';
                document.getElementById('my-books-nav').style.display = isLoggedIn ? 'block' : 'none';
                document.getElementById('requests-nav').style.display = isLoggedIn ? 'block' : 'none';
                
                if (isLoggedIn) {
                    document.getElementById('user-name').textContent = session.user.name;
                    
                    // Show admin nav if admin
                    if (session.user.email === 'admin@booknook.com') {
                        document.getElementById('admin-nav').style.display = 'block';
                    } else {
                        document.getElementById('admin-nav').style.display = 'none';
                    }
                } else {
                    document.getElementById('admin-nav').style.display = 'none';
                }
            }
        }

        // Initialize session manager
        const sessionManager = new TabSessionManager();

        // Override form submissions to handle login
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form[action*="login"]');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const email = formData.get('email');
                    const password = formData.get('password');
                    
                    // Simulate login (in real app, this would be an AJAX call)
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            sessionManager.setSession(data.user);
                            window.location.href = data.redirect || '/dashboard';
                        } else {
                            alert(data.message || 'Login failed');
                        }
                    })
                    .catch(error => {
                        console.error('Login error:', error);
                        // Fallback to normal form submission
                        this.submit();
                    });
                });
            }
        });

        // Logout function
        function logout() {
            fetch('/logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(() => {
                sessionManager.clearSession();
                window.location.href = '/';
            })
            .catch(() => {
                // Fallback
                sessionManager.clearSession();
                window.location.href = '/';
            });
        }
    </script>
</body>
</html>