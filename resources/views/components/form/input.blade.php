<div class="form-group {{ $is_hidden }}">
    <label for="{{ $input_name }}" class="form-label">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $input_name }}" id="{{ $input_name }}" class="form-control"
        placeholder="{{ $placeholder }}" value="{{ old($input_name) }}" {{ $is_required==true? 'required':'' }}>
    @if(isset($errors[$input_name]))
        <div class="alert alert-danger mt-2 mb-0">{{ $errors[$input_name][0] }}</div>
    @endif
</div>
