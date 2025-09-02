<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Tools Suite - Enhance Your Media</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #f5f5f5;
            --card-bg: #ffffff;
            --primary-color: #212529;
            --secondary-color: #6c757d;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            --success-color: #198754;
            --processing-color: #0d6efd;
            --header-bg: rgba(255, 255, 255, 0.95);
            --header-height: 70px;
            --primary-gradient: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            --feature-primary: #2563eb;
            --feature-secondary: #4f46e5;
            --light-bg: #f8fafc;
            --dark-text: #1e293b;
            --light-text: #64748b;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            padding-top: var(--header-height);
        }

        /* Header Styles */
        .header-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--header-bg);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
            z-index: 1000;
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .logo-icon {
            background: var(--primary-gradient);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .logo-icon i {
            color: white;
            font-size: 1.2rem;
        }

        .nav-links {
            display: flex;
            margin-left: 40px;
            position: relative;
        }

        .nav-link {
            text-decoration: none;
            color: var(--secondary-color);
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            margin: 0 5px;
            position: relative;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.1);
        }

        /* Tools Dropdown Menu */
        .tools-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            width: 800px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 25px;
            display: none;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            z-index: 1000;
            /* margin-top: 15px; */
        }

        /* .tools-dropdown::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 30px;
            width: 20px;
            height: 20px;
            background: white;
            transform: rotate(45deg);
            border-radius: 4px;
        } */

        .tools-dropdown.active {
            display: grid;
            animation: fadeIn 0.3s ease;
        }

        .tool-dropdown-item {
            display: flex;
            align-items: flex-start;
            padding: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--dark-text);
        }

        .tool-dropdown-item:hover {
            background: rgba(37, 99, 235, 0.05);
            transform: translateY(-2px);
        }

        .tool-dropdown-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .tool-dropdown-icon i {
            color: white;
            font-size: 1.2rem;
        }

        .tool-dropdown-content h4 {
            font-size: 1rem;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .tool-dropdown-content p {
            font-size: 0.85rem;
            color: var(--light-text);
            line-height: 1.4;
        }

        .header-actions {
            display: flex;
            align-items: center;
            margin-left: auto;
            gap: 15px;
        }

        .btn-login {
            background: transparent;
            border: 1px solid #dee2e6;
            color: var(--primary-color);
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #f8f9fa;
            border-color: #ced4da;
        }

        .btn-try-pro {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-try-pro:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 117, 252, 0.3);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* .mobile-menu-btn {
            display: none;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: var(--primary-color);
        } */

        /* Hero Section */
        .hero-section {
            padding: 80px 0;
            background: linear-gradient(135deg, rgba(106, 17, 203, 0.05) 0%, rgba(37, 117, 252, 0.05) 100%);
            text-align: center;
            margin-bottom: 60px;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--secondary-color);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(37, 117, 252, 0.2);
        }

        .btn-outline-primary {
            background: transparent;
            color: #0d6efd;
            border: 2px solid #0d6efd;
            padding: 10px 28px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: rgba(13, 110, 253, 0.1);
            transform: translateY(-3px);
        }

        /* Tools Section */
        .tools-section {
            padding: 60px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark-text);
        }

        .section-title p {
            font-size: 1.1rem;
            color: var(--light-text);
            max-width: 600px;
            margin: 0 auto;
        }

        .tool-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .tool-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .tool-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .tool-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .tool-card:hover .tool-image img {
            transform: scale(1.05);
        }

        .tool-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .tool-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tool-icon i {
            color: white;
            font-size: 1.8rem;
        }

        .tool-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark-text);
        }

        .tool-description {
            color: var(--light-text);
            margin-bottom: 20px;
            line-height: 1.6;
            flex-grow: 1;
        }

        .tool-button {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .tool-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 117, 252, 0.3);
            color: white;
        }

        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: var(--light-bg);
        }

        .feature-item {
            text-align: center;
            padding: 30px;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }

        .feature-icon i {
            color: white;
            font-size: 2.5rem;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark-text);
        }

        .feature-description {
            color: var(--light-text);
            line-height: 1.6;
        }

        /* Testimonials */
        .testimonials-section {
            padding: 80px 0;
        }

        .testimonial-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin: 15px;
            position: relative;
        }

        .testimonial-text {
            font-style: italic;
            color: var(--dark-text);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
        }

        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .author-info h4 {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark-text);
        }

        .author-info p {
            color: var(--light-text);
            font-size: 0.9rem;
        }

        /* Footer */
        .footer {
            background: var(--dark-text);
            color: white;
            padding: 60px 0 30px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .footer-logo-icon {
            background: var(--primary-gradient);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .footer-logo-icon i {
            color: white;
            font-size: 1.2rem;
        }

        .footer-logo-text {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .footer-description {
            color: #94a3b8;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .footer-title {
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary-gradient);
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid #334155;
            padding-top: 30px;
            margin-top: 60px;
            text-align: center;
            color: #94a3b8;
        }

        /* User Popup */
        .user-popup {
            position: absolute;
            top: 90px;
            right: 40px;
            width: 320px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .user-popup.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .popup-header {
            padding: 25px;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border-radius: 18px 18px 0 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .popup-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 24px;
            color: #2563eb;
        }

        .user-info {
            color: white;
        }

        .user-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .user-email {
            font-size: 14px;
            opacity: 0.9;
        }

        .popup-body {
            padding: 25px;
        }

        .popup-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .popup-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 8px;
        }

        .popup-item:hover {
            background: #f7fafc;
        }

        .popup-item i {
            width: 30px;
            color: #4a5568;
            font-size: 18px;
        }

        .popup-item span {
            font-size: 16px;
            font-weight: 500;
            color: #2d3748;
        }

        .popup-footer {
            padding: 20px 25px;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: center;
        }

        .logout-btn {
            background: #f8fafc;
            color: #e53e3e;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #fff5f5;
            transform: translateY(-2px);
        }
.mobile-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .nav-links {
                display: none;
            }

            .header-actions .btn-text {
                display: none;
            }
/*
            .mobile-menu-btn {
                display: block;
                margin-left: auto;
                margin-right: 15px;
            } */

            .hero-title {
                font-size: 2.8rem;
            }

            .tools-dropdown {
                width: 600px;
                left: -100px;
            }

            .user-popup {
                right: 20px;
                width: 300px;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .tool-card {
                margin-bottom: 30px;
            }

            .tools-dropdown {
                width: 400px;
                grid-template-columns: 1fr;
                left: -150px;
            }
        }

        @media (max-width: 576px) {
            :root {
                --header-height: 60px;
            }

            .hero-title {
                font-size: 1.8rem;
            }

            .logo span {
                display: none;
            }

            .logo-icon {
                margin-right: 0;
            }

            .btn-try-pro {
                padding: 8px;
                font-size: 0;
            }

            .btn-try-pro i {
                font-size: 1rem;
                margin: 0;
            }

            .tools-dropdown {
                width: 320px;
                left: -180px;
            }

            .user-popup {
                width: calc(100% - 30px);
                right: 15px;
                left: 15px;
                top: 80px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header-container">
        <a href="#" class="logo">
            <div class="logo-icon">
                <i class="fas fa-magic"></i>
            </div>
            <span>AI Tools</span>
        </a>

        <div class="nav-links">
            <a href="#" class="nav-link active">Home</a>

            <!-- Tools Link with Dropdown -->
            <a href="#tools" class="nav-link" id="toolsLink">Tools<i class="fas fa-caret-down ms-1"></i></a>
            <div class="tools-dropdown" id="toolsDropdown">
                <a href="bg-remove.html" class="tool-dropdown-item">
                    <div class="tool-dropdown-icon">
                        <i class="fas fa-object-ungroup"></i>
                    </div>
                    <div class="tool-dropdown-content">
                        <h4>Background Remover</h4>
                        <p>Remove backgrounds from images instantly with AI</p>
                    </div>
                </a>

                <a href="photo-enhance.html" class="tool-dropdown-item">
                    <div class="tool-dropdown-icon">
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </div>
                    <div class="tool-dropdown-content">
                        <h4>Photo Enhance</h4>
                        <p>Improve image quality and adjust colors automatically</p>
                    </div>
                </a>

                <a href="photo-swap.html" class="tool-dropdown-item">
                    <div class="tool-dropdown-icon">
                        <i class="fas fa-people-arrows"></i>
                    </div>
                    <div class="tool-dropdown-content">
                        <h4>Photo Swap</h4>
                        <p>Swap faces in photos with incredible accuracy</p>
                    </div>
                </a>

                <a href="video-record.html" class="tool-dropdown-item">
                    <div class="tool-dropdown-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="tool-dropdown-content">
                        <h4>Video Record</h4>
                        <p>Record high-quality videos directly in your browser</p>
                    </div>
                </a>

                <a href="video-enhance.html" class="tool-dropdown-item">
                    <div class="tool-dropdown-icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="tool-dropdown-content">
                        <h4>Video Enhance</h4>
                        <p>Improve video quality and stabilize shaky footage</p>
                    </div>
                </a>

                <a href="all-tools.html" class="tool-dropdown-item">
                    <div class="tool-dropdown-icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <div class="tool-dropdown-content">
                        <h4>All Tools</h4>
                        <p>Access our complete suite of AI-powered media tools</p>
                    </div>
                </a>
            </div>

            <a href="#features" class="nav-link">Features</a>
            <a href="#" class="nav-link">Pricing</a>
        </div>

        <div class="header-actions">
            <button class="btn-login">
                <i class="fas fa-user"></i>
                <span class="btn-text">Sign In</span>
            </button>
            <button class="btn-try-pro">
                <i class="fas fa-star"></i>
                <span class="btn-text">Try Pro</span>
            </button>


            <div class="user-avatar" id="userAvatar">JS</div>

        </div>

        {{-- <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button> --}}

        <!-- User Popup -->
        <div class="user-popup" id="userPopup">
            <div class="popup-header">
                <!-- Mobile Close Button -->
                <div class="mobile-close-btn" id="mobileProfilePopUpCloseBtn">
                    <i class="fas fa-times"></i>
                </div>

                <div class="popup-avatar">JS</div>
                <div class="user-info">
                    <div class="user-name">John Smith</div>
                    <div class="user-email">john.smith@example.com</div>
                </div>
            </div>

            <div class="popup-body">
                <div class="popup-section">
                    <div class="section-title">Account</div>
                    <div class="popup-item">
                        <i class="fas fa-user"></i>
                        <span>Profile Settings</span>
                    </div>
                    <div class="popup-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Security</span>
                    </div>
                    <div class="popup-item">
                        <i class="fas fa-credit-card"></i>
                        <span>Billing & Plans</span>
                    </div>
                </div>

                <div class="popup-section">
                    <div class="section-title">Resources</div>
                    <div class="popup-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Help Center</span>
                    </div>
                    <div class="popup-item">
                        <i class="fas fa-comments"></i>
                        <span>Community</span>
                    </div>
                    <div class="popup-item">
                        <i class="fas fa-lightbulb"></i>
                        <span>Feature Requests</span>
                    </div>
                </div>
            </div>

            <div class="popup-footer">
                <button class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Log Out
                </button>
            </div>
        </div>

    </header>




    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Transform Your Media with AI Power</h1>
                <p class="hero-subtitle">Enhance photos, remove backgrounds, swap faces, record and improve videos - all
                    with our powerful AI tools. Simple, fast, and stunning results.</p>
                <div class="hero-buttons">
                    <button class="btn btn-primary">Get Started Free</button>
                    <button class="btn btn-outline-primary">View Demo</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Tools Section -->
    <section class="tools-section" id="tools">
        <div class="container">
            <div class="section-title">
                <h2>Our AI Tools</h2>
                <p>Powerful tools to transform your photos and videos</p>
            </div>

            <div class="row">
                <!-- Background Remover Tool -->
                <div class="col-md-4 mb-4">
                    <div class="tool-card">
                        <div class="tool-image">
                            <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="Background Remover">
                        </div>
                        <div class="tool-content">
                            <div class="tool-icon">
                                <i class="fas fa-object-ungroup"></i>
                            </div>
                            <h3 class="tool-title">Background Remover</h3>
                            <p class="tool-description">Remove backgrounds from your images instantly with our advanced
                                AI technology. Perfect for product photos, portraits, and more.</p>
                            <a href="bg-remove.html" class="tool-button">Use Tool</a>
                        </div>
                    </div>
                </div>

                <!-- Photo Enhance Tool -->
                <div class="col-md-4 mb-4">
                    <div class="tool-card">
                        <div class="tool-image">
                            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="Photo Enhance">
                        </div>
                        <div class="tool-content">
                            <div class="tool-icon">
                                <i class="fas fa-wand-magic-sparkles"></i>
                            </div>
                            <h3 class="tool-title">Photo Enhance</h3>
                            <p class="tool-description">Improve image quality, adjust colors, remove imperfections, and
                                make your photos look professionally edited with one click.</p>
                            <a href="photo-enhance.html" class="tool-button">Use Tool</a>
                        </div>
                    </div>
                </div>

                <!-- Photo Swap Tool -->
                <div class="col-md-4 mb-4">
                    <div class="tool-card">
                        <div class="tool-image">
                            <img src="https://images.unsplash.com/photo-1554080353-a576cf803bda?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="Photo Swap">
                        </div>
                        <div class="tool-content">
                            <div class="tool-icon">
                                <i class="fas fa-people-arrows"></i>
                            </div>
                            <h3 class="tool-title">Photo Swap</h3>
                            <p class="tool-description">Swap faces in photos with incredible accuracy. Create fun images
                                by replacing faces while maintaining natural lighting and perspective.</p>
                            <a href="photo-swap.html" class="tool-button">Use Tool</a>
                        </div>
                    </div>
                </div>

                <!-- Video Record Tool -->
                <div class="col-md-4 mb-4">
                    <div class="tool-card">
                        <div class="tool-image">
                            <img src="https://images.unsplash.com/photo-1574717024453-354056aafa98?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="Video Record">
                        </div>
                        <div class="tool-content">
                            <div class="tool-icon">
                                <i class="fas fa-video"></i>
                            </div>
                            <h3 class="tool-title">Video Record</h3>
                            <p class="tool-description">Record high-quality videos directly in your browser with our
                                easy-to-use recording tool. No downloads or installations required.</p>
                            <a href="video-record.html" class="tool-button">Use Tool</a>
                        </div>
                    </div>
                </div>

                <!-- Video Enhance Tool -->
                <div class="col-md-4 mb-4">
                    <div class="tool-card">
                        <div class="tool-image">
                            <img src="https://images.unsplash.com/photo-1528353518104-dbd48bee7bc4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="Video Enhance">
                        </div>
                        <div class="tool-content">
                            <div class="tool-icon">
                                <i class="fas fa-film"></i>
                            </div>
                            <h3 class="tool-title">Video Enhance</h3>
                            <p class="tool-description">Improve video quality, stabilize shaky footage, enhance colors,
                                and upscale resolution with our AI-powered video enhancement tool.</p>
                            <a href="video-enhance.html" class="tool-button">Use Tool</a>
                        </div>
                    </div>
                </div>

                <!-- All Tools -->
                <div class="col-md-4 mb-4">
                    <div class="tool-card">
                        <div class="tool-image">
                            <img src="https://images.unsplash.com/photo-1535223289827-42f1e9919769?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="All Tools">
                        </div>
                        <div class="tool-content">
                            <div class="tool-icon">
                                <i class="fas fa-th-large"></i>
                            </div>
                            <h3 class="tool-title">All Tools</h3>
                            <p class="tool-description">Access our complete suite of AI-powered media tools in one
                                place. Perfect for creators, marketers, and photographers.</p>
                            <a href="all-tools.html" class="tool-button">Explore All</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose Our Tools</h2>
                <p>Powerful features designed for creators</p>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="feature-title">Fast Processing</h3>
                        <p class="feature-description">Our AI algorithms work in seconds to process your media, saving
                            you hours of manual editing work.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Privacy First</h3>
                        <p class="feature-description">Your media is never stored on our servers. All processing happens
                            securely in your browser.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">High Quality Results</h3>
                        <p class="feature-description">Get professional-grade results with our advanced AI technology
                            that learns from millions of examples.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Mobile Friendly</h3>
                        <p class="feature-description">Use our tools on any device - desktop, tablet, or smartphone. No
                            downloads or installations required.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-cloud-download-alt"></i>
                        </div>
                        <h3 class="feature-title">Easy Export</h3>
                        <p class="feature-description">Download your processed media in high-quality formats, ready to
                            use on social media or professional projects.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">User-Friendly</h3>
                        <p class="feature-description">Intuitive interface designed for both beginners and
                            professionals. No technical skills required.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-title">
                <h2>What Our Users Say</h2>
                <p>Join thousands of satisfied creators</p>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"The background removal tool saved me hours of work. As an
                            e-commerce seller, I need clean product images, and this tool delivers perfect results every
                            time."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sarah Johnson">
                            </div>
                            <div class="author-info">
                                <h4>Sarah Johnson</h4>
                                <p>E-commerce Entrepreneur</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"I've tried many photo enhancement tools, but this one stands out.
                            The AI understands exactly what my photos need to make them pop without looking
                            overprocessed."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen">
                            </div>
                            <div class="author-info">
                                <h4>Michael Chen</h4>
                                <p>Professional Photographer</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"The video enhancement tool transformed my old family videos. The
                            quality improvement is incredible - it's like watching them for the first time again."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Emma Rodriguez">
                            </div>
                            <div class="author-info">
                                <h4>Emma Rodriguez</h4>
                                <p>Content Creator</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="footer-logo">
                        <div class="footer-logo-icon">
                            <i class="fas fa-magic"></i>
                        </div>
                        <div class="footer-logo-text">AI Tools</div>
                    </div>
                    <p class="footer-description">Transform your media with our powerful AI-powered tools. Simple, fast,
                        and stunning results for creators of all levels.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 mb-5 mb-md-0">
                    <h3 class="footer-title">Tools</h3>
                    <ul class="footer-links">
                        <li><a href="bg-remove.html">Background Remover</a></li>
                        <li><a href="photo-enhance.html">Photo Enhance</a></li>
                        <li><a href="photo-swap.html">Photo Swap</a></li>
                        <li><a href="video-record.html">Video Record</a></li>
                        <li><a href="video-enhance.html">Video Enhance</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4 mb-5 mb-md-0">
                    <h3 class="footer-title">Company</h3>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h3 class="footer-title">Support</h3>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h3 class="footer-title">Resources</h3>
                    <ul class="footer-links">
                        <li><a href="#">Tutorials</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">API</a></li>
                        <li><a href="#">Community</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2023 AI Tools. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            const toolsLink = $('#toolsLink');
            const toolsDropdown = $('#toolsDropdown');

            // header css start
            const userPopup = document.getElementById('userPopup');
            const mobileProfilePopUpCloseBtn = document.getElementById('mobileProfilePopUpCloseBtn');

            // Toggle popup when clicking user avatar
            userAvatar.addEventListener('click', function (e) {
                e.stopPropagation();
                userPopup.classList.toggle('active');
                // popupOverlay.classList.toggle('active');
            });

            // Close popup when clicking outside
            document.addEventListener('click', function (e) {
                if (userPopup.classList.contains('active') &&
                    !userPopup.contains(e.target) &&
                    e.target !== userAvatar) {
                    closePopup();
                }
            });
            // Prevent closing when clicking inside popup
            userPopup.addEventListener('click', function (e) {
                e.stopPropagation();
            });
            // Close popup function
            function closePopup() {
                userPopup.classList.remove('active');
                popupOverlay.classList.remove('active');
            }


            // Close popup when clicking mobile close button
            mobileProfilePopUpCloseBtn.addEventListener('click', function () {
                closePopup();
            });

            // header css end

            // Show dropdown on hover
            toolsLink.on('mouseenter', function () {
                toolsDropdown.addClass('active');
            });

            // Hide dropdown when mouse leaves the dropdown area
            toolsDropdown.on('mouseleave', function () {
                toolsDropdown.removeClass('active');
            });

            // Keep dropdown visible when hovering over the tools link
            toolsLink.on('mouseleave', function (e) {
                // Check if mouse is moving to dropdown
                if (e.relatedTarget &&
                    (e.relatedTarget === toolsDropdown[0] ||
                        $.contains(toolsDropdown[0], e.relatedTarget))) {
                    return;
                }
                toolsDropdown.removeClass('active');
            });

            // Toggle dropdown on click (for mobile)
            toolsLink.on('click', function (e) {
                if ($(window).width() < 992) {
                    e.preventDefault();
                    toolsDropdown.toggleClass('active');
                }
            });

            // Close dropdown when clicking elsewhere
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#toolsLink, #toolsDropdown').length) {
                    toolsDropdown.removeClass('active');
                }
            });


            // // User avatar click
            // $('#userAvatar').on('click', function () {
            //     alert('User menu would open here. Implement similar to your BG Remover page.');
            // });

            // Smooth scrolling for anchor links
            $('a[href^="#"]').not('#toolsLink').on('click', function (e) {
                e.preventDefault();
                var target = this.hash;
                var $target = $(target);
                $('html, body').animate({
                    'scrollTop': $target.offset().top - 80
                }, 800, 'swing');
            });
        });
    </script>
</body>

</html>
