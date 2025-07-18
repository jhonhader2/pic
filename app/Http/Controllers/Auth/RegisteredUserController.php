<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\PersonaRequest;
use App\Services\PersonaService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;

class RegisteredUserController extends Controller
{
    public function __construct(
        private PersonaService $personaService
    ) {}

    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $userRequest, PersonaRequest $personaRequest): RedirectResponse
    {
        try {
            // Combinar datos validados de usuario y persona
            $userData = $userRequest->validated();
            $personaData = $personaRequest->validated();
            $combinedData = array_merge($userData, $personaData);

            // Crear usuario y persona usando el servicio
            $user = $this->personaService->createUserWithPersona($combinedData);

            // Disparar evento de registro
            event(new Registered($user));

            return redirect(route('dashboard'))
                ->with('success', 'Usuario registrado exitosamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Hubo un problema al registrar el usuario: ' . $e->getMessage());
        }
    }
}
