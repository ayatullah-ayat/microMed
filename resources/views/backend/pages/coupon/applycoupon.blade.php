@extends('backend.layouts.master')

@section('title','Apply Coupon')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Apply Coupon Settings</a> </h6>
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Apply Coupon</i></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Coupon Name</th>
                                <th>Coupon Code</th>
                                <th>Coupon Type</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($applycoupons)
                                @foreach ($applycoupons as $applycoupon)
                                {{-- @dd($applycoupon) --}}
                                    <tr coupon-data="{{ json_encode($applycoupon) }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $applycoupon->coupon->coupon_name ?? 'N/A' }}</td>
                                        <td>{{ $applycoupon->coupon_code ?? 'N/A'}}</td>
                                        <td>{{ ucfirst($applycoupon->coupon->coupon_type) ?? 'N/A'}}</td>
                                        <td class="text-center">
                                            <a data-couponid="{{$applycoupon->coupon->id}}" data-coupontype="{{$applycoupon->coupon->coupon_type}}" href="javascript:void(0)" class="fa fa-eye text-info text-decoration-none detail"></a>
                                            <a data-couponid="{{$applycoupon->coupon->id}}" href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.applycoupon.destroy', $applycoupon->coupon_id)}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    
    </div>

    <div class="modal fade" id="applyCouponModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel">Apply Coupon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Apply Coupon Information</h5>
                                <hr>
                            </div>
                            
                            <div class="col-md-12" data-col="col">
                                <div class="form-group">
                                    <label for="coupon_id">Select Coupon</label>
                                    <select name="coupon_id" data-selected-coupontype="" class="coupon_id" data-required id="coupon_id" data-placeholder="Select Coupon">
                                        @isset($coupons)
                                            @foreach ($coupons as $coupon)
                                                @if (!count($coupon->applycoupons))
                                                <option value="{{ $coupon->id }}" data-coupontype="{{$coupon->coupon_type}}">{{ $coupon->coupon_name }}</option>
                                                @endif
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <span class="v-msg"></span>
                            </div>

                            <div class="col-md-12 d-none" data-col="col">
                                <div class="form-group">
                                    <label for="category_id">Select Categories</label>
                                    <select name="category_id" multiple class="category_id" data-required id="category_id" data-placeholder="Select Category"></select>
                                </div>
                                <span class="v-msg"></span>
                            </div>

                            <div class="col-md-12 d-none" data-col="col">
                                <div class="form-group">
                                    <label for="product_id">Select Products</label>
                                    <select name="product_id" multiple class="product_id" data-required id="product_id" data-placeholder="Select Product"></select>
                                </div>
                                <span class="v-msg"></span>
                            </div>

                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                        <button id="apply_coupon_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
                    </div>
                </div>
    
            </div>
        </div>
    </div>

    <div class="modal fade" id="CouponUpdateModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel">Update Apply Coupon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">

                            <div class="col-md-12" data-col="col">
                                <div class="form-group">
                                    <label for="coupon_id_update">Select Coupon</label>
                                    <select name="coupon_id_update" data-selected-coupontype="" class="coupon_id_update" data-required id="coupon_id_update" data-placeholder="Select Coupon">
                                        @isset($coupons)
                                            @foreach ($coupons as $coupon)
                                                @if (count($coupon->applycoupons))
                                                <option value="{{ $coupon->id }}" data-coupontype="{{$coupon->coupon_type}}">{{ $coupon->coupon_name }}</option>
                                                @endif
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <span class="v-msg"></span>
                            </div>

                            <div class="col-md-12 d-none" data-col="col">
                                <div class="form-group">
                                    <label for="category_id_update">Select Categories</label>
                                    <select name="category_id_update" multiple class="category_id_update" data-required id="category_id_update" data-placeholder="Select Category"></select>
                                </div>
                                <span class="v-msg"></span>
                            </div>

                            <div class="col-md-12 d-none" data-col="col">
                                <div class="form-group">
                                    <label for="product_id">Select Products</label>
                                    <select name="product_id_update" multiple class="product_id_update" data-required id="product_id_update" data-placeholder="Select Product"></select>
                                </div>
                                <span class="v-msg"></span>
                            </div>

                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button id="coupon_update_btn" type="button" class="coupon_update_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Update</span></button>
                        <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
                    </div>
                </div>
    
            </div>
        </div>
    </div>

    <div class="modal fade" id="applyCouponDatailModal"  tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Coupon Applied Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body" id="modalDetail"></div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
                    </div>
                </div>
    
            </div>
        </div>
    </div>
    
@endsection

