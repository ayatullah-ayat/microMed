@extends('backend.layouts.master')

@section('title','Manage Ads')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Ads Settings</a> </h6>
                @if(!count($ads))
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Manage Ads</i></button>
                @endif 
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#SL</th>
                                <th>Ads Banner</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($ads)
                            @foreach ($ads as $ad)
                                    <tr data-banner="{{ json_encode($ad)}}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($ad->image)
                                                <img src="{{ asset($ad->image) }}" style="width: 100px;" alt="Ads Image">
                                            @else 
                                                <img src="" style="width: 150px;" alt="Ads Image">
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $ad->is_publish ? '<span class="badge badge-success">Published </span>' : '<span class="badge badge-danger">Un-Published </span>' !!}
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{route('admin.cms_settings.ads_manager.destroy', $ad->id )}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="bannerModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Create</span> Ads</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Ads Info</h5>
                                <hr>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="">Ads Image</label>
                                {!! renderFileInput(['id' => 'bannerImage', 'imageSrc' => '']) !!}
                                <span class="v-msg"></span>
                            </div>

                             <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status</label><br>
                                    <input type="radio" name="is_active" id="isActive" checked>
                                    <label for="isActive">Publish</label>
                                    <input type="radio" name="is_active" id="isInActive">
                                    <label for="isInActive">Un-Publish</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                        <button id="banner_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="banner_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
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
            $(document).on('click','#add', createModal)
            $(document).on('click','#banner_save_btn', submitToDatabase)
            $(document).on('change' , '#bannerImage', checkImage)
            $(document).on('click', '#reset', resetForm)
            $(document).on('click','.delete', deleteToDatabase)

            $(document).on('click', '.update', showUpdateModal)
            $(document).on('click', '#banner_update_btn', updateToDatabase)
        });

         // call the func on change file input 
         function checkImage() {
            fileRead(this, '#img-preview');
        }

        function createModal(){
            showModal('#bannerModal');
            $('#banner_save_btn').removeClass('d-none');
            $('#banner_update_btn').addClass('d-none');
            $('#bannerModal .heading').text('Create')
            resetData();
        }
        
        function showUpdateModal(){
            resetData();
            let shopBanner = $(this).closest('tr').attr('data-banner');

            if(shopBanner){

                $('#banner_save_btn').addClass('d-none');
                $('#banner_update_btn').removeClass('d-none');

                shopBanner = JSON.parse(shopBanner);

                $('#bannerModal .heading').text('Edit').attr('data-id', shopBanner?.id)

                if(shopBanner?.is_publish){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                // show previos image on modal
                $(document).find('#img-preview').attr('src', `{{ asset('') }}${shopBanner.image}`);

                showModal('#bannerModal');
            }


        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#bannerModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.cms_settings.ads_manager.update', '' ) }}/${id}`, 
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
            resetData()
        }

        function submitToDatabase(){

            ajaxFormToken();

                let obj = {
                    url     : `{{ route('admin.cms_settings.ads_manager.store')}}`, 
                    method  : "POST",
                    data    : formatData(),
                };

                ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function formatData(){
            return {
                banner_image: fileToUpload('#img-preview'),
                is_publish  : $('#isActive').is(':checked') ? 1 : 0,
            };
        }

        function resetData(){
            $('#name').val(null)
            fileToUpload('#img-preview', '')
            $('#isActive').prop('checked', true)
        }

    </script>
@endpush
