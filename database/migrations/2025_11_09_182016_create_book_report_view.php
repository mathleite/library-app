<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->createBookReportView();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS Relatorio_Livros_Autores_Assuntos');
    }

    private function createBookReportView(): void
    {
        $sql = <<<SQL
        CREATE OR REPLACE VIEW Relatorio_Livros_Autores_Assuntos AS
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

        DB::statement($sql);
    }
};
