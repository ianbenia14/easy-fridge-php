<?php
namespace App\Http\Controllers;

use App\Mail\ExpiringProductsMail;
use App\Models\FridgeProduct;
use Illuminate\Http\JsonResponse;
use App\Mail\MovementReportMail;
use App\Services\FridgeService;
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

    public function sendMovementReport(Request $request): JsonResponse
{
    $request->validate([
        'period' => 'required|in:daily,monthly',
    ]);

    $userId = $request->user()->id;
    $movements = app(FridgeService::class)->getMovementsByUser($userId, $request->period);

    if (empty($movements)) {
        return response()->json([
            'message' => 'Nenhuma movimentação encontrada no período.'
        ]);
    }

    Mail::to($request->user()->email)->send(new MovementReportMail($movements));

    return response()->json([
        'message'                => 'Relatório enviado com sucesso!',
        'movimentacoes_enviadas' => count($movements),
    ]);
    }
}