@push('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/css/currency/currency.css')}}" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0f3aa4 !important;
            border: 1px solid #0f3aa4 !important;
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 5px;
            margin-top: 5px;
            padding: 0 5px;
            color: #fff !important;
        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            float: left;
            padding: 0;
            padding-right: 0px;
            padding-right: .75rem;
            margin-top: calc(.375rem - 2px);
            margin-right: .375rem;
            color: #fff !important;
            cursor: pointer;
            border-radius: .2rem;
            background-color: #0f3aa4 !important;
            border: 1px solid #0f3aa4 !important;
        }

    </style>
@endpush

@push('js')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/backend/libs/demo/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function(){
            init();

            $(document).on('click','#add', createModal)
            $(document).on('click','#apply_coupon_btn', submitToDatabase)
            $(document).on('change','.coupon_id', visibleRelatedSelect2)
            $(document).on('click', '.delete', deleteToDatabase)
            $(document).on('click','.detail', showDataToModal)

            $(document).on('click', '.update', showUpdateModal)
            $(document).on('click','#coupon_update_btn', updateToDatabase)
        });

        function init(){

            let arr=[
                {
                    dropdownParent  : '#applyCouponModal',
                    selector        : `#coupon_id`,
                    type            : 'select',
                },
                {
                    dropdownParent  : '#applyCouponModal',
                    selector        : `#user_id`,
                    type            : 'select',
                },
                {
                    dropdownParent  : '#CouponUpdateModal',
                    selector        : `#coupon_id_update`,
                    type            : 'select',
                },
            ];

            globeInit(arr);

            const URL = "{{ route('admin.applycoupon.searchProduct')}}";
            const URL2= "{{ route('admin.applycoupon.searchProductCategory')}}";

            $("#product_id").select2({
                theme : 'bootstrap4',
                minimumInputLength: 2,
                // tags: [],
                ajax: {
                    url         : URL,
                    dataType    : 'json',
                    type        : "GET",
                    quietMillis : 50,
                    data        : function (term) {
                        return {
                            term: term
                        };
                    },
                    processResults     : function (data) {
                        console.log(data);
                        return {
                            results: $.map(data, function (item) {

                                return {
                                    text: item.product_name ?? 'N/A',
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });

            $("#category_id").select2({
                theme : 'bootstrap4',
                minimumInputLength: 2,
                // tags: [],
                ajax: {
                    url         : URL2,
                    dataType    : 'json',
                    type        : "GET",
                    quietMillis : 50,
                    data        : function (term) {
                        return {
                            term: term
                        };
                    },
                    processResults     : function (data) {
                        console.log(data);
                        return {
                            results: $.map(data, function (item) {

                                return {
                                    text: item.category_name ?? 'N/A',
                                    id: item.category_id
                                }
                            })
                        };
                    }
                }
            });

            $("#category_id_update").select2({
                theme : 'bootstrap4',
                minimumInputLength: 2,
                // tags: [],
                ajax: {
                    url         : URL2,
                    dataType    : 'json',
                    type        : "GET",
                    quietMillis : 50,
                    data        : function (term) {
                        return {
                            term: term
                        };
                    },
                    processResults     : function (data) {
                        console.log(data);
                        return {
                            results: $.map(data, function (item) {

                                return {
                                    text: item.category_name ?? 'N/A',
                                    id: item.category_id
                                }
                            })
                        };
                    }
                }
            });

            $("#product_id_update").select2({
                theme : 'bootstrap4',
                minimumInputLength: 2,
                ajax: {
                    url         : URL2,
                    dataType    : 'json',
                    type        : "GET",
                    quietMillis : 50,
                    data        : function (term) {
                        return {
                            term: term
                        };
                    },
                    processResults     : function (data) {
                        console.log(data);
                        return {
                            results: $.map(data, function (item) {

                                return {
                                    text: item.category_name ?? 'N/A',
                                    id: item.category_id
                                }
                            })
                        };
                    }
                }
            });
        }

        function showUpdateModal(){
            let couponid = $(this).attr('data-couponid');

            if(couponid){
                $('#coupon_id_update').val(couponid).trigger('change')

                visibleRelatedSelect2Update();

                showModal('#CouponUpdateModal');
            }
        }


        function visibleRelatedSelect2Update(){
            let 
            elem                = $('#coupon_id_update'),
            selectedCouponType  = elem.find(':selected').attr('data-coupontype'),
            coupon_id           = elem.val();

            elem.attr('data-selected-coupontype', selectedCouponType);

            getOldRecords(coupon_id, selectedCouponType);

        }


        function getOldRecords(coupon_id, coupon_type){

            let 
            patternCat      = /category/im,
            patternProduct  = /include|exclude/im;
            //
            $.ajax({
                url     : `{{ route('admin.applycoupon.getCouponData') }}`,
                method  : 'get',
                data    : {coupon_id, coupon_type},
                success(data){

                    if(patternCat.test(coupon_type)){

                        $('.category_id_update').parent().parent().removeClass('d-none');
                        $('.product_id_update').parent().parent().addClass('d-none');

                        let options = "";
                        data.forEach( d => {
                            options += `<option value="${d.category_id}" selected>${d.category_name}</option>`;
                        })
                        
                        $('.category_id_update').html(options);


                    }else if(patternProduct.test(coupon_type)){

                        $('.category_id_update').parent().parent().addClass('d-none');
                        $('.product_id_update').parent().parent().removeClass('d-none');

                        let options = "";
                        data.forEach( d => {
                            options += `<option value="${d.product_id}" selected>${d.product_name}</option>`;
                        })

                        $('.product_id_update').html(options);

                    }

                },
                error(err){
                    console.log(err);
                },
            });
        }

        // category_id_update

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#coupon_id_update').val();
            let obj = {
                url     : `{{ route('admin.applycoupon.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : {
                    coupon_id  : id,
                    category_id: $('#category_id_update').val(),
                    product_id : $('#product_id_update').val(),
                },
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function showDataToModal(){
            let 
            elem            = $(this),
            tr              = elem.closest('tr'),
            modalDetailElem = $('#modalDetail'),
            coupon_id       = elem.attr('data-couponid'),
            coupon_type     = elem.attr('data-coupontype'),
            patternCat      = /category/im;
            patternProduct  = /include|exclude/im;

            $('#applyCouponDatailModal').modal('show')


            $.ajax({
                url     : `{{ route('admin.applycoupon.getCouponData') }}`,
                method  : 'get',
                data    : {coupon_id, coupon_type},
                success(data){

                    let html = "";

                    if(patternCat.test(coupon_type)){

                        html += `<table class="table table-sm table-bordered table-striped"><tr class="bg-danger text-white font-font-weight-bold">
                            <th>#SL</th>
                            <td>Category Name</td>
                        </tr>`;

                        data.forEach((d, i) =>{

                            html += `
                                <tr>
                                    <th>${++i}</th>
                                    <td>${d.category_name ?? 'N/A'}</td>
                                </tr>
                            `;
                        })

                    }else if(patternProduct.test(coupon_type)){

                        html += `<table class="table table-sm table-bordered table-striped"><tr class="bg-danger text-white font-font-weight-bold">
                            <th>#SL</th>
                            <td>Product Name</td>
                        </tr>`;

                        data.forEach((d, i) =>{

                            html += `
                                <tr>
                                    <th>${++i}</th>
                                    <td>${d.product_name ?? 'N/A'}</td>
                                </tr>
                            `;
                        })

                    }
                    
                    modalDetailElem.html(html);

                },
                error(err){
                    console.log(err);
                },
            });


        }

        function deleteToDatabase(e){
            e.preventDefault();

            let elem = $(this),
            href = elem.attr('href');
            if(confirm("Are you sure to delete the record?")){
                ajaxFormToken();

                $.ajax({
                    url     : href, 
                    method  : "DELETE",
                    data    : {},
                    success(res){

                        // console.log(res?.data);
                        if(res?.success){
                            _toastMsg(res?.msg ?? 'Success!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error(err){
                        console.log(err);
                        _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
                    },
                });
            }
        }

        function visibleRelatedSelect2(){
            let 
            elem                = $(this),
            selectedCouponType  = elem.find(':selected').attr('data-coupontype'),
            patternCat          = /category/im;
            patternProduct      = /include|exclude/im;

            elem.attr('data-selected-coupontype', selectedCouponType);

            if(patternCat.test(selectedCouponType)){

                $('.category_id').parent().parent().removeClass('d-none');
                $('.product_id').parent().parent().addClass('d-none');
                $('.category_id').val(null).trigger('change')

            }else if(patternProduct.test(selectedCouponType)){

                $('.category_id').parent().parent().addClass('d-none');
                $('.product_id').parent().parent().removeClass('d-none');
                $('.product_id').val(null).trigger('change')
            }

        }

        function createModal(){
            showModal('#applyCouponModal');
        }

        function submitToDatabase(){
            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.applycoupon.store')}}`, 
                method  : "POST",
                data    : {
                    coupon_id  : $('#coupon_id').val(),
                    category_id: $('#category_id').val(),
                    product_id : $('#product_id').val(),
                },
            };

            ajaxRequest(obj,{ reload: true, timer: 2000 });
        }

    </script>
@endpush
