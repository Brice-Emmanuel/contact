<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion de Contacts')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bleu-fonce: #1a2a4a;
            --bleu-moyen: #2c4a7a;
            --bleu-clair: #4a7aaa;
            --orange-fonce: #c45a2a;
            --orange-moyen: #e87a4a;
            --orange-clair: #f5a87a;
        }

        .bg-bleu-fonce { background-color: #1a2a4a; }
        .bg-bleu-moyen { background-color: #2c4a7a; }
        .bg-bleu-clair { background-color: #4a7aaa; }
        .bg-orange-fonce { background-color: #c45a2a; }
        .bg-orange-moyen { background-color: #e87a4a; }
        .bg-orange-clair { background-color: #f5a87a; }

        .text-bleu-fonce { color: #1a2a4a; }
        .text-orange-fonce { color: #c45a2a; }
        .text-orange-moyen { color: #e87a4a; }

        .btn-primary {
            background-color: #1a2a4a;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #2c4a7a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 42, 74, 0.3);
        }

        .btn-orange {
            background-color: #c45a2a;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        .btn-orange:hover {
            background-color: #e87a4a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(196, 90, 42, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid #1a2a4a;
            color: #1a2a4a;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
            background: transparent;
        }
        .btn-outline-primary:hover {
            background-color: #1a2a4a;
            color: white;
        }

        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            transition: all 0.3s;
            border: 1px solid #e5e7eb;
        }
        .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: all 0.3s;
            outline: none;
        }
        .form-input:focus {
            border-color: #1a2a4a;
            box-shadow: 0 0 0 3px rgba(26, 42, 74, 0.1);
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #1a2a4a;
            margin-bottom: 0.3rem;
            font-size: 0.9rem;
        }

        .badge-famille { background: #2c4a7a; color: white; }
        .badge-amis { background: #c45a2a; color: white; }
        .badge-Collègue{ background: #4a7aaa; color: white; }
        .badge-autres { background: #6b7280; color: white; }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: white;
            border-radius: 0.5rem;
            transition: all 0.3s;
            text-decoration: none;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar-link.active {
            background: #c45a2a;
            color: white;
        }
        .sidebar-link i {
            width: 1.5rem;
        }

        /* ===== SIDEBAR TOGGLE ===== */
        .sidebar-desktop {
            transition: transform 0.3s ease, width 0.3s ease;
        }
        .sidebar-desktop.hidden {
            transform: translateX(-100%);
            width: 0 !important;
            overflow: hidden;
        }
        .main-content {
            transition: margin-left 0.3s ease;
        }

        /* ===== MOBILE SIDEBAR ===== */
        .sidebar-mobile {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.3s;
        }
        .sidebar-mobile.open {
            transform: translateX(0);
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 45;
        }
        .sidebar-overlay.active {
            display: block;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        body.overflow-hidden {
            overflow: hidden;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #c45a2a; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #e87a4a; }
    </style>
</head>
<body class="bg-gray-50">

    <div class="min-h-screen flex">
        
        <!-- ===== SIDEBAR ===== -->
        @include('partials.sidebar')

        <!-- ===== CONTENU PRINCIPAL ===== -->
        <div class="main-content flex-1 flex flex-col ml-64 transition-all duration-300">
            
            <!-- ===== HEADER ===== -->
            @include('partials.header')

            <!-- ===== CONTENU ===== -->
            <main class="flex-1 p-4 md:p-6 lg:p-8 fade-in">
                <div class="max-w-7xl mx-auto">
                    
                    <!-- Messages flash -->
                    @include('partials.flash-messages')

                    @yield('content')

                </div>
            </main>

            <!-- ===== FOOTER ===== -->
          <!-- ===== FOOTER ===== -->
<footer class="py-2 px-6 mt-auto">
    <div class="max-w-7xl mx-auto text-right">
        <p class="text-xs italic text-gray-500 font-light">
            Fait par <span class="font-medium text-gray-500">Emmanuel NYAMSI</span>
        </p>
    </div>
</footer>
        </div>
    </div>

    <!-- ===== JAVASCRIPT ===== -->
    @include('partials.scripts')

    @stack('scripts')
</body>
</html>