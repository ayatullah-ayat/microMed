@extends('backend.layouts.master')

@section('title','Social Icon')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Social Icon Settings</a> </h6>
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Social Icon</i></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Facebook</th>
                                <th>Twitter</th>
                                <th>Instagram</th>
                                <th>Linkedin</th>
                                <th>FB Messenger</th>
                                <th>WhatsApp</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @isset($sociallists)
                            @foreach ($sociallists as $sociallist)
                                <tr sociallist-data="{{ json_encode($sociallist) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a target="_blank" href="{{ $sociallist->facebook ?? '#' }}"> {{ $sociallist->facebook ?? 'N/A' }} </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ $sociallist->twitter ?? '#' }}"> {{ $sociallist->twitter ?? 'N/A' }} </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ $sociallist->instagram ?? '#' }}"> {{ $sociallist->instagram ?? 'N/A' }} </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ $sociallist->linkedin ?? '#' }}"> {{ $sociallist->linkedin ?? 'N/A' }} </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ $sociallist->fb_messenger ?? '#' }}"> {{ $sociallist->fb_messenger ?? 'N/A' }} </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ $sociallist->whatsapp ?? '#' }}"> {{ $sociallist->whatsapp ?? 'N/A' }} </a>
                                    </td>
                                    <th class="text-center">
                                        {!! $sociallist->is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                    </th>
                                    <td class="text-center">
                                        {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                        <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                        <a href="{{ route('admin.socialicon.destroy', $sociallist->id ) }}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="socialLinkModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Create</span> Social Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Social Link Information</h5>
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook">Facebook</label>
                                    <input name="facebook" id="facebook" class="form-control" placeholder="Facebook Link" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter">Twitter</label>
                                    <input name="twitter" id="twitter" class="form-control" placeholder="Twitter Link" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="instagram">Instagram</label>
                                    <input name="instagram" id="instagram" class="form-control" placeholder="Instagram Link" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linkedin">Linkedin</label>
                                    <input name="linkedin" id="linkedin" class="form-control" placeholder="Linkedin Link" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fb_messenger">FB Messenger</label>
                                    <input name="fb_messenger" id="fb_messenger" class="form-control" placeholder="FB Messenger Link" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp">WhatsApp</label>
                                    <input name="whatsapp" id="whatsapp" class="form-control" placeholder="WhatsApp Link" />
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
                        <button id="sociallink_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="sociallink_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
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
            $(document).on('click','#sociallink_save_btn', submitToDatabase)

            $(document).on('click','.delete', deleteToDatabase)
            $(document).on('click' , '#reset', resetForm)

            $(document).on('click','#sociallink_update_btn', updateToDatabase)
            $(document).on('click' , '.update', showUpdateModal)
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
                    dropdownParent  : '#socialLinkModal',
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
            showModal('#socialLinkModal');
            $('#sociallink_save_btn').removeClass('d-none');
            $('#sociallink_update_btn').addClass('d-none');
            $('#socialLinkModal .heading').text('Create');
            resetData()
        }

        function showUpdateModal(){
            resetData();

            let sociallist = $(this).closest('tr').attr('sociallist-data');

            if(sociallist){

                $('#sociallink_save_btn').addClass('d-none');
                $('#sociallink_update_btn').removeClass('d-none');

                sociallist = JSON.parse(sociallist);

                $('#socialLinkModal .heading').text('Edit').attr('data-id', sociallist?.id)

                $('#facebook').val(sociallist?.facebook)
                $('#twitter').val(sociallist?.twitter)
                $('#instagram').val(sociallist?.instagram)
                $('#linkedin').val(sociallist?.linkedin)
                $('#fb_messenger').val(sociallist?.fb_messenger)
                $('#whatsapp').val(sociallist?.whatsapp)
                
                if(sociallist?.is_active){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                showModal('#socialLinkModal');
            }
        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#socialLinkModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.socialicon.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }


        function resetForm(){
            resetData();
        }

        function submitToDatabase(){
            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.socialicon.store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }


        function formatData(){
            return {
                facebook        : $('#facebook').val().trim(),
                twitter         : $('#twitter').val().trim(),
                instagram       : $('#instagram').val().trim(),
                linkedin        : $('#linkedin').val().trim(),
                fb_messenger    : $('#fb_messenger').val().trim(),
                whatsapp        : $('#whatsapp').val().trim(),
                is_active       : $('#isActive').is(':checked') ? 1 : 0,
            }
        }

        function resetData(){
            $('#facebook').val(null),
            $('#twitter').val(null),
            $('#instagram').val(null),
            $('#linkedin').val(null),
            $('#fb_messenger').val(null),
            $('#whatsapp').val(null),
            $('#isActive').prop('checked', true)
        }

    </script>
@endpush
