@extends('layouts.main')
@section('title', 'Contact App | All Companies')
@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-title">
                            <div class="d-flex align-items-center">
                                <h2 class="mb-0">
                                    All Companies
                                    @if (request()->query('trash'))
                                        <small>(In Trash)</small>
                                    @endif
                                </h2>
                                <div class="ml-auto">
                                    <a href='{{ route('companies.create') }}' class="btn btn-success"><i
                                            class="fa fa-plus-circle"></i> Add New</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('companies._filter' /*, ['companies' => $companies]*/)
                            @if ($message = session('message'))
                                <div class="alert alert-success">{{ $message }}
                                    @if ($undoRoute = session('undoRoute'))
                                        <form action="{{ $undoRoute }}" method="POST" style="display: inline">
                                            @csrf
                                            @method('delete')
                                            <button class="btn alert-link">Undo</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">
                                            {!! sortable('Name') !!}
                                        </th>
                                        <th scope="col">
                                            {!! sortable('Address') !!}
                                        </th>
                                        <th scope="col">
                                            {!! sortable('Email') !!}
                                        </th>
                                        <th scope="col">
                                            {!! sortable('Website') !!}
                                        </th>
                                        <th scope="col">Num Of Contacts</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $showTrashButtons = request()->query('trash') ? true : false;
                                    @endphp
                                    @forelse ($companies as $index => $company)
                                        {{-- <tr @if ($loop->first || ($loop->last && $loop->odd)) class="table-primary" @endif> --}}
                                        {{-- @if ($id == 1)
                                        @continue
                                        @endif --}}
                                        {{-- @continue($id == 1) --}}
                                        {{-- note break same syntax of continue --}}

                                        @include('companies._company', [
                                            'company' => $company,
                                            'index' => $index,
                                        ])
                                    @empty
                                        @include('companies._empty')
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $companies->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
    </main>
    {{-- @endif --}}
@endsection
