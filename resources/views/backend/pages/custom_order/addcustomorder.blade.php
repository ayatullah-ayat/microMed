@extends('backend.layouts.master')

@section('title', 'Add Order')

@section('content')
    <div>
        <div class="container card p-3 shadow">
            <h4 class="text-dark font-weight-bold text-dark">Custom Order Information</h4>
            <div class="row">

                <div class="col-md-6" data-col="col">
                    <div class="form-group">
                        <label for="customer"> Customer<span style="color: red;" class="req">*</span></label>
                        <select name="customer" class="customer" data-required id="customer" data-placeholder="Select Customer"></select>
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="number" class="form-control" id="phone">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" cols="0" rows="2" class="form-control"></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="logo">Upload Logo</label><br>
                        <input type="file" name="logo" id="logo">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="w-100">
                        <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                        <button id="category_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Submit</span></button>
                        <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Cancel</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/purchase/addpurchase.css')}}">
@endpush

@push('js')
<script>
    $(document).ready(function(){
        init();

        $(document).on('click','#add', createModal)
        $(document).on('click','#category_save_btn', submitToDatabase)
    });

    function init(){

        let arr=[
            {
                selector        : `#customer`,
                type            : 'select',
            },
        ];

        globeInit(arr);

        // $(`#stuff`).select2({
        //     width           : '100%',
        //     dropdownParent  : $('#categoryModal'),
        //     theme           : 'bootstrap4',
        // }).val(null).trigger('change')


        // $('#booking_date').datepicker({
        //     autoclose : true,
        //     clearBtn : false,
        //     todayBtn : true,
        //     todayHighlight : true,
        //     orientation : 'bottom',
        //     format : 'yyyy-mm-dd',
        // })
    }


    function createModal(){
        showModal('#categoryModal');
    }

    function submitToDatabase(){
        //

        ajaxFormToken();

        let obj = {
            url     : ``, 
            method  : "POST",
            data    : {},
        };

        ajaxRequest(obj);
    }

</script>
@endpush

