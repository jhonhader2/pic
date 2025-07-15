@extends('layouts.auth')

@section('title', 'Iniciar Sesión - PIC')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <!-- Mensaje de error por acceso no autorizado -->
                        @if (session('error'))
                            <x-alert type="warning">
                                {{ session('error') }}
                            </x-alert>
                        @endif

                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="fas fa-user-circle text-primary"></i>
                            </div>
                            <h2 class="fw-bold" style="color: #0066CC;">Iniciar Sesión</h2>
                            <p class="text-muted">Accede a tu cuenta del PIC</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <x-form-input name="email" label="Correo Electrónico" type="email"
                                placeholder="tu@email.com" required icon="envelope" autofocus />

                            <x-form-input name="password" label="Contraseña" type="password" placeholder="Tu contraseña"
                                required icon="lock" />

                            <!-- Remember Me -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Iniciar Sesión
                                </button>
                            </div>

                            <!-- Links -->
                            <div class="text-center">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif

                                @if (Route::has('register'))
                                    <div class="mt-3">
                                        <span class="text-muted">¿No tienes cuenta?</span>
                                        <a href="{{ route('register') }}" class="text-decoration-none ms-1">
                                            Regístrate aquí
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
