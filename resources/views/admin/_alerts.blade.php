@if(session('success'))
<div class="alert alert-success">
    <span class="alert-icon">✓</span>
    <div class="alert-content">{{ session('success') }}</div>
</div>
@endif

@if($errors->has('import') || $errors->has('file'))
<div class="alert alert-error">
    <span class="alert-icon">⚠</span>
    <div class="alert-content">
        @if($errors->has('import')){{ $errors->first('import') }}@endif
        @if($errors->has('file')){{ $errors->first('file') }}@endif
    </div>
</div>
@endif
