<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Persistence\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookReportRepository implements \App\Domain\Contracts\Persistence\BookReportRepository
{
    /**
     * @return void
     * @throws \Exception
     */
    public function createOrReplaceView(): void
    {
        try {
            DB::statement($this->getViewSql());
            Log::info('Book report view created/replaced successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create book report view: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function refreshView(): void
    {
        $this->createOrReplaceView();
    }

    /**
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getReportData(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = DB::table($this->getViewName(), 'brv1')
            ->select(
                'codigo_autor',
                'nome_autor',
                'codigo_livro',
                'titulo_livro',
                'editora',
                'edicao',
                'preco',
                'ano_publicacao',
                'total_livros_autor',
                // Agregar assuntos em uma string
                DB::raw('STRING_AGG(DISTINCT descricao_assunto, \'; \') as assuntos_agregados'),
                DB::raw('COUNT(DISTINCT codigo_assunto) as total_assuntos_livro'),
                // Agregar informações de outros autores
                DB::raw('(SELECT STRING_AGG(DISTINCT brv2.nome_autor, \'; \')
                         FROM relatorio_livros_autores_assuntos brv2
                         WHERE brv2.codigo_livro = brv1.codigo_livro) as todos_autores'),
                DB::raw('(SELECT COUNT(DISTINCT brv2.codigo_autor)
                         FROM relatorio_livros_autores_assuntos brv2
                         WHERE brv2.codigo_livro = brv1.codigo_livro) as total_autores_livro')
            )
            ->groupBy(
                'codigo_autor',
                'nome_autor',
                'codigo_livro',
                'titulo_livro',
                'editora',
                'edicao',
                'preco',
                'ano_publicacao',
                'total_livros_autor'
            );

        $this->applyFilters($query, $filters);

        return $query->orderBy('nome_autor')
            ->orderBy('titulo_livro')
            ->paginate($perPage);
    }

    /**
     * @param $query
     * @param array $filters
     * @return void
     */
    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['autor_id']) && $filters['autor_id']) {
            $query->where('codigo_autor', $filters['autor_id']);
        }

        if (isset($filters['assunto_id']) && $filters['assunto_id']) {
            $query->where('codigo_assunto', $filters['assunto_id']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nome_autor', 'ILIKE', "%{$search}%")
                    ->orWhere('titulo_livro', 'ILIKE', "%{$search}%")
                    ->orWhere('descricao_assunto', 'ILIKE', "%{$search}%")
                    ->orWhere('editora', 'ILIKE', "%{$search}%");
            });
        }
    }

    /**
     * @return bool
     */
    public function viewExists(): bool
    {
        try {
            DB::table($this->getViewName())->limit(1)->count();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * @return string
     */
    private function getViewSql(): string
    {
        return <<<SQL
        CREATE OR REPLACE VIEW relatorio_livros_autores_assuntos AS
        SELECT
            -- Informações do Autor
            a."CodAu" AS codigo_autor,
            a."Nome" AS nome_autor,

            -- Informações do Assunto
            ass."codAs" AS codigo_assunto,
            ass."Descricao" AS descricao_assunto,

            -- Informações do Livro
            l."Codl" AS codigo_livro,
            l."Titulo" AS titulo_livro,
            l."Editora" AS editora,
            l."Edicao" AS edicao,
            l."Preco" AS preco,
            l."AnoPublicacao" AS ano_publicacao,

            -- Estatísticas do autor
            (
                SELECT COUNT(DISTINCT la2."Livro_Codl")
                FROM "Livro_Autor" la2
                WHERE la2."Autor_CodAu" = a."CodAu"
            ) AS total_livros_autor,

            -- Estatísticas do assunto
            (
                SELECT COUNT(DISTINCT las2."Livro_Codl")
                FROM "Livro_Assunto" las2
                WHERE las2."Assunto_codAs" = ass."codAs"
            ) AS total_livros_assunto

        FROM
            "Autor" a
            INNER JOIN "Livro_Autor" la ON a."CodAu" = la."Autor_CodAu"
            INNER JOIN "Livro" l ON la."Livro_Codl" = l."Codl"
            LEFT JOIN "Livro_Assunto" las ON l."Codl" = las."Livro_Codl"
            LEFT JOIN "Assunto" ass ON las."Assunto_codAs" = ass."codAs"
        SQL;
    }

    /**
     * @return string
     */
    public function getViewName(): string
    {
        return 'relatorio_livros_autores_assuntos';
    }

    /**
     * @param array $filters
     * @return array
     */
    public function getReportStats(array $filters = []): array
    {
        $query = DB::table($this->getViewName())
            ->select('codigo_livro', 'preco');

        if (isset($filters['autor_id']) && $filters['autor_id']) {
            $query->where('codigo_autor', $filters['autor_id']);
        }

        if (isset($filters['assunto_id']) && $filters['assunto_id']) {
            $query->where('codigo_assunto', $filters['assunto_id']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nome_autor', 'ILIKE', "%{$search}%")
                    ->orWhere('titulo_livro', 'ILIKE', "%{$search}%")
                    ->orWhere('descricao_assunto', 'ILIKE', "%{$search}%");
            });
        }

        $livrosUnicos = $query->distinct()->get();

        $baseQuery = DB::table($this->getViewName());
        $this->applyFilters($baseQuery, $filters);

        return [
            'totalLivros' => $livrosUnicos->count(),
            'totalAutores' => $baseQuery->distinct()->count('codigo_autor'),
            'totalAssuntos' => $baseQuery->distinct()->count('codigo_assunto'),
            'valorTotal' => $livrosUnicos->sum('preco')
        ];
    }

    /**
     * @return array
     */
    public function getAuthorsForFilter(): array
    {
        return DB::table($this->getViewName())
            ->select('codigo_autor', 'nome_autor')
            ->distinct()
            ->orderBy('nome_autor')
            ->get()
            ->toArray();
    }

    /**
     * @return array
     */
    public function getSubjectsForFilter(): array
    {
        return DB::table($this->getViewName())
            ->select('codigo_assunto', 'descricao_assunto')
            ->whereNotNull('codigo_assunto')
            ->distinct()
            ->orderBy('descricao_assunto')
            ->get()
            ->toArray();
    }
}
