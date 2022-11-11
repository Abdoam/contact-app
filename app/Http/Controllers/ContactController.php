<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Auth\Events\Verified;
use Illuminate\Queue\NullQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function __construct(protected CompanyRepository $company)
    {
        $this->middleware(['auth', 'verified']);
    }
    public function index(Request $request)
    {
        $companies = $this->company->pluck(request('trash')); // $company->pluck();)->where(function ($query)
        //DB::enableQueryLog();
        $contacts = Auth::user()->contacts()->with('company')->allowedTrash()->allowedSorts(['first_name', 'last_name', 'email'], '-id')->allowedFilters('company_id')->allowedSearch('first_name', 'last_name', 'email', 'address', 'phone')->paginate(10);
        //dump(DB::getQueryLog());
        return view('contacts.index', compact('contacts') /* This function creates an array from variables and their values. ['contacts' => $contacts]*/)->with('companies', $companies);
    }
    public function show(Request $request,Contact $contact)
    {
        return view('contacts.show')->with('contact', $contact);
    }
    public function edit(Request $request, Contact $contact)
    {
        $companies = $this->company->pluck();
        return view('contacts.edit', compact('companies', 'contact'));
    }
    public function update(ContactRequest $request, Contact $contact)
    {
        $contact->update($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contact has been updated successfully');
    }
    public function destroy(Contact $contact)
    {
        $contact->delete();
        $redirect = request()->query('redirect');
        return ($redirect ? redirect()->route($redirect) : back())->with('message', 'Contact has been moved to trash.')->with('undoRoute', $this->getUndoRoute('contacts.restore', $contact));
    }
    public function restore(Contact $contact)
    {
        $contact->restore();
        return back()->with('message', 'Contact has been restored from trash.')->with('undoRoute', $this->getUndoRoute('contacts.destroy', $contact));
    }
    protected function getUndoRoute($name, $resource)
    {
        return request()->missing('undo') ? route($name, [$resource->id, 'undo' => true]) : null;
    }
    public function forceDelete(Contact $contact)
    {
        $contact->forceDelete();
        return back()->with('message', 'Contact has been removed permanently.');
    }
    public function create()
    {
        $contact = new Contact();
        $companies = $this->company->pluck();
        return view('contacts.create', compact('companies', 'contact'));
    }
    public function store(ContactRequest $request)
    {
        $request->user()->contacts()->create($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully');
    }
    public function contactValidate($request, $rules = [], $params = [])
    {

        $nameRule = [
            'required', 'string', 'max:50',
            Rule::unique('contacts')->where(fn ($query) => $query->where('first_name', $request->input('first_name'))
                ->where('last_name', $request->input('last_name')))
        ];
        $request->validate(
            array_merge([
                'first_name' => $nameRule,
                'last_name' => 'required|string|max:50',
                'email' => 'required|email|unique:contacts,email',
                'phone' => 'nullable',
                'address' => 'nullable',
                'company_id' => 'required|exists:companies,id'
            ], $rules),
            array_merge(
                [
                    'first_name.unique' => '"' . $request->input('first_name') . ' ' . $request->input('last_name') . '"' . ' this name has already been taken.',
                ],
                $params
            ),
            ['company_id' => 'company'],
        );
    }
}
