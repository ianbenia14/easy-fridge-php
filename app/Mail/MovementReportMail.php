<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class MovementReportMail extends Mailable
{
    public function __construct(public array $movements) {}

    public function build()
    {
        $list = implode('', array_map(
            fn($m) => "<li><strong>{$m['product_name']}</strong> — {$m['action']} — quantidade: {$m['quantity']} — {$m['created_at']}</li>",
            $this->movements
        ));

        return $this->subject('Relatório de movimentação da geladeira')
                    ->html("
                        <h2>Relatório de Movimentação</h2>
                        <ul>{$list}</ul>
                    ");
    }
}