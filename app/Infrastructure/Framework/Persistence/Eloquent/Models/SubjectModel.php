<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $codAs
 * @property int $Descricao
 */
class SubjectModel extends Model
{
    protected $table = 'Assunto';

    protected $primaryKey = 'codAs';

    public $timestamps = false;

    protected $fillable = [
        'Descricao',
    ];

    /**
     * @return HasMany<BookSubjectRelationModel, $this>
     */
    public function bookAuthorRelations(): HasMany
    {
        return $this->hasMany(BookSubjectRelationModel::class, 'Assunto_codAs', 'codAs');
    }
}
