<?php

namespace App\Infrastructure\Framework\Controllers;

use App\Application\UseCases\Report\GetDetailedReportData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @param Request $request
     * @param GetDetailedReportData $useCase
     * @return View
     */
    public function index(Request $request, GetDetailedReportData $useCase): View
    {
        $filters = $request->only(['autor_id', 'assunto_id', 'search']);
        $perPage = $request->get('per_page', 10);

        $reportData = $useCase->execute($filters, $perPage);

        return view('reports.detailed', $reportData);
    }
}
