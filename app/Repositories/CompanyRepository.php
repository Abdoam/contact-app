<?php
namespace App\Repositories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class CompanyRepository
{
    public function pluck($onlyTrash = false)
    {
        $contacts_ids = $onlyTrash ? Auth::user()->contacts()->groupBy('company_id')->onlyTrashed()->pluck('company_id') : Auth::user()->contacts()->groupBy('company_id')->pluck('company_id') ;
        $companies = Auth::user()->companies()->find($contacts_ids)->pluck('name', 'id');
        return $companies;
    }
}
?>
