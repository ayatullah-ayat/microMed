<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('alias') ? 'has-error' : ''}}">
    {!! Form::label('alias', 'Alias', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('alias', null, ['class' => 'form-control']) !!}
        {!! $errors->first('alias', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
@push('scripts')
<script>
    
    var convertName2Alias = function () {
        var name = $(this).val().trim().toLowerCase().replace(/\s+/g, '_');
        var alias = $('#alias').val();
        if (alias == '') {
            $('#alias').val(name);
        }
    };


    $(function () {
        $('#name').on('change', convertName2Alias);
    });
</script>
@endpush