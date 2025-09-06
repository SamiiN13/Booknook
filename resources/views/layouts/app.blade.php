<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('books.create') }}">Share a Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('books.my-books') }}">My Books</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Requests
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('book-requests.my-requests') }}">My Requests</a></li>
                                <li><a class="dropdown-item" href="{{ route('book-requests.received') }}">Received Requests</a></li>
                            </ul>
                        </li>
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        @if(Auth::user()->email === 'admin@booknook.com')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
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
        // Multi-session support: Generate unique tab ID
        (function() {
            // Check if tab_id cookie exists
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            }
            
            // Generate unique tab ID if it doesn't exist
            if (!getCookie('tab_id')) {
                const tabId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                document.cookie = `tab_id=${tabId}; path=/; max-age=31536000`; // 1 year
            }
            
            // Add tab ID to all AJAX requests
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                const tabId = getCookie('tab_id');
                if (tabId && args[1]) {
                    args[1].headers = {
                        ...args[1].headers,
                        'X-Tab-ID': tabId
                    };
                } else if (tabId) {
                    args[1] = {
                        headers: {
                            'X-Tab-ID': tabId
                        }
                    };
                }
                return originalFetch.apply(this, args);
            };
        })();
    </script>
</body>
</html> 