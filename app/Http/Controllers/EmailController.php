<?php
namespace App\Http\Controllers;

use App\Mail\ExpiringProductsMail;
use App\Models\FridgeProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function sendExpiringProducts(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'days'  => 'integer|min:1',
        ]);

        $days = $request->days ?? 7;

        $products = FridgeProduct::with('product')
            ->whereDate('expiration_date', '<=', now()->addDays($days))
            ->whereDate('expiration_date', '>=', now())
            ->get()
            ->map(fn($fp) => [
                'name'            => $fp->product->name,
                'expiration_date' => $fp->expiration_date,
            ])
            ->toArray();

        if (empty($products)) {
            return response()->json([
                'message' => 'Nenhum produto vencendo nos próximos ' . $days . ' dias.'
            ]);
        }

        Mail::to($request->email)->send(new ExpiringProductsMail($products));

        return response()->json([
            'message'              => 'Email enviado com sucesso!',
            'produtos_encontrados' => count($products),
        ]);
    }
}
