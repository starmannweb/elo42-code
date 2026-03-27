<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Service extends Model
{
    protected static string $table = 'services';
    protected static array $fillable = ['name','slug','description','rules','price','recurrence','status','sort_order'];
}
