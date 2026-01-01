<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PetugasMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('petugas.login');
        }

        if (auth()->user()->role !== 'petugas') {
            abort(403, 'Unauthorized access');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('petugas.login')->with('error', 'Akun Anda tidak aktif');
        }

        return $next($request);
    }
}
