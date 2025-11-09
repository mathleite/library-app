<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookAuthorRelationModel extends Model
{
    protected $table = 'Livro_Autor';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'Livro_Codl',
        'Autor_CodAu',
    ];

    /**
     * @return BelongsTo
     * @phpstan-return BelongsTo<BookModel, $this>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(BookModel::class, 'Livro_Codl', 'Codl');
    }

    /**
     * @return BelongsTo
     * @phpstan-return BelongsTo<AuthorModel, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(AuthorModel::class, 'Autor_CodAu', 'CodAu');
    }
}
