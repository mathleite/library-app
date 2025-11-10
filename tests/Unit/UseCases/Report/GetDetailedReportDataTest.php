<?php

declare(strict_types=1);

use App\Application\UseCases\Report\GetDetailedReportData;
use App\Domain\Contracts\Persistence\BookReportRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Mockery as m;

it('should create view when it does not exist and return a merged report payload', function () {
    $repository = m::mock(BookReportRepository::class);

    $filters = ['author_id' => 2];
    $perPage = 15;

    $paginator = m::mock(LengthAwarePaginator::class);

    $repository->shouldReceive('viewExists')->once()->andReturn(false);
    $repository->shouldReceive('createOrReplaceView')->once();

    $repository->shouldReceive('getReportData')
        ->once()
        ->with($filters, $perPage)
        ->andReturn($paginator);

    $repository->shouldReceive('getReportStats')
        ->once()
        ->with($filters)
        ->andReturn(['total' => 10, 'totalPerSubject' => ['Tech' => 5]]);

    $repository->shouldReceive('getAuthorsForFilter')
        ->once()
        ->andReturn([['id' => 1, 'name' => 'Author A']]);

    $repository->shouldReceive('getSubjectsForFilter')
        ->once()
        ->andReturn([['id' => 3, 'name' => 'Subject A']]);

    $useCase = new GetDetailedReportData($repository);

    $result = $useCase->execute($filters, $perPage);

    expect($result)
        ->toBeArray()
        ->and($result)
        ->toHaveKeys(['total', 'totalPerSubject', 'reportData', 'autores', 'assuntos', 'filters'])
        ->and($result['reportData'])->toBe($paginator)
        ->and($result['filters'])->toBe($filters)
        ->and($result['autores'][0]['name'])->toBe('Author A')
        ->and($result['assuntos'][0]['name'])->toBe('Subject A');
});

it('should not try to create the view when it already exists', function () {
    $repository = m::mock(BookReportRepository::class);

    $filters = [];
    $perPage = 10;

    $paginator = m::mock(LengthAwarePaginator::class);

    $repository->shouldReceive('viewExists')->once()->andReturn(true);
    $repository->shouldReceive('createOrReplaceView')->never();

    $repository->shouldReceive('getReportData')
        ->once()
        ->with($filters, $perPage)
        ->andReturn($paginator);

    $repository->shouldReceive('getReportStats')
        ->once()
        ->with($filters)
        ->andReturn([]);

    $repository->shouldReceive('getAuthorsForFilter')->once()->andReturn([]);
    $repository->shouldReceive('getSubjectsForFilter')->once()->andReturn([]);

    $useCase = new GetDetailedReportData($repository);

    $result = $useCase->execute($filters, $perPage);

    expect($result)
        ->toHaveKeys(['reportData', 'autores', 'assuntos', 'filters']);
});
