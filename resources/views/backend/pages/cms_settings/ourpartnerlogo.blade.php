@extends('backend.layouts.master')

@section('title', 'Partnership Logo')

@section('content')

<div class="content-wrapper my-4">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Other Organization') }}</div>

                    <div class="card-body">
                        <h2 class="text-left font-weight-bold">Other Organization's Logo</h2>

                        <form id="frm" method="post" class="needs-validation" novalidate="" action="{{ route('admin.cms_settings.partnership-logo.store') }}">
                            {{-- @csrf --}}

                            <!--Image Upload-->
                            {{-- <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ $gallery->title ?? '' }}">
                                </div>
                            </div> --}}
                            <div class="row mt-3 mb-2">

                                <div class="col-md-12 pr-0 text-left">
                                    <label for="Images"
                                        class="col-form-label text-nowrap"><strong>Logo</strong></label>
                                </div>
                            </div>

                            <ul class="nav nav-tabs nav-pills nav-fill" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="upload-tab" data-toggle="tab" href="#home" role="tab"
                                        aria-controls="home" aria-selected="true">Upload</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="gallery-tab" data-toggle="tab" href="#profile" role="tab"
                                        aria-controls="profile" aria-selected="false">Logo</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                    aria-labelledby="upload-tab">
                                    <!--Image container -->
                                    <div class="row" data-type="imagesloader" data-errorformat="Accepted file formats"
                                        data-errorsize="Maximum size accepted" data-errorduplicate="File already loaded"
                                        data-errormaxfiles="Maximum number of images you can upload"
                                        data-errorminfiles="Minimum number of images to upload"
                                        data-modifyimagetext="Modify immage">

                                        <!-- Progress bar -->
                                        <div class="col-12 order-1 mt-2">
                                            <div data-type="progress" class="progress"
                                                style="height: 25px; display:none;">
                                                <div data-type="progressBar"
                                                    class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                    role="progressbar" style="width: 100%;">Load in progress...</div>
                                            </div>
                                        </div>

                                        <!-- Model -->

                                        <div data-type="image-model" class="col-md-4 pl-2 pr-2 pt-2"
                                            style="max-width:200px; display:none;">

                                            <div class="ratio-box text-center" data-type="image-ratio-box">
                                                <img data-type="noimage"
                                                    class="btn btn-light ratio-img img-fluid p-2 image border dashed rounded"
                                                    src="{{ asset('assets/backend/libs/imagesloader/img/photo-camera-gray.svg') }}"
                                                    style="cursor:pointer;">
                                                <div data-type="loading" class="img-loading"
                                                    style="color:#218838; display:none;">
                                                    <span class="fa fa-2x fa-spin fa-spinner"></span>
                                                </div>
                                                <img data-type="preview"
                                                    class="btn btn-light ratio-img img-fluid p-2 image border dashed rounded"
                                                    src="" style="display: none; cursor: default;">
                                                <span class="badge badge-pill badge-success p-2 w-50 main-tag"
                                                    style="display:none;">Main</span>
                                            </div>

                                            <!-- Buttons -->
                                            <div data-type="image-buttons" class="row justify-content-center mt-2">
                                                <button data-type="add" class="btn btn-outline-success"
                                                    type="button"><span class="fa fa-camera mr-2"></span>Add</button>
                                                <button data-type="btn-modify" type="button"
                                                    class="btn btn-outline-success m-0" data-toggle="popover"
                                                    data-placement="right" style="display:none;">
                                                    <span class="fa fa-pencil-alt mr-2"></span>Modify
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Popover operations -->
                                        <div data-type="popover-model" style="display:none">
                                            <div data-type="popover" class="ml-3 mr-3" style="min-width:150px;">
                                                <div class="row">
                                                    <div class="col p-0">
                                                        <button data-operation="main"
                                                            class="btn btn-block btn-success btn-sm rounded-pill"
                                                            type="button"><span
                                                                class="fa fa-angle-double-up mr-2"></span>Main</button>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-6 p-0 pr-1">
                                                        <button data-operation="left"
                                                            class="btn btn-block btn-outline-success btn-sm rounded-pill"
                                                            type="button"><span
                                                                class="fa fa-angle-left mr-2"></span>Left</button>
                                                    </div>
                                                    <div class="col-6 p-0 pl-1">
                                                        <button data-operation="right"
                                                            class="btn btn-block btn-outline-success btn-sm rounded-pill"
                                                            type="button">Right<span
                                                                class="fa fa-angle-right ml-2"></span></button>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-6 p-0 pr-1">
                                                        <button data-operation="rotateanticlockwise"
                                                            class="btn btn-block btn-outline-success btn-sm rounded-pill"
                                                            type="button"><span
                                                                class="fas fa-undo-alt mr-2"></span>Rotate</button>
                                                    </div>
                                                    <div class="col-6 p-0 pl-1">
                                                        <button data-operation="rotateclockwise"
                                                            class="btn btn-block btn-outline-success btn-sm rounded-pill"
                                                            type="button">Rotate<span
                                                                class="fas fa-redo-alt ml-2"></span></button>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <button data-operation="remove"
                                                        class="btn btn-outline-danger btn-sm btn-block"
                                                        type="button"><span
                                                            class="fa fa-times mr-2"></span>Remove</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <div class="input-group">
                                            <!--Hidden file input for images-->
                                            <input id="files" type="file" name="files[]" data-button="" multiple=""
                                                accept="image/jpeg, image/png, image/gif," style="display:none;">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                                </div>{{-- end tab --}}

                                {{--
                                ===============================================================================================
                                --}}
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="gallery-tab">
                                    <div class="row">

                                        @if(isset($partnerlogos))
                                            @foreach ($partnerlogos as $idx => $item)
                                                <div data-type="image" data-imageid="{{ $item->id ?? null }}" class="col-md-4 col-6 pl-2 pr-2 pt-2 img-container" style="max-width: 200px;" data-id="{{ $item->logo ?? '' }}">

                                                    <div class="ratio-box text-center" data-type="image-ratio-box" data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="{{ explode("/",$item->logo) ? explode("/",$item->logo)[2] : ''}}">

                                                        <img data-type="noimage"
                                                            class="btn btn-light ratio-img img-fluid p-2 image border dashed rounded"
                                                            src="{{ asset('assets/backend/libs/imagesloader/img/photo-camera-gray.svg') }}"
                                                            style="cursor: pointer; display: none;">

                                                        <div data-type="loading" class="img-loading"
                                                            style="color:#218838; display:none;">
                                                            <span class="fa fa-2x fa-spin fa-spinner"></span>
                                                        </div>

                                                        <img data-type="preview" class="btn btn-light ratio-img img-fluid p-2 image border dashed rounded" src="{{ asset($item->logo) }}" style="cursor: default;">

                                                        <span class="badge badge-pill badge-success p-2 w-50 main-tag"
                                                            style="display:none;">Main</span>

                                                        <div class="overlay-btn">
                                                            <button data-imageid="{{ $item->id ?? null }}" data-logoid="{{ $item->id }}"
                                                                data-operation="remove" class="btn btn-danger btn-sm remove_img"
                                                                type="button">
                                                                <span class="fa fa-times"></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div> 
                            {{-- end tab --}}

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('css')
<link type="text/css" rel="stylesheet" href="{{ asset('assets/backend/libs/imagesloader/jquery.imagesloader.css') }}">
<style>
    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: #fff;
        background-color: var(--theme-primary-color, '#DC0029') !important;
    }

    .overlay-btn {
        position: absolute;
        width: auto;
        height: auto;
        top: 10px;
        right: 10px;
        z-index: 1;
        display: none;
    }

    .img-container:hover .overlay-btn {
        display: block !important;
    }
