<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyFormRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use App\Services\EvaluationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    protected $companyService;
    protected $evaluationService;

    public function __construct(CompanyService $companyService, EvaluationService $evaluationService)
    {
        $this->companyService = $companyService;
        $this->evaluationService = $evaluationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return CompanyResource::collection($this->companyService->getCompanies($request->input('filter', null)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyFormRequest $request)
    {
        $company = $this->companyService->save($request->validated());

        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $evaluations = json_decode($this->evaluationService->getEvaluationsCompany($company->id))->data;

        return (new CompanyResource($company))->additional([
            'data' => ['evaluations' => $evaluations]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyFormRequest $request, Company $company)
    {
        return new CompanyResource($this->companyService->save($request->validated(), $company));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $this->companyService->delete($company);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
