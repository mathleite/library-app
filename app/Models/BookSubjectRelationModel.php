<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookSubjectRelationModel extends Model
{
    protected $table = 'Livro_Assunto';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'Livro_Codl',
        'Assunto_codAs',
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
     * @return BelongsTo<SubjectModel, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(SubjectModel::class, 'Assunto_codAs', 'codAs');
    }
}
