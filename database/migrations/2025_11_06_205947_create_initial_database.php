<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Autor', function (Blueprint $table) {
            $table->id('CodAu')->primary();
            $table->string('Nome', 40)->unique();
        });
        Schema::create('Assunto', function (Blueprint $table) {
            $table->id('codAs')->primary();
            $table->string('Descricao', 20)->unique();
        });
        Schema::create('Livro', function (Blueprint $table) {
            $table->id('Codl')->primary();
            $table->string('Titulo', 40)
                ->index();
            $table->string('Editora', 40)
                ->index();
            $table->integer('Edicao');
            $table->integer('Preco');
            $table->string('AnoPublicacao', 4)
                ->index();
        });
        Schema::create('Livro_Autor', function (Blueprint $table) {
            $table->unsignedInteger('Livro_Codl');
            $table->unsignedInteger('Autor_CodAu');

            $table->foreign('Livro_Codl', 'Livro_Autor_FKIndex1')
                ->references('Codl')
                ->on('Livro')
                ->onDelete('cascade');
            $table->foreign('Autor_CodAu', 'Livro_Autor_FKIndex2')
                ->references('CodAu')
                ->on('Autor')
                ->onDelete('cascade');
        });
        Schema::create('Livro_Assunto', function (Blueprint $table) {
            $table->unsignedInteger('Livro_Codl');
            $table->unsignedInteger('Assunto_codAs');

            $table->foreign('Livro_Codl', 'Livro_Assunto_FKIndex1')
                ->references('Codl')
                ->on('Livro')
                ->onDelete('cascade');
            $table->foreign('Assunto_codAs', 'Livro_Assunto_FKIndex2')
                ->references('codAs')
                ->on('Assunto')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Livro_Assunto');
        Schema::dropIfExists('Livro_Autor');
        Schema::dropIfExists('Livro');
        Schema::dropIfExists('Assunto');
        Schema::dropIfExists('Autor');
    }
};
