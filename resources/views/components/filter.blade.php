<form action="{{ route($route) }}"  method="GET" enctype="multipart/form-data">
    <div class="row">
        <div class="form-group col-md-6 col-sm-12 mb-2">
            <label for="type">Filter by type</label>
            <select name="type" id="typetype" class="form-select">
                <option value="" selected>
                    All
                </option>
                @foreach ($types as $type)
                <option value="{{ $type->id }}" {{ request()->get('type') ==  $type->id? 'selected':'' }}>
                    {{ Str::ucfirst(strtolower($type->category_name)) }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6 col-sm-12 mb-2">
            <label for="user">Forwarded by</label>
            <select name="user" id="user" class="form-select">
                <option value="" selected>
                    All
                </option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ request()->get('user') ==  $user->id? 'selected':'' }}>
                    {{ $user->is_admin? 'Admin' : strtoupper($user->office->office_name) }}<br>-
                    {{ Str::ucfirst(strtolower($user->first_name)) }}
                    {{ Str::ucfirst(strtolower(Str::substr($user->middle_name, 0, 1))) }}.
                    {{ Str::ucfirst(strtolower($user->last_name)) }}
                </option>
                @endforeach
            </select>
        </div>

    </div>
    <div class="row">
        <div class="form-group col-md-6 col-sm-12 mb-2">
            <label for="date_from">Date created from</label>
            <input type="date" name="date_from" id="date_from" value="{{ request()->get('date_from') != ''? request()->get('date_from'):'' }}" class="form-control">
        </div>
        <div class="form-group col-md-6 col-sm-12 mb-2">
            <label for="date_to">Date created to</label>
            <input type="date" name="date_to" class="form-control" value="{{ request()->get('date_to') != ''? request()->get('date_to'):'' }}">
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
