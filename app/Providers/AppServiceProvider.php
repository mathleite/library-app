<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: \App\Domain\Contracts\Persistence\AuthorRepository::class,
            concrete: \App\Infrastructure\Framework\Persistence\Eloquent\AuthorRepository::class
        );
        $this->app->bind(
            abstract: \App\Domain\Contracts\Persistence\BookRepository::class,
            concrete: \App\Infrastructure\Framework\Persistence\Eloquent\BookRepository::class
        );
        $this->app->bind(
            abstract: \App\Domain\Contracts\Persistence\BookAuthorRelationRepository::class,
            concrete: \App\Infrastructure\Framework\Persistence\Eloquent\BookAuthorRelationRepository::class
        );
        $this->app->bind(
            abstract: \App\Domain\Contracts\Persistence\SubjectRepository::class,
            concrete: \App\Infrastructure\Framework\Persistence\Eloquent\SubjectRepository::class
        );
        $this->app->bind(
            abstract: \App\Domain\Contracts\Persistence\BookSubjectRelationRepository::class,
            concrete: \App\Infrastructure\Framework\Persistence\Eloquent\BookSubjectRelationRepository::class
        );
        $this->app->bind(
            abstract: \App\Domain\Contracts\Persistence\BookReportRepository::class,
            concrete: \App\Infrastructure\Framework\Persistence\Eloquent\BookReportRepository::class
        );
        $this->app->bind(
            abstract: \App\Domain\Contracts\Persistence\DatabaseTransactionManager::class,
            concrete: \App\Infrastructure\Framework\Persistence\LaravelTransactionManager::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
