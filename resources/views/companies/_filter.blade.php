<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col">
                <a href="{{ request()->fullUrlWithQuery(['trash' => false, 'page' => null ]) }}" class="btn {{ !request()->query('trash') ? 'text-primary' : 'text-secondary'}}">All</a>
                 |
                <a href="{{ request()->fullUrlWithQuery(['trash' => true, 'page' => null ]) }}" class="btn {{ request()->query('trash') ? 'text-primary' : 'text-secondary'}}">Trash</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <form>
            <input type="hidden" name="trash" value="{{ request()->query('trash') }}">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="search-input" name="search"
                            value="{{ request()->query('search') }}" placeholder="Search..." aria-label="Search..."
                            aria-describedby="button-addon2">
                        <div class="input-group-append">
                            @if (request()->filled('search'))
                                <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('search-input').value = '', this.form.submit()">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            @endif
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
