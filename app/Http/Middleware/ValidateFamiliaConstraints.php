<?php

namespace App\Http\Middleware;

use App\Services\FamiliaService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateFamiliaConstraints
{
    public function __construct(private FamiliaService $familiaService) {}

    public function handle(Request $request, Closure $next): Response
    {
        // Este middleware se puede usar para validaciones adicionales en tiempo real
        // Por ahora, las validaciones están en FamiliaRequest

        return $next($request);
    }
}
