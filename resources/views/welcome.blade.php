@extends('layouts.app')

@section('title', 'PIC - Plan de Intervenciones Colectivas')

@section('content')
    <!-- Hero Section -->
    <div class="container-fluid py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4" style="color: #0066CC;">Plan de Intervenciones Colectivas</h1>
                    <p class="lead text-muted mb-4">Programa de salud pública del municipio de San José del Guaviare</p>
                    <p class="text-muted mb-5">Desarrollado por la Secretaría de Salud Municipal y la Alcaldía de San
                        José del Guaviare, a través de la ESE Red de Servicios de Salud de Primer Nivel</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#servicios" class="btn btn-primary btn-lg px-4">Nuestros Servicios</a>
                        <a href="#contacto" class="btn btn-outline-primary btn-lg px-4">Contáctanos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container py-5" id="servicios">
        <div class="row g-4">
            <div class="col-md-4">
                <x-card title="Atención Primaria en Salud"
                    subtitle="Brindamos servicios de salud integrales y accesibles a toda la población del departamento del Guaviare, con enfoque en prevención y promoción."
                    icon="heartbeat" color="primary" />
            </div>

            <div class="col-md-4">
                <x-card title="Intervenciones Colectivas"
                    subtitle="Implementamos estrategias de salud pública dirigidas a grupos poblacionales específicos para mejorar indicadores de salud y calidad de vida."
                    icon="users" color="success" />
            </div>

            <div class="col-md-4">
                <x-card title="Promoción y Prevención"
                    subtitle="Desarrollamos programas educativos y preventivos para fomentar hábitos saludables y reducir factores de riesgo en la población."
                    icon="shield-alt" color="info" />
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4" style="color: #0066CC;">Sobre el PIC</h2>
                    <p class="lead text-muted mb-4">
                        El Plan de Intervenciones Colectivas (PIC) es un programa estratégico de salud pública
                        del municipio de San José del Guaviare, Colombia.
                    </p>
                    <p class="text-muted mb-4">
                        Desarrollado a través de esfuerzos mutuos entre la Secretaría de Salud Municipal,
                        la Alcaldía de San José del Guaviare y la ESE Red de Servicios de Salud de Primer Nivel,
                        trabajamos para garantizar el acceso a servicios de salud de calidad para toda la población.
                    </p>
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="fw-bold text-primary">100%</h3>
                            <p class="text-muted">Cobertura Departamental</p>
                        </div>
                        <div class="col-4">
                            <h3 class="fw-bold text-primary">24/7</h3>
                            <p class="text-muted">Atención Disponible</p>
                        </div>
                        <div class="col-4">
                            <h3 class="fw-bold text-primary">ESE</h3>
                            <p class="text-muted">Red de Servicios</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-primary bg-opacity-10 p-5 rounded">
                        <h4 class="fw-bold mb-3">Nuestra Misión</h4>
                        <p class="text-muted mb-4">
                            Garantizar el acceso equitativo a servicios de salud integrales y de calidad
                            para toda la población del municipio de San José del Guaviare, a través de intervenciones
                            colectivas efectivas y sostenibles.
                        </p>
                        <h4 class="fw-bold mb-3">Nuestra Visión</h4>
                        <p class="text-muted">
                            Ser reconocidos como el programa líder en salud pública del municipio,
                            contribuyendo significativamente a mejorar los indicadores de salud y
                            la calidad de vida de los habitantes de San José del Guaviare.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="container py-5" id="contacto">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold mb-4" style="color: #0066CC;">Contáctanos</h2>
                <p class="lead text-muted mb-5">
                    ¿Necesitas información sobre nuestros servicios de salud o quieres conocer más sobre el PIC?
                </p>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div class="text-start">
                                <h6 class="fw-bold mb-1">Email</h6>
                                <p class="text-muted mb-0">pic@guaviare.gov.co</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                            <div class="text-start">
                                <h6 class="fw-bold mb-1">Teléfono</h6>
                                <p class="text-muted mb-0">+57 (8) 584-XXXX</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div class="text-start">
                                <h6 class="fw-bold mb-1">Dirección</h6>
                                <p class="text-muted mb-0">San José del Guaviare<br>Guaviare, Colombia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
