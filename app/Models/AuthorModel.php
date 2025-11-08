<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $CodAu
 * @property string $Nome
 */
class AuthorModel extends Model
{
    protected $table = 'Autor';

    protected $primaryKey = 'CodAu';

    public $timestamps = false;

    protected $fillable = [
        'Nome',
    ];
}
