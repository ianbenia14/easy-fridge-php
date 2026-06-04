<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ProductRemovedMail extends Mailable
{
    public function __construct(
        public string $productName,
        public int    $quantity
    ) {}

    public function build()
    {
        return $this->subject('Produto retirado da geladeira')
                    ->html("
                        <h2>Retirada registrada</h2>
                        <p>Produto: <strong>{$this->productName}</strong></p>
                        <p>Quantidade retirada: {$this->quantity}</p>
                    ");
    }
}
