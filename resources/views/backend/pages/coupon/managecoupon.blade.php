@extends('backend.layouts.master')

@section('title','Coupon Manage')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none"> Manage Coupon</a> </h6>
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Coupon</i></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Discount Type</th>
                                <th>Coupon Name</th>
                                <th>Coupon Code</th>
                                <th>Coupon Discount</th>
                                <th>Coupon Validity</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($coupons)
                                @foreach ($coupons as $coupon)
                                    <tr coupon-data="{{ json_encode($coupon) }}">
                                        <td> {{ $loop->iteration }}</td>
                                        <td> {{ ucfirst($coupon->discount_type ?? 'N/A') }}</td>
                                        <td> {{ $coupon->coupon_name ?? 'N/A' }}</td>
                                        <td> {{ $coupon->coupon_code ?? 'N/A' }}</td>
                                        <td> <span>{{ $coupon->coupon_discount ?? 'N/A' }}</span> {!!  preg_match("/parcent|parcentage/im", $coupon->discount_type) ? '<span>%</span>' : '' !!} </td>
                                        <td> {{ $coupon->coupon_validity }}</td>
                                        <td class="text-center">
                                            {!! $coupon->status ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="fa fa-eye text-info text-decoration-none detail"></a>
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.coupon.destroy', $coupon->id ) }}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="couponModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Create</span> Coupon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Coupon Information</h5>
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="coupon_name">Coupon Name</label>
                                    <input type="text" name="coupon_name" id="coupon_name" class="form-control" placeholder="Coupon Name" />
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="coupon_code">Coupon Code</label>
                                    <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Coupon Code" />
                                </div>
                            </div>

                            <div class="col-md-6" data-col="col">
                                <div class="form-group">
                                    <label for="discount_type">Discount Type</label>
                                    <select name="discount_type" class="discount_type" data-required id="discount_type" data-placeholder="Select Discount Type">
                                        <option value="fixed">Fixed</option>
                                        <option value="parcent">Parcentage</option>
                                    </select>
                                </div>
                                <span class="v-msg"></span>
                            </div>

                            <div class="col-md-6" data-col="col">
                                <div class="form-group">
                                    <label for="coupon_type">Coupon Type</label>
                                    <select name="coupon_type" class="coupon_type" data-required id="coupon_type" data-placeholder="Select Coupon Type">
                                        <option value="include">Include Product</option>
                                        <option value="exclude">Exclude Product</option>
                                        <option value="category">Product Category</option>
                                    </select>
                                </div>
                                <span class="v-msg"></span>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="coupon_discount">Coupon Discount</label>
                                    <input type="number" name="coupon_discount" id="coupon_discount" class="form-control" placeholder="Coupon Discount" />
                                </div>
                            </div>

                            <div class="col-md-6" data-col="col">
                                <div class="form-group">
                                    <label for="coupon_validity">Coupon Validity</label>
                                    <input type="text" data-required="" autocomplete="off" class="form-control" id="coupon_validity" name="coupon_validity" placeholder="Validity Date">
                                </div>
                                <span class="v-msg"></span>
                            </div>

                            <div class="col-md-6" data-col="col">
                                <div class="form-group">
                                    <label for="usage_limit">Usage Limit</label>
                                    <input type="text" class="form-control" id="usage_limit" name="usage_limit" placeholder="User Usage Limit">
                                </div>
                            </div>

                            <div class="col-md-6" data-col="col">
                                <div class="form-group">
                                    <label for="min_bill_limit">Min Price Limit</label>
                                    <input type="text" class="form-control" id="min_bill_limit" name="min_bill_limit" placeholder="Min Price Limit">
                                </div>
                            </div>

                            <div class="col-md-6" data-col="col">
                                <div class="form-group">
                                    <label for="max_bill_limit">Max Price Limit</label>
                                    <input type="text" class="form-control" id="max_bill_limit" name="max_bill_limit" placeholder="Max Price Limit">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Is Active</label><br>
                                    <input type="radio" name="is_active" id="isActive" checked>
                                    <label for="isActive">Active</label>
                                    <input type="radio" name="is_active" id="isInActive">
                                    <label for="isInActive">Inactive</label>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                        <button id="coupon_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="coupon_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
                        <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
                    </div>
                </div>
    
            </div>
        </div>
    </div>

    <div class="modal fade" id="CouponDetailModal"  tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Category Details</h5>
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
            $(document).on('click','#coupon_save_btn', submitToDatabase)
            $(document).on('click','#reset', resetForm)
            $(document).on('click','.delete', deleteToDatabase)
            $(document).on('click', '.update', showUpdateModal)
            $(document).on('click','#coupon_update_btn', updateToDatabase)
            $(document).on('click','.detail', showDataToModal)
        });

        function init(){
            let arr=[
                {
                    dropdownParent  : '#couponModal',
                    selector        : `#discount_type`,
                    type            : 'select',
                },
                {
                    dropdownParent  : '#couponModal',
                    selector        : `#coupon_type`,
                    type            : 'select',
                },
                {
                    selector        : `#coupon_validity`,
                    type            : 'date',
                    format          : 'yyyy-mm-dd',
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

        function showDataToModal(){
            let 
            elem        = $(this),
            tr          = elem.closest('tr'),
            coupon    = tr?.attr('coupon-data') ? JSON.parse(tr.attr('coupon-data')) : null,
            modalDetailElem = $('#modalDetail');

            if(category){
                let html = `
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th> Discount Type </th>
                        <td>${coupon.discount_type ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Coupon Type </th>
                        <td>${coupon.coupon_type ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Coupon Name </th>
                        <td>${coupon.coupon_name ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Coupon Code </th>
                        <td>${coupon.coupon_code ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Coupon Discount </th>
                        <td>${coupon.coupon_discount ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Coupon validity </th>
                        <td>${coupon.coupon_validity ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Usage Limit </th>
                        <td>${coupon.usage_limit ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Min Bill Limit </th>
                        <td>${coupon.min_bill_limit ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Max Bill Limit </th>
                        <td>${coupon.max_bill_limit ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>${coupon.status ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>'}</td>
                    </tr>
                </table>
                `;

                modalDetailElem.html(html);
            }

            $('#CouponDetailModal').modal('show')
        }

        function showUpdateModal(){
            resetData();

            let coupon = $(this).closest('tr').attr('coupon-data');

            if(coupon){

                $('#coupon_save_btn').addClass('d-none');
                $('#coupon_update_btn').removeClass('d-none');

                coupon = JSON.parse(coupon);

                $('#couponModal .heading').text('Edit').attr('data-id', coupon?.id)

                $('#discount_type').val(coupon.discount_type).trigger('change');
                $('#coupon_type').val(coupon.coupon_type).trigger('change');
                // $('#discount_type').select2().val(coupon.discount_type).trigger('change');
                // $('#coupon_type').select2().val(coupon.coupon_type).trigger('change');
                $('#coupon_name').val(coupon?.coupon_name)
                $('#coupon_code').val(coupon?.coupon_code)
                $('#coupon_discount').val(coupon?.coupon_discount)
                $('#coupon_validity').val(coupon?.coupon_validity)
                $('#usage_limit').val(coupon?.usage_limit)
                $('#min_bill_limit').val(coupon?.min_bill_limit)
                $('#max_bill_limit').val(coupon?.max_bill_limit)

                if(coupon?.status){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                showModal('#couponModal');
            }
        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#couponModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.coupon.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
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
                            resetData();

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

        function resetForm(){
            resetData();
        }

        function createModal(){
            showModal('#couponModal');
            $('#coupon_save_btn').removeClass('d-none');
            $('#coupon_update_btn').addClass('d-none');
            $('#couponModal .heading').text('Create');
            resetData();
        }

        function submitToDatabase(){
            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.coupon.store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function formatData(){
            return {
                coupon_type         : $('#coupon_type').val(),
                discount_type       : $('#discount_type').val(),
                coupon_name         : $('#coupon_name').val().trim(),
                coupon_code         : $('#coupon_code').val().trim(),
                coupon_discount     : $('#coupon_discount').val().trim(),
                coupon_validity     : $('#coupon_validity').val(),
                usage_limit         : $('#usage_limit').val(),
                min_bill_limit      : $('#min_bill_limit').val(),
                max_bill_limit      : $('#max_bill_limit').val(),
                status              : $('#isActive').is(':checked') ? 1 : 0,
            };
        }

        function resetData(){
            $('#coupon_type').val(null),
            $('#discount_type').val(null),
            $('#coupon_name').val(null),
            $('#coupon_code').val(null),
            $('#coupon_discount').val(null),
            $('#coupon_validity').val(null),
            $('#usage_limit').val(null),
            $('#min_bill_limit').val(null),
            $('#max_bill_limit').val(null),
            $('#isActive').prop('checked', true)
        }

    </script>
@endpush
