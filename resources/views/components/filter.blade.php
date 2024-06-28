<form action="{{ route($route) }}"  method="GET" enctype="multipart/form-data">
    <div class="row">
        <div class="form-group col-md-6 col-sm-12 mb-2">
                <label for="filterType">Filter by type</label>
                <select name="filterType" id="filterType" class="form-select">
                    <option value="" selected>
                        All
                    </option>
                    @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ request()->get('filterType') ==  $type->id? 'selected':'' }}>
                        {{ Str::ucfirst(strtolower($type->category_name)) }}
                    </option>
                    @endforeach
                </select>
        </div>
        @if (request()->is('document/all'))
        <div class="form-group col-md-6 col-sm-12 mb-2">
            <label for="filterStatus">Status</label>
            <select name="filterStatus" id="filterStatus" class="form-select">
                <option value="" selected>
                    All
                </option>
                <option value="completed" {{ request()->get('filterStatus') ==  'completed'? 'selected':'' }}>
                    Completed
                </option>
                <option value="received" {{ request()->get('filterStatus') ==  'received'? 'selected':'' }}>
                    In progress
                </option>
                <option value="pending receive" {{ request()->get('filterStatus') == 'pending receive'? 'selected':'' }}>
                    Pending receive
                </option>
            </select>
        </div>
        @else
        <div class="form-group col-md-6 col-sm-12 mb-2">
            <label for="filterUser">Forwarded by</label>
            <select name="filterUser" id="filterUser" class="form-select">
                <option value="" selected>
                    All
                </option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ request()->get('filterUser') ==  $user->id? 'selected':'' }}>
                    {{ $user->is_admin? 'Admin' : strtoupper($user->office->office_name) }}<br>-
                    {{ Str::ucfirst(strtolower($user->first_name)) }}
                    {{ Str::ucfirst(strtolower(Str::substr($user->middle_name, 0, 1))) }}.
                    {{ Str::ucfirst(strtolower($user->last_name)) }}
                </option>
                @endforeach
            </select>
        </div>
        @endif


    </div>
    <div class="row">
        <div class="form-group col-md-6 col-sm-12 mb-2">
            <label for="filterDateFrom">Date created from</label>
            <input type="date" name="filterDateFrom" id="filterDateFrom" value="{{ request()->get('filterDateFrom') != ''? request()->get('filterDateFrom'):'' }}" class="form-control">
        </div>
        <div class="form-group col-md-6 col-sm-12 mb-2">
            <label for="filterDateTo">Date created to</label>
            <input type="date" name="filterDateTo" id="filterDateTo" class="form-control" value="{{ request()->get('filterDateTo') != ''? request()->get('filterDateTo'):'' }}">
        </div>
    </div>
    <div class="d-flex justify-content-end w-100 gap-2">
        <a href="{{route($route)}}" class="btn btn-danger w-auto col-sm-12">
            <i class='ri-delete-bin-2-line'></i> Clear Filter
        </a>
        <button type='submit' class="btn btn-primary w-auto col-sm-12">
            <i class='ri-filter-2-line'></i> Filter
        </button>
    </div>
</form>
