<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ExpiringProductsMail extends Mailable
{
    public function __construct(public array $products) {}

    public function build()
    {
        $list = implode('', array_map(
            fn($p) => "<li>{$p['name']} — vence em {$p['expiration_date']}</li>",
            $this->products
        ));

        return $this->subject('Produtos próximos do vencimento')
                    ->html("<h2>Atenção!</h2><ul>{$list}</ul>");
    }
}
