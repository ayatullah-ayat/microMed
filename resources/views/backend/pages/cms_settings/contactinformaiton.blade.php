@extends('backend.layouts.master')

@section('title','Contact Information')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Contact Information</a> </h6>
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Information</i></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($infoDatas)
                                @foreach ($infoDatas as $infoData)
                                    <tr information-data="{{ json_encode($infoData) }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $infoData->title ?? 'N/A' }}</td>
                                        <td>{{ $infoData->description ?? 'N/A' }} </td>
                                        <td>{{ $infoData->address ?? 'N/A' }}</td>
                                        <td>{{ $infoData->email ?? 'N/A' }}</td>
                                        <td>{{ $infoData->phone ?? 'N/A' }}</td>
                                        <th class="text-center">
                                            {!! $infoData->is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                        </th>
                                        <td class="text-center">
                                            {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.contactinfo.destroy', $infoData->id ) }}" class="fa fa-trash text-danger text-decoration-none dalete"></a>
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

    <div class="modal fade" id="ContactInfoModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Create</span> Contact Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                           
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input name="title" id="title" class="form-control" placeholder="Title" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" cols="0" rows="3" class="form-control" placeholder="Description"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contact_address">Address</label>
                                    <textarea name="contact_address" id="contact_address" cols="0" rows="3" class="form-control" placeholder="Contact Address"></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_email">Email</label>
                                    <input name="contact_email" id="contact_email" class="form-control" placeholder="Contact Email" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Phone</label>
                                    <input name="contact_phone" id="contact_phone" class="form-control" placeholder="Contact Phone" />
                                </div>
                            </div>

                            <div class="col-md-12">
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
                        <button id="contactinfo_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="contactinfo_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
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
            $(document).on('click','#contactinfo_save_btn', submitToDatabase)

            $(document).on('click','.update', showUpdateModal)
            $(document).on('click','#contactinfo_update_btn', updateToDatabase)

            $(document).on('click' , '.dalete', deleteToDatabase)
            $(document).on('click', '#reset', resetForm)


        });

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

        function init(){

            let arr=[
                {
                    dropdownParent  : '#categoryModal',
                    selector        : `#email_template`,
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

        function createModal(){
            showModal('#ContactInfoModal');

            $('#contactinfo_save_btn').removeClass('d-none');
            $('#contactinfo_update_btn').addClass('d-none');
            $('#ContactInfoModal .heading').text('Create');
            resetData()
        }

        function showUpdateModal(){
            resetData();

            let contactInfo = $(this).closest('tr').attr('information-data');

            if(contactInfo){

                $('#contactinfo_save_btn').addClass('d-none');
                $('#contactinfo_update_btn').removeClass('d-none');

                contactInfo = JSON.parse(contactInfo);

                $('#ContactInfoModal .heading').text('Edit').attr('data-id', contactInfo?.id)

                $('#title').val(contactInfo?.title)
                $('#description').val(contactInfo?.description)
                $('#contact_address').val(contactInfo?.address)
                $('#contact_email').val(contactInfo?.email)
                $('#contact_phone').val(contactInfo?.phone)
                
                if(contactInfo?.is_active){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                showModal('#ContactInfoModal');
            }
        }


        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#ContactInfoModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.contactinfo.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function resetForm(){
            resetData()
        }

        function submitToDatabase(){
            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.contactinfo.store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData()
        }

        function formatData(){
            return {
                title       : $('#title').val().trim(),
                description : $('#description').val().trim(),
                address     : $('#contact_address').val().trim(),
                email       : $('#contact_email').val().trim(),
                phone       : $('#contact_phone').val(),
                is_active   : $('#isActive').is(':checked') ? 1 : 0,
            }
        }

        function resetData(){
            $('#title').val(null),
            $('#description').val(null),
            $('#contact_address').val(null),
            $('#contact_email').val(null),
            $('#contact_phone').val(null),
            $('#isActive').is(':checked') ? 1 : 0
        }

    </script>
@endpush
