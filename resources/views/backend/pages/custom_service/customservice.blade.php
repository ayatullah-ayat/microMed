@extends('backend.layouts.master')

@section('title','Custom Service')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Custom Service</a> </h6>
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Service</i></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Service Title</th>
                                <th>Service Description</th>
                                <th>Service Thumbnail</th>
                                <th>Is Content Visible</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($services)
                                @foreach ($services as $service)
                                    <tr service-data="{{ json_encode($service) }}">
                                        <td>{{ $loop->iteration ?? 'N/A' }}</td>
                                        <td>{{ $service->service_name ?? 'N/A' }}</td>
                                        <td>{{ $service->service_description ?? 'N/A' }}</td>
                                        <td>
                                            @if($service->service_thumbnail)
                                                <img src="{{ asset($service->service_thumbnail) }}" style="width: 80px;" alt="service Image">
                                            @else 
                                                <img src="" style="width: 80px;" alt="service Image">
                                            @endif
                                        </td>
                                        <td>
                                            {!! $service->is_allow_caption ? '<span class="badge badge-success">Yes </span>' : '<span class="badge badge-danger">No </span>' !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $service->is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                            <a href="javascipt:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.customservice.destroy', $service->id )}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="serviceModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"><span class="heading">Create</span> Custom Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Service Information</h5>
                                <hr>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Service Title <span style="color: red;" class="req">*</span></label>
                                    <input type="text" class="form-control" id="service_title">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="service_description">Service Description</label>
                                    <textarea name="service_description" id="service_description" cols="0" rows="5" class="form-control"></textarea>
                                </div>
                            </div>

                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label for="service_thumbnail">Service Thumbnail</label> <br>
                                     <input type="file" name="" id="service_thumbnail" name="service_thumbnail">
                                </div>
                            </div> --}}

                            <div class="col-md-12 mb-2">
                                <label for="">Service Image ( size: 273 x 232 )</label>
                                {!! renderFileInput(['id' => 'service_thumbnail', 'imageSrc' => '']) !!}
                                <span class="v-msg"></span>
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

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="checkbox" checked name="allow_caption" id="allow_caption">
                                    <label for="allow_caption"> Is Content Visible </label>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                        <button id="service_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="service_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
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
            $(document).on('click','#service_save_btn', submitToDatabase)

            $(document).on('click', '#reset', resetForm)

            $(document).on('change' , '#service_thumbnail', checkImage)
            $(document).on('click','.delete', deleteToDatabase)

            $(document).on('click', '.update',  showUpdateModal)
            $(document).on('click', '#service_update_btn', updateToDatabase)

        });

        // call the func on change file input 
        function checkImage() {
            fileRead(this, '#img-preview');
        }      
        
        function init(){

            let arr=[
                {
                    dropdownParent  : '#serviceModal',
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
            showModal('#serviceModal');
            $('#service_save_btn').removeClass('d-none');
            $('#service_update_btn').addClass('d-none');
            $('#serviceModal .heading').text('Create')
            resetData();
        }

        function resetForm(){
            resetData();
        }


        function submitToDatabase(){
            //
            
            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.customservice.store') }}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData()
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

            let service = $(this).closest('tr').attr('service-data');

            if(service){

            $('#service_save_btn').addClass('d-none');
            $('#service_update_btn').removeClass('d-none');

            service = JSON.parse(service);

            $('#serviceModal .heading').text('Edit').attr('data-id', service?.id)

            $('#service_title').val(service?.service_name)
            $('#service_description').val(service?.service_description)


            if(service?.is_active){
            $('#isActive').prop('checked',true)
            }else{
            $('#isInActive').prop('checked',true)
            }

            if(service?.is_allow_caption){
            $('#allow_caption').prop('checked',true)
            }else{
            $('#allow_caption').prop('checked',false)
            }

            // show previos image on modal
            $(document).find('#img-preview').attr('src', `{{ asset('') }}${service.service_thumbnail}`);

            showModal('#serviceModal');
            }


        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#serviceModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.customservice.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function formatData(){
            return {
                service_name        : $('#service_title').val().trim(),
                service_description : $('#service_description').val().trim(),
                service_thumbnail   : fileToUpload('#img-preview'),
                is_active           : $('#isActive').is(':checked') ? 1 : 0,
                is_allow_caption    : $('#allow_caption').is(':checked') ? 1 : 0,
            };
        }

        function resetData(){
            $('#service_title').val(null),
            $('#service_description').val(null),
            $('#service_thumbnail').val(null),
            fileToUpload('#img-preview', 'put default src'),
            $('#isActive').prop('checked', true)
        }

    </script>
@endpush