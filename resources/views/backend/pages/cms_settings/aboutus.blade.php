@extends('backend.layouts.master')

@section('title','About Us')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">About Us Settings</a> </h6>
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> About Us</i></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Thumbnail</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($aboutdatas)
                                @foreach ($aboutdatas as $data)
                                    <tr about-data="{{ json_encode($data) }}">
                                        <td>{{ $loop->iteration ?? 'N/A' }}</td>
                                        <td>{{ $data->about_title ?? 'N/A' }}</td>
                                        <td>{{ $data->about_description ?? 'N/A' }}</td>
                                        <td>
                                            @if(isset($data->about_thumbnail))
                                                <img src="{{ asset($data->about_thumbnail) }}" style="width: 80px;" alt="Category Image">
                                            @else 
                                                <img src="" style="width: 80px;" alt="Category Image">
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $data->is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.about.destroy', $data->id ) }}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="aboutModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Add</span> About Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about_title">Title </label>
                                    <input type="text" name="about_title" class="form-control about_title" id="about_title" placeholder="About Title">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                   <label for="about_description">Description</label>
                                    <textarea class="form-control" name="about_description" id="about_description" cols="0" rows="5" placeholder="About Description"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="">About Thumbnail</label>
                                {!! renderFileInput(['id' => 'about_thumbnail', 'imageSrc' => '']) !!}
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
    
    
                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                        <button id="about_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="about_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
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
            $(document).on('click','#about_save_btn', submitToDatabase)

            $(document).on('click','.delete', deleteToDatabase)
            $(document).on('change' , '#about_thumbnail', checkImage)
            $(document).on('click','#reset', resetForm)

            $(document).on('click', '.update', showUpdateModal)
            $(document).on('click' , '#about_update_btn', updateToDatabase)
        });

         // call the func on change file input 
         function checkImage() {
            fileRead(this, '#img-preview');
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
        }

        function resetForm(){
            resetData();
        }

        function createModal(){
            showModal('#aboutModal');
            $('#about_save_btn').removeClass('d-none');
            $('#about_update_btn').addClass('d-none');
            $('#aboutModal .heading').text('Create')
            resetData();
        }

        function submitToDatabase(){

            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.about.store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
            resetData();
        }
        
        function showUpdateModal(){
            resetData();

            let about = $(this).closest('tr').attr('about-data');

            if(about){

                $('#about_save_btn').addClass('d-none');
                $('#about_update_btn').removeClass('d-none');

                about = JSON.parse(about);

                $('#aboutModal .heading').text('Edit').attr('data-id', about?.id)

                $('#about_title').val(about?.about_title)
                $('#about_description').val(about?.about_description)
                
                if(about?.is_active){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                // show previos image on modal
                $(document).find('#img-preview').attr('src', `{{ asset('') }}${about.about_thumbnail}`);

                showModal('#aboutModal');
            }
        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#aboutModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.about.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData();
        }
        
        function formatData(){
            return {
                about_title       : $('#about_title').val().trim(),
                about_description : $('#about_description').val().trim(),
                about_thumbnail   : fileToUpload('#img-preview'),
                is_active         : $('#isActive').is(':checked') ? 1 : 0,
            };
        }

        function resetData(){
            $('#about_title').val(null)
            $('#about_description').val(null)
            $('#about_thumbnail').val(null)
            fileToUpload('#img-preview', 'put default src')
            $('#isActive').prop('checked', true)
        }

    </script>
@endpush
