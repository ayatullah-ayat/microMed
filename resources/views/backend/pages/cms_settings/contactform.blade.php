@extends('backend.layouts.master')

@section('title','Contact page')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Contact Form List</a> </h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#SL</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($contacts)
                                @foreach ($contacts as $contact)
                                    <tr contact-data="{{ json_encode($contact)}}">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $contact->name ?? 'N/A' }}</td>
                                        <td>{{ $contact->email ?? 'N/A' }}</td>
                                        <td>{{ $contact->phone ?? 'N/A' }}</td>
                                        <td>{{ $contact->subject ?? 'N/A' }}</td>
                                        <td>{{ $contact->message ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.contact.destroy', $contact->id ) }}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="contactModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel">Edit Contact</h5>
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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" id="name"  placeholder="Your Name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Your Email">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Phone</label>
                                    <input type="number" class="form-control" id="phone" placeholder="Your Phone">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Subject</label>
                                    <input type="text" class="form-control" id="subject" placeholder="Subject">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="message">Service Description</label>
                                    <textarea name="message" id="message" cols="0" rows="5" class="form-control" placeholder="Your Message"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button id="contact_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Update</span></button>
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
            $(document).on('click','.delete', deleteToDatabase)

            $(document).on('click', '.update',  showUpdateModal)
            $(document).on('click', '#contact_update_btn', updateToDatabase)

        });

        
        function init(){

            let arr=[
                {
                    dropdownParent  : '#contactModal',
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

        function showUpdateModal(){

            let contact = $(this).closest('tr').attr('contact-data');

            if(contact){

                contact = JSON.parse(contact);
                $('#contactModal').attr('data-id', contact?.id)

                $('#name').val(contact?.name)
                $('#email').val(contact?.email)
                $('#phone').val(contact?.phone)
                $('#subject').val(contact?.subject)
                $('#message').val(contact?.message)

                showModal('#contactModal');

            }

        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#contactModal').attr('data-id');
            let obj = {
                url     : `{{ route('admin.contact.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function formatData(){
            return {
                name    : $('#name').val().trim(),
                email   : $('#email').val(),
                phone   : $('#phone').val(),
                subject : $('#subject').val().trim(),
                message : $('#message').val().trim(),
            };
        }

    </script>
@endpush