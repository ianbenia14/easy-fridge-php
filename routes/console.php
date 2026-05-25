<?php

use App\Models\Product;
use App\Models\User;
use App\Mail\ExpiringProductsMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $expiring = Product::where('expiration_date', '<=', now()->addDays(3))
                       ->get()->toArray();

    if (!empty($expiring)) {
        User::all()->each(fn($user) =>
            Mail::to($user->email)->send(new ExpiringProductsMail($expiring))
        );
    }
})->daily();
