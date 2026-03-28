@extends('layout.eduka')
@section('title', 'Eduka School | Trujillo - Perú')
@section('content')
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet">

        <!-- AOS Animation Library -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <!-- Custom CSS -->
        <style>
            :root {
                --primary-color: #053255;
                /* Azul institucional EDUKA */
                --secondary-color: #053255;
                /* Azul académico */
                --accent-color: #c9a14a;
                /* Dorado universitario */
                --dark-color: #0b1f2d;
                --light-color: #f5f7fa;
                --success-color: #2da44e;
            }


            body,
            a,
            li {
                font-family: 'Quicksand', sans-serif !important;

            }



            /* Hero Section */
            .hero-section {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                min-height: 100vh;
                position: relative;
                overflow: hidden;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2071&q=80') center/cover;
                opacity: 0.1;
            }

            .hero-content {
                position: relative;
                z-index: 2;
            }

            .hero-title {
                font-size: 3.5rem;
                font-weight: 800;
                color: white;
                margin-bottom: 1.5rem;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            }

            .hero-subtitle {
                font-size: 1.3rem;
                color: rgba(255, 255, 255, 0.9);
                margin-bottom: 2rem;
                line-height: 1.6;
            }

            .btn-custom {
                padding: 15px 40px;
                border-radius: 50px;
                font-weight: 600;
                font-size: 1.1rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: all 0.3s ease;
                border: none;
                position: relative;
                overflow: hidden;
            }

            .btn-custom::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .btn-custom:hover::before {
                left: 100%;
            }

            .btn-primary-custom {
                background: var(--accent-color);
                color: white;
            }

            .btn-primary-custom:hover {
                background: #d97706;
                transform: translateY(-3px);
                box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
            }

            .btn-outline-custom {
                background: transparent;
                color: white;
                border: 2px solid white;
            }

            .btn-outline-custom:hover {
                background: white;
                color: var(--primary-color);
            }

            /* Stats Section */
            .stats-section {
                background: var(--light-color);
                padding: 80px 0;
            }

            .stat-card {
                text-align: center;
                padding: 30px;
                background: white;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-10px);
            }

            .stat-number {
                font-size: 3rem;
                font-weight: 800;
                color: var(--primary-color);
                margin-bottom: 10px;
            }

            .stat-title {
                color: var(--dark-color);
                font-weight: 600;
            }

            /* Features Section */
            .features-section {
                padding: 100px 0;
                background: white;
            }

            .feature-card {
                padding: 40px 30px;
                text-align: center;
                border-radius: 15px;
                transition: all 0.3s ease;
                height: 100%;
            }

            .feature-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                color: white;
                font-size: 2rem;
            }

            .feature-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--dark-color);
                margin-bottom: 15px;
            }

            .feature-description {
                color: #6b7280;
                line-height: 1.6;
            }

            /* Process Section */
            .process-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 100px 0;
                color: white;
            }

            .process-step {
                text-align: center;
                padding: 40px 20px;
            }

            .step-number {
                width: 80px;
                height: 80px;
                background: var(--accent-color);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                font-weight: 800;
                margin: 0 auto 20px;
                color: white;
            }

            .step-title {
                font-size: 1.3rem;
                font-weight: 700;
                margin-bottom: 15px;
            }

            .step-description {
                opacity: 0.9;
                line-height: 1.6;
            }

            /* CTA Section */
            .cta-section {
                background: var(--dark-color);
                padding: 80px 0;
                color: white;
                text-align: center;
            }

            .cta-title {
                font-size: 2.5rem;
                font-weight: 800;
                margin-bottom: 20px;
            }

            .cta-subtitle {
                font-size: 1.2rem;
                opacity: 0.9;
                margin-bottom: 40px;
            }

            /* Footer */
            .footer {
                background: #1a202c;
                color: white;
                padding: 60px 0 30px;
            }

            .footer-title {
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 20px;
                color: white;
            }

            .footer-links {
                list-style: none;
                padding: 0;
            }

            .footer-links li {
                margin-bottom: 10px;
            }

            .footer-links a {
                color: #a0aec0;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .footer-links a:hover {
                color: var(--accent-color);
            }

            .social-links {
                margin-top: 20px;
            }

            .social-links a {
                display: inline-block;
                width: 40px;
                height: 40px;
                background: var(--primary-color);
                color: white;
                border-radius: 50%;
                text-align: center;
                line-height: 40px;
                margin-right: 10px;
                transition: all 0.3s ease;
            }

            .social-links a:hover {
                background: var(--accent-color);
                transform: translateY(-3px);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.5rem;
                }

                .hero-subtitle {
                    font-size: 1.1rem;
                }

                .stat-number {
                    font-size: 2.5rem;
                }

                .cta-title {
                    font-size: 2rem;
                }
            }

            /* Animation Classes */
            .fade-in-up {
                animation: fadeInUp 0.8s ease-out;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: var(--primary-color);
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: var(--secondary-color);
            }



            /* Logo */
            .edu-logo {
                width: 48px;
                height: 48px;
                background: var(--accent-color);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #053255;
                font-size: 1.3rem;
            }

            .edu-name {
                font-family: 'Playfair Display', serif;
                font-size: 1.5rem;
                font-weight: 800;
                color: white;
                letter-spacing: 1px;
            }

            .edu-subtitle {
                font-size: 0.7rem;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: var(--accent-color);
                margin-top: -4px;
            }

            /* Menu */
            .edu-menu .nav-link {
                color: #e5e7eb;
                font-weight: 500;
                margin: 0 12px;
                position: relative;
                transition: .3s;
            }

            .edu-menu .nav-link::after {
                content: '';
                position: absolute;
                bottom: -6px;
                left: 0;
                width: 0;
                height: 2px;
                background: var(--accent-color);
                transition: .3s;
            }

            .edu-menu .nav-link:hover::after {
                width: 100%;
            }

            /* Botón portal */
            .edu-btn-login {
                padding: 12px 30px;
                border-radius: 50px;
                border: 2px solid var(--accent-color);
                color: var(--accent-color);
                font-weight: 600;
                text-decoration: none;
                transition: .4s;
            }

            .edu-btn-login:hover {
                background: var(--accent-color);
                color: #053255;
            }

            /* iOS Liquid Glass Effect */
            .edu-navbar {
                background: rgba(5, 50, 85, 0.25);
                backdrop-filter: blur(20px) saturate(180%);
                -webkit-backdrop-filter: blur(20px) saturate(180%);
                transition: all 0.45s cubic-bezier(.2, .8, .2, 1);
            }

            /* Se mueve → vidrio líquido */
            .edu-navbar.scrolling {
                background: rgba(5, 50, 85, 0.15);
                backdrop-filter: blur(32px) saturate(220%);
                -webkit-backdrop-filter: blur(32px) saturate(220%);
                box-shadow: 0 20px 60px rgba(0, 0, 0, .45);
            }

            /* Se detiene (sin importar la altura) */
            .edu-navbar.scrolled:not(.scrolling) {
                background: rgba(5, 50, 85, 0.92);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            .edu-nav-container {
                padding-left: 1.2rem;
                padding-right: 1.2rem;
            }



            @media (max-width: 991px) {
                .edu-menu {
                    background: rgba(5, 50, 85, .92);
                    border-radius: 14px;
                    padding: 20px;
                    margin-top: 12px;
                    backdrop-filter: blur(12px);
                }

                .edu-menu .nav-link {
                    margin: 10px 0;
                    font-size: 1.05rem;
                }
            }
        </style>

    </head>

    <body>
        <script>
            let isScrolling;

            window.addEventListener("scroll", () => {
                const nav = document.querySelector(".edu-navbar");

                nav.classList.add("scrolling");

                if (window.scrollY > 40) {
                    nav.classList.add("scrolled");
                } else {
                    nav.classList.remove("scrolled");
                }

                clearTimeout(isScrolling);
                isScrolling = setTimeout(() => {
                    nav.classList.remove("scrolling");
                }, 180);
            });
        </script>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top edu-navbar">
            <div class="container edu-nav-container">

                <a class="navbar-brand d-flex align-items-center" href="#inicio">
                    <img src="{{ asset('imagenes/Imagen1.png') }}" alt="EDUKA" style="height:70px;">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-lg-center edu-menu text-center text-lg-start">
                        <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#nosotros">Institución</a></li>
                        <li class="nav-item"><a class="nav-link" href="#programas">Programas</a></li>
                        <li class="nav-item"><a class="nav-link" href="#admision">Admisión</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>

                        <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                            <a class="edu-btn-login d-inline-block" href="{{ route('login.index') }}" target="_blank">
                                Portal Académico
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>

        <!-- Hero Section -->
        <section id="inicio" class="hero-section d-flex align-items-center">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6" data-aos="fade-right">
                        <div class="hero-content">
                            <h1 class="hero-title">
                                Educación de Excelencia<br>
                                <span style="color: var(--accent-color);">para una vida de liderazgo</span>
                            </h1>
                            <p class="hero-subtitle">
                                EDUKA es una institución privada de alto nivel académico que forma
                                estudiantes preparados para universidades de prestigio en el Perú
                                y el extranjero, desarrollando carácter, disciplina y pensamiento crítico.
                            </p>

                            <div class="d-flex flex-wrap gap-3">
                                <a href="#admision" class="btn btn-custom btn-primary-custom">
                                    <i class="fas fa-user-plus me-2"></i>Admisión 2025
                                </a>
                                <a href="#nosotros" class="btn btn-custom btn-outline-custom">
                                    <i class="fas fa-info-circle me-2"></i>Conócenos
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-left">
                        <div class="text-center">
                            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                alt="Estudiantes EDUKA" class="img-fluid rounded-3 shadow-lg">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="stat-card">
                            <div class="stat-number" data-target="15">0</div>
                            <div class="stat-title">Años de Excelencia</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="stat-card">
                            <div class="stat-number" data-target="1200">0</div>
                            <div class="stat-title">Estudiantes Activos</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="stat-card">
                            <div class="stat-number" data-target="85">0</div>
                            <div class="stat-title">Profesores Calificados</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="stat-card">
                            <div class="stat-number" data-target="98">0</div>
                            <div class="stat-title">% Satisfacción</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="nosotros" class="features-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-12 text-center" data-aos="fade-up">
                        <h2 class="display-4 fw-bold text-dark mb-4">Nuestra Institución</h2>
                        <p class="lead text-muted">Una comunidad académica orientada a la excelencia y la proyección
                            universitaria
                        </p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-card h-100">
                            <div class="feature-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h3 class="feature-title">Educación Integral</h3>
                            <p class="feature-description">
                                Desarrollamos la mente, el cuerpo y el espíritu de nuestros estudiantes
                                con un enfoque holístico que va más allá del aprendizaje académico.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-card h-100">
                            <div class="feature-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <h3 class="feature-title">Mentalidad Global</h3>
                            <p class="feature-description">
                                Preparamos a nuestros estudiantes para un mundo conectado,
                                fomentando el pensamiento crítico y la comprensión intercultural.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-card h-100">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="feature-title">Comunidad Educativa</h3>
                            <p class="feature-description">
                                Creamos un ambiente de apoyo donde estudiantes, profesores y familias
                                trabajan juntos para lograr el éxito de cada niño.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="feature-card h-100">
                            <div class="feature-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h3 class="feature-title">Innovación Tecnológica</h3>
                            <p class="feature-description">
                                Integramos las últimas tecnologías en nuestro proceso de enseñanza,
                                preparando a los estudiantes para la era digital.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                        <div class="feature-card h-100">
                            <div class="feature-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3 class="feature-title">Valores y Ética</h3>
                            <p class="feature-description">
                                Formamos personas íntegras con sólidos principios morales,
                                responsabilidad social y compromiso con el bien común.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                        <div class="feature-card h-100">
                            <div class="feature-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h3 class="feature-title">Excelencia Académica</h3>
                            <p class="feature-description">
                                Nuestros resultados académicos nos posicionan como una de las
                                instituciones educativas más destacadas del país.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Programs Section -->
        <section id="programas" class="py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-12 text-center" data-aos="fade-up">
                        <h2 class="display-4 fw-bold text-dark mb-4">Nuestra Oferta Educativa</h2>
                        <p class="lead text-muted">Programas académicos diseñados para el desarrollo integral</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card border-0 shadow-lg h-100">
                            <div class="card-body p-4">
                                <div class="text-center mb-4">
                                    <div
                                        style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff6b6b, #ee5a24); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 1.5rem;">
                                        <i class="fas fa-school"></i>
                                    </div>
                                </div>
                                <h4 class="card-title text-center fw-bold mb-3">Educación Inicial – Formación Temprana</h4>
                                <p class="card-text text-muted">
                                    Programa educativo que estimula el desarrollo cognitivo, emocional y social
                                    de los niños pequeños en un ambiente seguro y estimulante.
                                </p>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Desarrollo
                                        psicomotriz</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Estimulación temprana
                                    </li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Aprendizaje lúdico
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card border-0 shadow-lg h-100">
                            <div class="card-body p-4">
                                <div class="text-center mb-4">
                                    <div
                                        style="width: 60px; height: 60px; background: linear-gradient(135deg, #4ecdc4, #44a08d); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 1.5rem;">
                                        <i class="fas fa-book-open"></i>
                                    </div>
                                </div>
                                <h4 class="card-title text-center fw-bold mb-3">Primaria (6-11 años)</h4>
                                <p class="card-text text-muted">
                                    Educación básica sólida que combina el aprendizaje académico con el
                                    desarrollo de habilidades sociales y emocionales.
                                </p>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Currículo nacional
                                    </li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Actividades
                                        extracurriculares</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Desarrollo integral
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="card border-0 shadow-lg h-100">
                            <div class="card-body p-4">
                                <div class="text-center mb-4">
                                    <div
                                        style="width: 60px; height: 60px; background: linear-gradient(135deg, #45b7d1, #96c93d); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 1.5rem;">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                </div>
                                <h4 class="card-title text-center fw-bold mb-3">Secundaria (12-17 años)</h4>
                                <p class="card-text text-muted">
                                    Preparación integral para la universidad con énfasis en el desarrollo
                                    de pensamiento crítico y habilidades para la vida.
                                </p>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Programa del Diploma
                                        IB</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Exámenes Cambridge
                                    </li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Orientación
                                        vocacional</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Section -->
        <section id="admision" class="process-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-12 text-center" data-aos="fade-up">
                        <h2 class="display-4 fw-bold mb-4">Proceso de Admisión</h2>
                        <p class="lead opacity-90">Únete a nuestra comunidad educativa en simples pasos</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="process-step">
                            <div class="step-number">1</div>
                            <h4 class="step-title">Contacto Inicial</h4>
                            <p class="step-description">
                                Visítanos o contáctanos para conocer nuestras instalaciones
                                y resolver tus dudas sobre el proceso de admisión.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="process-step">
                            <div class="step-number">2</div>
                            <h4 class="step-title">Inscripción</h4>
                            <p class="step-description">
                                Completa el formulario de inscripción con los datos
                                solicitados y adjunta la documentación requerida.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="process-step">
                            <div class="step-number">3</div>
                            <h4 class="step-title">Evaluación</h4>
                            <p class="step-description">
                                Realiza las pruebas de admisión y entrevistas
                                correspondientes al nivel solicitado.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="process-step">
                            <div class="step-number">4</div>
                            <h4 class="step-title">Matrícula</h4>
                            <p class="step-description">
                                Una vez aceptado, formaliza la matrícula y únete
                                a nuestra comunidad EDUKA.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="500">
                    <a href="{{ route('login.index') }}" class="btn btn-custom btn-primary-custom btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Iniciar Proceso de Admisión
                    </a>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="contacto" class="cta-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center" data-aos="fade-up">
                        <h2 class="cta-title">¿Listo para formar parte de EDUKA?</h2>
                        <p class="cta-subtitle">
                            Únete a una institución educativa que se preocupa por el futuro de tus hijos
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="tel:+51123456789" class="btn btn-custom btn-primary-custom">
                                <i class="fas fa-phone me-2"></i>Llamar Ahora
                            </a>
                            <a href="mailto:admisiones@eduka.edu.pe" class="btn btn-custom btn-outline-custom">
                                <i class="fas fa-envelope me-2"></i>Enviar Correo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <h4 class="footer-title">
                            <img src="imagenes/Imagen1.png" alt="" style="height: 56px">
                        </h4>
                        <p class="mb-3">
                            EDUKA es una institución privada de formación integral,
                            orientada a la excelencia académica y a la preparación
                            de estudiantes con proyección universitaria nacional e internacional.

                        </p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <h5 class="footer-title">Enlaces Rápidos</h5>
                        <ul class="footer-links">
                            <li><a href="#inicio">Inicio</a></li>
                            <li><a href="#nosotros">Nosotros</a></li>
                            <li><a href="#programas">Programas</a></li>
                            <li><a href="#admision">Admisión</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <h5 class="footer-title">Programas</h5>
                        <ul class="footer-links">
                            <li><a href="#">Educación Inicial</a></li>
                            <li><a href="#">Educación Primaria</a></li>
                            <li><a href="#">Educación Secundaria</a></li>
                            <li><a href="#">Programa del Diploma IB</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <h5 class="footer-title">Contacto</h5>
                        <ul class="footer-links">
                            <li><i class="fas fa-map-marker-alt me-2"></i>Av. Educación 123, Lima</li>
                            <li><i class="fas fa-phone me-2"></i>+51 123 456 789</li>
                            <li><i class="fas fa-envelope me-2"></i>info@eduka.edu.pe</li>
                            <li><i class="fas fa-clock me-2"></i>Lun-Vie: 8:00-17:00</li>
                        </ul>
                    </div>
                </div>
                <hr class="my-4" style="border-color: #513737;">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="mb-0"><b>&copy; 2026 Eduka Perú S.A.</b> Todos los derechos reservados.
                            <a href="#" class="text-decoration-none ms-2">Política de Privacidad</a> |
                            <a href="#" class="text-decoration-none ms-1">Términos de Servicio</a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- AOS Animation Library -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

        <!-- Custom JavaScript -->
        <script>
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });

            // Counter Animation
            function animateCounter(element, target) {
                let current = 0;
                const increment = target / 100;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        element.textContent = target;
                        clearInterval(timer);
                    } else {
                        element.textContent = Math.floor(current);
                    }
                }, 30);
            }

            // Intersection Observer for counters
            const observerOptions = {
                threshold: 0.5,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const statNumber = entry.target.querySelector('.stat-number');
                        const target = parseInt(statNumber.getAttribute('data-target'));
                        animateCounter(statNumber, target);
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all stat cards
            document.querySelectorAll('.stat-card').forEach(card => {
                observer.observe(card);
            });

            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Navbar background change on scroll
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.style.background = 'rgba(5, 50, 85, 0.95)';
                    navbar.style.backdropFilter = 'blur(10px)';
                } else {
                    navbar.style.background = 'linear-gradient(135deg, var(--primary-color), var(--secondary-color))';
                }
            });

            // Add loading animation
            window.addEventListener('load', function() {
                document.body.classList.add('fade-in-up');
            });
        </script>

        <!-- ===================== WHATSAPP WIDGET EDUKA ===================== -->
        <!-- ================= WHATSAPP WIDGET EDUKA PRO ================= -->
        <div id="wa-widget">

            <div id="wa-button" onclick="toggleWA()">
                <img src="imagenes/whatsapp.png" alt="WhatsApp Chat">
            </div>
            <div id="wa-hint">
                ¿Necesitas ayuda?
            </div>
            <script>
                document.addEventListener("click", function(e) {
                    const popup = document.getElementById("wa-popup");
                    const button = document.getElementById("wa-button");

                    // Si el popup está abierto
                    if (popup.classList.contains("active")) {

                        // Si el click NO fue ni en el botón ni dentro del popup → cerrar
                        if (!popup.contains(e.target) && !button.contains(e.target)) {
                            popup.classList.remove("active");
                        }
                    }
                });
            </script>

            <div id="wa-popup">
                <div class="wa-header">
                    <img src="imagenes/imgTioEduka.png" alt="Eduka Soporte">
                    <div>
                        <strong style="font-family: 'Quicksand', sans-serif !important;">Eduka Soporte</strong>
                        <span id="wa-status" class="online"
                            style="font-family:'Quicksand',sans-serif; font-weight:bold !important;">
                            En línea
                        </span>

                    </div>
                </div>

                <div class="wa-body">
                    <div class="wa-msg" style="font-family: 'Quicksand', sans-serif !important;">
                        Hola 👋 <br>
                        ¿En qué podemos ayudarte?
                    </div>

                    <a href="#" id="wa-chat-btn" class="wa-chat">
                        Chatear por WhatsApp
                    </a>

                </div>
            </div>

        </div>

        <script>
            function toggleWA() {
                document.getElementById('wa-popup').classList.toggle('active');
            }

            function updateWhatsAppStatus() {

                const ahora = new Date();
                const hora = ahora.getHours(); // hora local del navegador (Perú)

                const horaInicio = 7; // 7 am
                const horaFin = 17; // 5 pm

                const status = document.getElementById('wa-status');
                const btn = document.getElementById('wa-chat-btn');

                if (hora >= horaInicio && hora < horaFin) {
                    // 🟢 EN LÍNEA
                    status.textContent = "En línea · Atención 7am - 5pm";
                    status.style.color = "#fff";

                    btn.style.pointerEvents = "auto";
                    btn.style.opacity = "1";

                    btn.href =
                        "https://wa.me/51963150918?text=Hola%2C%20estoy%20interesado%20en%20el%20colegio%20Eduka%20y%20quisiera%20m%C3%A1s%20informaci%C3%B3n.";
                    btn.target = "_blank";

                } else {
                    // 🔴 DESCONECTADO
                    status.textContent = "Desconectado · Atención 7am - 5pm";
                    status.style.color = "#fff";

                    btn.removeAttribute("href");
                    btn.style.pointerEvents = "none";
                    btn.style.opacity = "0.6";
                    btn.textContent = "Fuera de horario";
                }
            }

            // Ejecutar al cargar
            updateWhatsAppStatus();
        </script>

        <style>
            #wa-hint {
                position: absolute;
                bottom: clamp(72px, 8vw, 90px);
                right: 0;
                background: white;
                padding: 10px 15px;
                border-radius: 20px;
                font-size: 14px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, .2);
                opacity: 0;
                transform: translateX(10px);
                transition: .4s ease;
                white-space: nowrap;
            }

            #wa-hint.show {
                opacity: 1;
                transform: translateX(0);
            }

            #wa-widget {
                position: fixed;
                bottom: 25px;
                right: 25px;
                z-index: 9999;
                font-family: Arial, Helvetica, sans-serif;
            }

            #wa-button {
                width: clamp(65px, 6vw, 80px);
                height: clamp(65px, 6vw, 80px);
                background: #25D366;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                box-shadow: 0 10px 30px rgba(0, 0, 0, .3);
                transition: .3s;
            }

            #wa-button:hover {
                transform: scale(1.1);
            }

            #wa-button img {
                width: clamp(32px, 6vw, 42px);;
            }

            #wa-popup {
                position: absolute;
                bottom: clamp(75px, 8vw, 95px);
                right: 0;
                width: 300px;
                background: #f4f4f4;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, .25);
                overflow: hidden;
                transform: scale(0);
                transform-origin: bottom right;
                transition: .35s ease;
            }

            #wa-popup.active {
                transform: scale(1);
            }

            /* Header */
            .wa-header {
                background: linear-gradient(135deg, #25D366, #1ebe5d);
                color: white;
                padding: 12px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .wa-header img {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                border: 2px solid white;
            }

            .wa-header strong {
                display: block;
            }

            .online {
                font-size: 12px;
                color: #d4ffe5;
            }

            /* Body */
            .wa-body {
                padding: 15px;
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .wa-msg {
                background: white;
                padding: 12px;
                border-radius: 10px;
                font-size: 14px;
                width: fit-content;
                max-width: 80%;
                box-shadow: 0 5px 15px rgba(0, 0, 0, .1);
            }

            .wa-chat {
                background: #25D366;
                color: white;
                text-align: center;
                padding: 12px;
                border-radius: 10px;
                text-decoration: none;
                font-weight: bold;
                transition: .3s;
            }

            .wa-chat:hover {
                background: #1ebe5d;
            }
        </style>
        <script>
            function toggleWA() {
                document.getElementById('wa-popup').classList.toggle('active');
            }
        </script>

        <script>
            const hint = document.getElementById("wa-hint");
            const popup = document.getElementById("wa-popup");

            function showHint() {
                if (!popup.classList.contains("active")) {
                    hint.classList.add("show");

                    setTimeout(() => {
                        hint.classList.remove("show");
                    }, 4000);
                }
            }

            // Mostrar cada 10 segundos
            setInterval(showHint, 20000);
        </script>

    </body>

    </html>
@endsection
