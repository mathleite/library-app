<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectModel extends Model
{
    protected $table = 'Assunto';

    protected $primaryKey = 'codAs';

    public $timestamps = false;

    protected $fillable = [
        'Descricao',
    ];
}
