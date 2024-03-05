<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\Client as BaseClient;

class Client extends BaseClient
{
    use HasFactory;
}
