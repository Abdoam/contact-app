<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = auth()->user()->companies()->withCount('contacts')->allowedTrash()->allowedSorts(['name', 'address', 'email', 'website'], '-id')->allowedSearch('name', 'address', 'email', 'website')->latest()->paginate(10);

        return view("companies.index", compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = new Company;

        return view('companies.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $request->user()->companies()->create($request->all());

        return redirect()->route('companies.index')->with('message', "Company has been added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return view("companies.show", compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view("companies.edit", compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $company->update($request->all());

        return redirect()->route('companies.index')->with('message', "Company has been updated successfully");
    }
    public function restore(Company $company)
    {
        $company->restore();
        return back()->with('message', 'Company has been restored from trash.')->with('undoRoute', $this->getUndoRoute('companies.destroy', $company));
    }
    protected function getUndoRoute($name, $resource)
    {
        return request()->missing('undo') ? route($name, [$resource->id, 'undo' => true]) : null;
    }
    public function forceDelete(Company $company)
    {
        $company->forceDelete();
        return back()->with('message', 'Company has been removed permanently.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        $redirect = request()->query('redirect');
        return ($redirect ? redirect()->route($redirect) : back())->with('message', 'Company has been moved to trash.')->with('undoRoute', $this->getUndoRoute('companies.restore', $company));
    }
}
