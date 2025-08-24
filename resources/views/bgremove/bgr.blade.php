<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Background Remover</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
        }

        .nav-link {
            text-decoration: none;
            color: var(--secondary-color);
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.1);
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

        .mobile-menu-btn {
            display: none;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        /* Main Content */
        .app-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .hero {
            text-align: center;
            margin-bottom: 2.5rem;
            padding-top: 20px;
        }

        .hero h1 {
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 1rem;
            color: var(--primary-color);
            font-size: 2.8rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .hero p {
            color: var(--secondary-color);
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        .preview-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: none;
            transition: all 0.3s ease;
        }

        .preview-area {
            width: 100%;
            height: 350px;
            background-color: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #e9ecef;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .preview-area:hover {
            border-color: #adb5bd;
            background-color: #f1f3f5;
        }

        .preview-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: none;
        }

        .upload-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
            transition: all 0.3s ease;
        }

        .upload-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 28px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 2;
        }

        .upload-btn:hover {
            background-color: #343a40;
            transform: translateY(-2px);
        }

        .upload-btn:active {
            transform: translateY(0);
        }

        .samples-section {
            margin-top: 1.5rem;
            text-align: center;
        }

        .samples-section h4 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }

        .sample-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin: 0 5px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sample-img:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .thumbnail-container {
            position: relative;
            margin-bottom: 20px;
        }

        .thumbnail-container::after {
            content: "Try this";
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.8rem;
            color: var(--secondary-color);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .thumbnail-container:hover::after {
            opacity: 1;
        }

        .footer {
            text-align: center;
            margin-top: 3rem;
            color: var(--secondary-color);
            font-size: 0.9rem;
            padding: 20px;
            border-top: 1px solid #e9ecef;
        }

        .processing-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 10;
            border-radius: 12px;
        }

        .processing-spinner {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(13, 110, 253, 0.2);
            border-top: 5px solid var(--processing-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .processing-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .result-container {
            display: none;
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }

        .result-image {
            max-width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background:
                linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%),
                linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
        }

        .download-btn {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 10px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 10px 5px;
        }

        .download-btn:hover {
            background-color: #157347;
            transform: translateY(-2px);
        }

        .upload-instructions {
            color: var(--secondary-color);
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .comparison-container {
            display: flex;
            width: 100%;
            gap: 20px;
            margin-top: 20px;
        }

        .comparison-box {
            flex: 1;
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .comparison-label {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .try-another-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 10px 5px;
        }

        .try-another-btn:hover {
            background-color: #5c636a;
            transform: translateY(-2px);
        }

        .success-message {
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 15px;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 500px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .result-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: none;
            transition: all 0.3s ease;
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: 100%;
            background: var(--header-bg);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.05);
            z-index: 999;
            display: none;
            flex-direction: column;
            padding: 20px;
        }

        .mobile-menu.show {
            display: flex;
        }

        .mobile-menu .nav-link {
            padding: 12px 15px;
            margin: 5px 0;
            width: 100%;
        }

        .mobile-menu .header-actions {
            flex-direction: column;
            margin: 15px 0 0 0;
            gap: 10px;
            width: 100%;
        }

        .mobile-menu .header-actions .btn {
            width: 100%;
            text-align: center;
        }

        /* Mobile menu header styles */
        .mobile-menu-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 5px;
        }

        /* .mobile-close-btn {
            background: none;
            border: none;
            font-size: 1.1rem;
            color: #6c757d;
            transition: all 0.3s ease;
            width: 30px;
            height: 30px;
            border: 1px solid #6e777f;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
        }

        .mobile-close-btn:hover {
            background-color: #f8f9fa;
            color: #495057;
        } */

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

        /* Overlay */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .popup-overlay.active {
            opacity: 1;
            visibility: visible;
        }


        /* How It Works Section */
        .how-it-works-section {
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
            z-index: 2;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: var(--dark-text);
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .section-header p {
            font-size: 1.1rem;
            color: var(--light-text);
            max-width: 600px;
            margin: 0 auto;
        }

        .steps-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            position: relative;
            z-index: 2;
        }

        .step-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            flex: 1;
            min-width: 280px;
            max-width: 320px;
            border: 1px solid #e2e8f0;
        }

        .step-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .step-number {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: var(--primary-gradient);
            color: white;
            border-radius: 50%;
            font-weight: 700;
            font-size: 1.3rem;
            line-height: 50px;
            margin-bottom: 25px;
        }

        .step-icon {
            font-size: 3rem;
            margin-bottom: 25px;
            color: var(--feature-primary);
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .step-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark-text);
        }

        .step-card p {
            color: var(--light-text);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* .features-section {
            background: white;
            padding: 80px 0;
        } */

        .feature-card {
            background: var(--light-bg);
            border-radius: 16px;
            padding: 40px;
            height: 100%;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 25px;
            color: var(--feature-primary);
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark-text);
        }

        .feature-card p {
            color: var(--light-text);
            font-size: 1rem;
            line-height: 1.6;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #cbd5e1, transparent);
            margin: 40px auto;
            max-width: 800px;
        }

        /* FAQ Section Styles */
        .faq-section {
            background: #fff;
            padding: 80px 0;
            position: relative;
        }

        .faq-section .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .faq-section .section-header h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: #212529;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .faq-section .section-header p {
            font-size: 1.1rem;
            color: #6c757d;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Accordion Styles */
        .accordion {
            max-width: 800px;
            margin: 0 auto;
        }

        .accordion-item {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .accordion-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .accordion-button {
            font-size: 1.1rem;
            font-weight: 600;
            padding: 20px 25px;
            color: #212529;
            background-color: #fff;
            border: none;
            box-shadow: none;
        }

        .accordion-button:not(.collapsed) {
            background-color: #fff;
            color: #0d6efd;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: transparent;
        }

        .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transition: transform 0.3s ease;
        }

        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230d6efd'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transform: rotate(180deg);
        }

        .accordion-body {
            padding: 20px 25px;
            font-size: 1rem;
            color: #495057;
            line-height: 1.7;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .support-link {
            font-size: 1.1rem;
            color: #6c757d;
        }

        .support-link a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .support-link a:hover {
            color: #0a58ca;
            text-decoration: underline;
        }


        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .nav-links {
                display: none;
            }

            .header-actions .btn-text {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
                margin-left: auto;
                margin-right: 15px;
            }

            .user-popup {
                right: 20px;
                width: 300px;
            }
        }

        @media (max-width: 768px) {

            .preview-card,
            .result-card {
                padding: 1.5rem;
            }

            .hero h1 {
                font-size: 2.2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .preview-area {
                height: 280px;
            }

            .comparison-container {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .header-container {
                padding: 0 15px;
            }

            .logo span {
                font-size: 1.3rem;
            }

            .sample-img {
                width: 60px;
                height: 60px;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .steps-container {
                flex-direction: column;
                align-items: center;
            }

            .step-card {
                max-width: 100%;
            }

            .faq-section {
                padding: 60px 0;
            }

            .faq-section .section-header h2 {
                font-size: 2rem;
            }

            .accordion-button {
                padding: 15px 20px;
                font-size: 1rem;
            }

            .accordion-body {
                padding: 15px 20px;
            }

        }

        @media (max-width: 576px) {
            :root {
                --header-height: 60px;
            }

            .hero h1 {
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

            .faq-section .section-header h2 {
                font-size: 1.8rem;
            }

            .user-popup {
                width: calc(100% - 30px);
                right: 15px;
                left: 15px;
                top: 80px;
            }

            .popup-header {
                padding: 20px;
            }

            /* Show mobile close button only on mobile */
            .mobile-close-btn {
                opacity: 1;
                visibility: visible;
            }
        }
    </style>
</head>

<body>
    <!-- Responsive Header -->
    <header class="header-container">
        <a href="#" class="logo">
            <div class="logo-icon">
                <i class="bi bi-magic"></i>
            </div>
            <span>BG Remover</span>
        </a>

        <div class="nav-links">
            <a href="#" class="nav-link active">Home</a>
            <a href="#how-it-works" class="nav-link">How It Works</a>
            <a href="#features" class="nav-link">Features</a>
            <a href="#" class="nav-link">Pricing</a>
        </div>

        <div class="header-actions">
            <button class="btn-login">
                <i class="bi bi-person"></i>
                <span class="btn-text">Sign In</span>
            </button>
            <button class="btn-try-pro">
                <i class="bi bi-stars"></i>
                <span class="btn-text">Try Pro</span>
            </button>
            <div class="user-avatar" id="userAvatar">JS</div>
        </div>

        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="bi bi-list"></i>
        </button>
    </header>

    <!-- Mobile Menu with Close Button -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <button class="mobile-close-btn" id="mobileCloseBtn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <a href="#" class="nav-link active">Home</a>
        <a href="#how-it-works" class="nav-link">How It Works</a>
        <a href="#features" class="nav-link">Features</a>
        <a href="#" class="nav-link">Pricing</a>

        <div class="header-actions">
            <button class="btn-login">
                <i class="bi bi-person"></i>
                <span class="btn-text">Sign In</span>
            </button>
            <button class="btn-try-pro">
                <i class="bi bi-stars"></i>
                <span class="btn-text">Try Pro</span>
            </button>
        </div>
    </div>

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

    <!-- Main Content -->
    <div class="app-container">
        <div class="hero">
            <h1>Background Remover</h1>
            <p class="lead">Upload an image to remove its background instantly.</p>
        </div>

        <div class="card preview-card" id="uploadSection">
            <div class="preview-area" id="previewArea">
                <i class="bi bi-cloud-arrow-up upload-icon" id="uploadIcon"></i>
                <input type="file" id="fileInput" accept="image/*" style="display: none;">
                <button class="upload-btn" id="uploadBtn">
                    <i class="bi bi-upload"></i> Upload Image
                </button>
                <p class="upload-instructions" id="uploadInstructions">Drag & drop or click to upload</p>

                <img src="" class="preview-img" id="originalPreview" alt="Original image">

                <div class="processing-overlay" id="processingOverlay">
                    <div class="processing-spinner"></div>
                    <p class="processing-text">Removing background...</p>
                </div>
            </div>
        </div>

        <div class="result-card" id="resultSection" style="display: none;">
            <div class="success-message">
                <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
                <span>Background removed successfully! Download your image below.</span>
            </div>

            <div class="comparison-container">
                <div class="comparison-box">
                    <div class="comparison-label">Original</div>
                    <img src="" class="result-image" id="originalImage" alt="Original image">
                </div>
                <div class="comparison-box">
                    <div class="comparison-label">Background Removed</div>
                    <img src="" class="result-image" id="processedImage" alt="Processed image">
                </div>
            </div>

            <div class="action-buttons">
                <a href="#" class="download-btn" id="downloadBtn" download>
                    <i class="bi bi-download"></i> Download Result
                </a>
                <button class="try-another-btn" id="tryAnotherBtn">
                    <i class="bi bi-arrow-repeat"></i> Try Another Image
                </button>
            </div>
        </div>

        <div class="samples-section" id="sampleSection">
            <h4>No image? Try one of these:</h4>
            <div class="d-flex justify-content-center mt-2">
                <div class="thumbnail-container">
                    <img class="sample-img" data-sample="sample1" name="women-1"
                        src="https://randomuser.me/api/portraits/women/1.jpg" alt="">
                </div>
                <div class="thumbnail-container">
                    <img class="sample-img" name="men-2" data-sample="sample2"
                        src="https://randomuser.me/api/portraits/men/2.jpg" alt="">
                </div>
                <div class="thumbnail-container">
                    <img class="sample-img" data-sample="sample3" name="women-3"
                        src="https://randomuser.me/api/portraits/women/3.jpg" alt="">
                </div>
                <div class="thumbnail-container">
                    <img class="sample-img" name="man-1" data-sample="sample4"
                        src="https://randomuser.me/api/portraits/men/4.jpg" alt="">
                </div>
            </div>
        </div>

        <section class="how-it-works-section" id="how-it-works">
            <!-- <div class="decoration circle-1"></div>
            <div class="decoration circle-2"></div> -->

            <div class="container">
                <div class="section-header">
                    <h2>How It Works</h2>
                    <p>Remove backgrounds from your images in just 3 simple steps</p>
                </div>

                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <i class="bi bi-cloud-arrow-up step-icon"></i>
                        <h3>Upload Your Image</h3>
                        <p>Upload any image from your device or drag and drop it directly into our tool. We support JPG,
                            PNG, and WebP formats.</p>
                    </div>

                    <div class="step-card">
                        <div class="step-number">2</div>
                        <i class="bi bi-cpu step-icon"></i>
                        <h3>AI Magic Processing</h3>
                        <p>Our advanced AI algorithm will automatically detect and remove the background in seconds
                            while preserving all details.</p>
                    </div>

                    <div class="step-card">
                        <div class="step-number">3</div>
                        <i class="bi bi-download step-icon"></i>
                        <h3>Download & Use</h3>
                        <p>Download your image with a transparent background in high-quality PNG format. Use it anywhere
                            you need!</p>
                    </div>
                </div>
            </div>

            <!-- <div class="wave">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
                </svg>
            </div> -->
        </section>

        <section class="" id="features">
            <div class="container">
                <div class="section-header">
                    <h2>Powerful Features</h2>
                    <p>Everything you need for perfect background removal</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-lightning-charge feature-icon"></i>
                            <h3>Instant Processing</h3>
                            <p>Our AI works in seconds to remove backgrounds, saving you hours of manual editing work.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-bounding-box feature-icon"></i>
                            <h3>Pixel-Perfect Results</h3>
                            <p>Get clean, crisp edges around your subject with no leftover background artifacts.</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-shield-check feature-icon"></i>
                            <h3>Privacy First</h3>
                            <p>Your images are never stored on our servers. All processing happens securely in your
                                browser.</p>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

            </div>
        </section>

        <section class="faq-section" id="faq">
            <div class="container">
                <div class="section-header">
                    <h2>Frequently Asked Questions</h2>
                    <p>Find answers to common questions about our background removal tool</p>
                </div>

                <div class="accordion" id="faqAccordion">
                    <!-- FAQ Item 1 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                How accurate is the background removal?
                            </button>
                        </h3>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Our AI-powered background removal is highly accurate for most images. It works best with
                                clear subjects against contrasting backgrounds. For complex images with fine details
                                like hair or fur, results may vary. You can always refine the result using our manual
                                editing tools.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                What image formats do you support?
                            </button>
                        </h3>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We support all common image formats including JPG, PNG, WebP, and GIF. Processed images
                                with transparent backgrounds are always downloaded as high-quality PNG files to preserve
                                transparency.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Is there a limit to how many images I can process?
                            </button>
                        </h3>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Our free plan allows up to 10 image processes per day. With our Pro subscription, you
                                get unlimited background removals, batch processing, and higher resolution downloads.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                How long does it take to remove a background?
                            </button>
                        </h3>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Processing time depends on image complexity and size, but typically takes 2-5 seconds.
                                Larger images may take slightly longer. Our AI processes images in real-time for most
                                common use cases.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Are my images stored on your servers?
                            </button>
                        </h3>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                No, we prioritize your privacy. All image processing happens in your browser, and we
                                never store your images on our servers. Once processing is complete, files are
                                automatically removed from our system.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Can I replace the background after removal?
                            </button>
                        </h3>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes! After removing the background, you can replace it with a solid color, gradient, or
                                upload a new background image. This feature is available in our Pro version.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="support-link text-center mt-5">
                    <p>Still have questions? <a href="#">Contact our support team</a></p>
                </div>
            </div>
        </section>

        <div class="footer">
            <p>Powered by AI technology • 100% automatic background removal</p>
            <p class="mt-2">© 2023 BG Remover. All rights reserved.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const fileInput = $('#fileInput');
            const uploadBtn = $('#uploadBtn');
            const uploadIcon = $('#uploadIcon');
            const originalPreview = $('#originalPreview');
            const processingOverlay = $('#processingOverlay');
            const uploadSection = $('#uploadSection');
            const resultSection = $('#resultSection');
            const originalImage = $('#originalImage');
            const processedImage = $('#processedImage');
            const downloadBtn = $('#downloadBtn');
            const tryAnotherBtn = $('#tryAnotherBtn');
            const uploadInstructions = $('#uploadInstructions');

            uploadBtn.on('click', function(e) {
                e.preventDefault();
                fileInput.click();
            });

            fileInput.on('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const file = e.target.files[0];
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        uploadIcon.hide();
                        originalPreview.attr('src', event.target.result).show();
                    }
                    reader.readAsDataURL(file);
                    uploadImage(file);
                }
            });

            function uploadImage(file) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', '{{ csrf_token() }}');
                processingOverlay.css('opacity', 1).css('pointer-events', 'all');
                uploadBtn.hide();
                uploadInstructions.hide();

                $.ajax({
                    url: "{{ route('bg.request') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(blob, status, xhr) {
                        const mimeType = xhr.getResponseHeader("Content-Type");
                        const blobUrl = URL.createObjectURL(blob);
                        processingOverlay.css('opacity', 0).css('pointer-events', 'none');
                        originalImage.attr('src', originalPreview.attr('src'));
                        processedImage.attr('src', blobUrl);
                        downloadBtn.attr('href', blobUrl);
                        uploadSection.hide();
                        resultSection.show();
                    },
                    error: function(xhr) {
                        console.error("Upload failed:", xhr.responseText);
                        processingOverlay.css('opacity', 0).css('pointer-events', 'none');
                    }
                });


            }

            $(document).on('click', '.sample-img', function() {
                const sampleUrl = $(this).attr('src');
                const sampleName = $(this).attr('name');

                uploadIcon.hide();
                originalPreview.attr('src', sampleUrl).show();

                processingOverlay.css('opacity', 1).css('pointer-events', 'all');
                uploadBtn.hide();
                uploadInstructions.hide();

                setTimeout(function() {
                    processingOverlay.css('opacity', 0).css('pointer-events', 'none');
                    originalImage.attr('src', sampleUrl);
                    processedImage.attr('src', `/assets/images/${sampleName}.png`);
                    downloadBtn.attr('href', `/assets/images/${sampleName}.png`);
                    uploadSection.hide();
                    resultSection.show();
                }, 7000);
            });

            tryAnotherBtn.on('click', function() {
                uploadSection.show();
                resultSection.hide();
                uploadIcon.show();
                originalPreview.hide().attr('src', '');
                fileInput.val('');
                uploadBtn.show();
                uploadInstructions.show();
            });
        });
    </script>

</body>

</html>