</style>
@endpush

@push('js')
<script type="text/javascript" src="{{ asset('assets/backend/libs/imagesloader/jquery.imagesloader-1.0.1.js') }}">
</script>
<script>
    $(document).ready(function(){
            //Image loader var to use when you need a function from object
            var auctionImages = null;
            
            // Create image loader plugin
            var imagesloader = $('[data-type=imagesloader]').imagesloader({
                maxFiles    : 10,
                maxSize     : 5000 * 1024,
                filesType   : ["image/jpeg", "image/png", "image/gif"],
                minSelect   : 1,
                imagesToLoad: auctionImages
            });


            //Form
            let $frm = $('#frm');
            
            // Form submit
            $frm.submit(function (e) {
                let 
                $form   = $(this),
                url     = $form.attr('action'),
                files   = imagesloader.data('format.imagesloader').AttachmentArray,
                il      = imagesloader.data('format.imagesloader'),
                title   = $('#title').val();

                // console.log(files);
            
                if (il.CheckValidity())
                    console.log('Upload ' + files.length + ' files');
                
                e.preventDefault();
                e.stopPropagation();

                gallerySettings({
                    url, 
                    data:{ data: JSON.stringify({ title, images: files })}
                });
            });

            $(document).on("click", '.remove_img', removeImageFromGallery)

        })

        function removeImageFromGallery(){
            let 
            elem        = $(this),
            image_id    = elem.attr('data-imageid'),
            gallery_id  = elem.attr('data-logoid');

            if(confirm("Are you sure To remove it?")){
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url     : `{{ route('admin.cms_settings.partnership-logo.destroy','') }}/${gallery_id}`,
                    data    : { gallery_id, image_id},
                    method  :'DELETE',
                    dataType: "json",
                    beforeSend(){
                        console.log("Sending...");
                    },
                    success(res){
                        // console.log(res);
                        if(res?.success){
                            _toastMsg(res?.msg,'success');
                            $(document).find(`div[data-imageid=${image_id}]`).remove();
                        }
                    },
                    error(err){
                        console.log("Error:", err);
                        _toastMsg((err.responseJSON?.msg ?? err?.responseJSON?.message) ?? 'Something wents wrong!')
                    }
                })
            }

        }

        function gallerySettings(obj){

            // console.log("obj", obj);

            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                ...obj,
                method  :'POST',
                dataType: "json",
                cache   : false, 
                async   : false,
                beforeSend(){
                    console.log("Sending...");
                },
                success(res){
                    console.log(res);
                    if(res?.success){
                       _toastMsg(res?.msg,'success');
                       setTimeout(() => {
                           window.location.reload()
                       }, 2000);
                    }else{
                        _toastMsg(res?.msg ); 
                    }
                },
                error(err){
                    console.log("Error:", err);
                    _toastMsg((err.responseJSON?.msg ?? err?.responseJSON?.message) ?? 'Something wents wrong!')
                }
            })

            // console.log(data, url);
        }

</script>
@endpush