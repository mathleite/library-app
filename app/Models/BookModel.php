<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookModel extends Model
{
    protected $table = 'Livro';

    protected $primaryKey = 'Codl';

    public $timestamps = false;

    protected $fillable = [
        'Titulo',
        'Editora',
        'Edicao',
        'AnoPublicacao',
        'Preco',
    ];

    /**
     * @return HasMany
     * @phpstan-return HasMany<BookSubjectRelationModel, $this>
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(BookSubjectRelationModel::class, 'Livro_Codl', 'Codl');
    }

    /**
     * @return HasMany
     * @phpstan-return HasMany<BookAuthorRelationModel, $this>
     */
    public function authors(): HasMany
    {
        return $this->hasMany(BookAuthorRelationModel::class, 'Livro_Codl', 'Codl');
    }
}
