@extends('backend.layouts.master')

@section('title','Manage Rewiew')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Manage Reviwe</a> </h6>
                {{-- <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Review</i></button> --}}
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>User</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Product</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($reviews)
                                @foreach ($reviews as $reviewItem)
                                    <tr data-review="{{ json_encode($reviewItem) }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $reviewItem->commentedBy->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $reviewItem->ratting ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $reviewItem->body ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $reviewItem->product->product_name ?? 'N/A' }}
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" data-id="{{$reviewItem->id}}" {{ $reviewItem->is_approved ? 'checked':''}} class="custom-control-input is-approved" id="{{ $reviewItem->id }}">
                                                <label class="custom-control-label" for="{{ $reviewItem->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.review.destroy', $reviewItem->id )}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="reviewModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel">Update Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Review Information</h5>
                                <hr>
                            </div>
{{-- 
                            <div class="col-md-6" data-col="col">
                                <div class="form-group">
                                    <label for="commented_by"> User</label>
                                    <select name="commented_by " class="commented_by " data-required id="commented_by " data-placeholder="Select User">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                    </select>
                                </div>
                            </div> --}}

                            {{-- <div class="col-md-6" data-col="col">
                                <div class="form-group">
                                    <label for="commentable_id"> Product</label>
                                    <select name="commentable_id " class="commentable_id " data-required id="commentable_id " data-placeholder="Select Product">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="body">Review Body</label>
                                    <textarea name="body" id="body" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12" data-col="col">
                                <div class="form-group">
                                    <label for="ratting">Star</label>
                                    <select name="ratting" class="ratting" data-required id="ratting" data-placeholder="Select Ratting">
                                            <option value="1">1 Star</option>
                                            <option value="2">2 Star</option>
                                            <option value="3">3 Star</option>
                                            <option value="4">4 Star</option>
                                            <option value="5">5 Star</option>
                                    </select>
                                </div>
                            </div>
                         
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status</label><br>
                                    <input type="radio" name="is_active" id="isActive" checked>
                                    <label for="isActive">Confirm</label>
                                    <input type="radio" name="is_active" id="isInActive">
                                    <label for="isInActive">Pending</label>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                        <button id="review_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Update</span></button>
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
        //   $(function() {
        //     $('.toggle-class').change(function() {
        //         var status = $(this).prop('checked') == true ? 1 : 0; 
        //         var user_id = $(this).data('id'); 
                
        //         $.ajax({
        //             type: "GET",
        //             dataType: "json",
        //             url: '/changeStatus',
        //             data: {'status': status, 'user_id': user_id},
        //             success: function(data){
        //             console.log(data.success)
        //             }
        //         });
        //     })
        // })

        $(document).ready(function(){
            init();
            $(document).on('change','.is-approved', changeStatus)
            // $(document).on('click','#review_save_btn', submitToDatabase)
            $(document).on('click' , '.delete', deleteToDatabase)
            $(document).on('click', '.update', showUpdateModal)
            $(document).on('click','#review_update_btn', updateToDatabase)
            $(document).on('click', '#reset', resetForm)
        });


        function init(){

            let arr=[
                {
                    dropdownParent  : '#reviewModal',
                    selector        : `#ratting`,
                    type            : 'select',
                },
                {
                    dropdownParent  : '#reviewModal',
                    selector        : `.commented_by`,
                    type            : 'select',
                },
                {
                    dropdownParent  : '#reviewModal',
                    selector        : `.commentable_id`,
                    type            : 'select',
                },
                {
                    selector        : `#booking_date`,
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

        function resetForm(){
             resetData();
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

        function showUpdateModal(){
            resetData();

            let review = $(this).closest('tr').attr('data-review');

            if(review){
                review = JSON.parse(review);

                $('#reviewModal').attr('data-id', review?.id)

                $('#body').val(review?.body)

                if(review?.is_approved){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                $('#ratting').select2().val(review.ratting).trigger('change');

                showModal('#reviewModal');
            }
        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#reviewModal').attr('data-id');
            let obj = {
                url     : `{{ route('admin.review.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData();

            hideModal('#reviewModal');
        }

        function formatData(){
            return {
                body            : $('#body').val().trim(),
                ratting         : $('#ratting').val(), 
                commentable_id  : $('#commentable_id ').val(), 
                commented_by    : $('#commented_by').val(), 
                is_approved     : $('#isActive').is(':checked') ? 1 : 0,
            }
        }
 
        function resetData(){
                $('#body').val(null),
                $('#ratting').val(null), 
                $('#commentable_id ').val(null),
                $('#commented_by').val(null), 
                $('#isActive').prop('checked', true)
        }

       function changeStatus(){
           let elem     = $(this),
           is_approved  = elem.prop("checked"),
           id           = elem.attr('data-id');

           ajaxFormToken();

           $.ajax({
               url      :`{{ route('admin.review.update', '' ) }}/${id}`, 
               method   :'PUT',
               data     : {is_approved: Number(is_approved)},
               success(res){
                   if(res?.success){
                       elem.prop("checked", is_approved);
                        _toastMsg(res?.msg ?? 'Success!', 'success', 500);
                   }
               },
               error(err){
                   console.log(err);
                   elem.prop("checked", !is_approved);
                   _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!');
               },
           })
       }

    </script>
@endpush
