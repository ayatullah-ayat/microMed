@extends('backend.layouts.master')

@section('title', 'Profile')

@section('content')

<div class="content-wrapper my-4">
    <section class="content">
        <div class="card">
            <div class="card-header bg-light-white">
                <h3 class="card-title">Your Profile</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">

                            {{-- @dd(hasProfile('admin')->mobile_no) --}}

                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        @auth
                                        <img draggable="false" class="profile-img profile-user-img img-fluid img-circle"
                                            src="{{ asset(hasProfile('admin')->photo ?? 'assets/common_assets/img/users/user1-128x128.jpg' ) }}" alt="User profile picture">
                                        @else
                                        <img  draggable="false" class="profile-user-img img-fluid img-circle"
                                            src="{{ asset('assets/common_assets/img/users/user1-128x128.jpg') }}"
                                            alt="User profile picture">
                                        @endauth
                                    </div>

                                    <h3 class="profile-username text-center" id="userName">{{ auth()->guard('admin')->user()->name ??
                                        'Jhone Due' }}</h3>

                                    <p class="text-muted text-center">{{ auth()->guard('admin')->user()->designation ?? '' }}</p>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b><i class="fa fal fa-envelope"></i></b> <span class="float-right"
                                                id="email-text">{{ auth()->guard('admin')->user()->email ?? '' }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b><i class="fa fal fa-phone"></i></b> <span class="float-right"
                                                id="phone-text">{{ hasProfile('admin')->mobile_no ?? '' }}</span>
                                        </li>
                                        {{-- <li class="list-group-item">
                                            <b><i class="fa fal fa-users"></i> Friends</b> <a
                                                class="float-right">13,287</a>
                                        </li> --}}
                                    </ul>

                                    <a href="#" class="btn btn-secondary btn-block"><b>Happy Feelings 🙂 </b></a>
                                </div>

                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- About Me Box -->
                            <div class="card card-primary d-none">
                                <div class="card-header">
                                    <h3 class="card-title">About Me</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <strong><i class="fas fa-book mr-1"></i> Education</strong>

                                    <p class="text-muted">
                                        B.S. in Computer Science from the University of Tennessee at Knoxville
                                    </p>

                                    <hr>

                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                                    <p class="text-muted">Malibu, California</p>

                                    <hr>

                                    <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                                    <p class="text-muted">
                                        <span class="tag tag-danger">UI Design</span>
                                        <span class="tag tag-success">Coding</span>
                                        <span class="tag tag-info">Javascript</span>
                                        <span class="tag tag-warning">PHP</span>
                                        <span class="tag tag-primary">Node.js</span>
                                    </p>

                                    <hr>

                                    <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                                        fermentum enim
                                        neque.</p>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#settings"
                                                data-toggle="tab">Profile Settings</a></li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="tab-pane active" id="settings">
                                            <form class="form-horizontal" id="profile-form">
                                                <input type="hidden" name="user_id" id="user_id"
                                                    value="{{ auth()->guard('admin')->user()->id }}">
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Name <span
                                                            class="is_required">*</span></label>
                                                    <div class="col-sm-10" data-col>
                                                        <input type="text" required name="name"
                                                            value="{{ auth()->guard('admin')->user()->name ?? '' }}"
                                                            class="form-control" id="name" placeholder="Name">
                                                        <span class="v-msg"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="email" class="col-sm-2 col-form-label">Email <span
                                                            class="is_required">*</span></label>
                                                    <div class="col-sm-10" data-col>
                                                        <input type="email" required name="email"
                                                            value="{{ auth()->guard('admin')->user()->email ?? '' }}"
                                                            class="form-control" id="email" placeholder="Email">
                                                        <span class="v-msg"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="phone" class="col-sm-2 col-form-label">Phone <span
                                                            class="is_required">*</span></label>
                                                    <div class="col-sm-10" data-col>
                                                        <input type="text" required name="phone"
                                                            value="{{ hasProfile('admin')->mobile_no ?? '' }}"
                                                            class="form-control" id="phone" placeholder="Phone">
                                                        <span class="v-msg"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="profile_image" class="col-sm-2 col-form-label">Profile
                                                        Photo <span class="is_required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <button title="Image Preview"
                                                                    class="btn btn-primary collapsed" type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#collapseExample1"
                                                                    aria-expanded="false"
                                                                    aria-controls="collapseExample"><i
                                                                        class="fa fa-image fa-lg"></i></button>
                                                            </div>
                                                            <input type="file" name="photo" id="profile_image"
                                                                class="form-control" accept="image/*">
                                                        </div>

                                                        <div class="collapse pt-4" id="collapseExample1">
                                                            <div class="d-flex justify-content-center"
                                                                onclick="javascript: document.getElementById('profile_image').click()">
                                                                <img title="Click to upload image"
                                                                    src="{{ asset('assets/img/blank-img.png') }}"
                                                                    alt="Profile Image" id="profile-img-preview"
                                                                    class="img-fluid img-responsive img-thumbnail"
                                                                    ondragstart="javascript: return false;"
                                                                    style="cursor:pointer;width:280px; height: 280px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="offset-sm-2 col-sm-10">
                                                        <button type="submit" class="btn btn-danger">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.card-body -->
        </div>
    </section>
</div>

@endsection


@push('css')

<style>
    .is_required {
        color: coral !important;
    }

    tr.active {
        background: rgb(249, 255, 218) !important;
        color: #000 !important;
    }

    .profile-img {
        width: 100px;
        height: 100px;
        border-radius: 100%;
    }

    @media screen and (max-width: 768) {
        .profile-img {
            width: 5rem !important;
            height: 4.2rem !important;
        }

        #profile-img-preview {
            height: auto !important;
        }
    }
</style>
@endpush

@push('js')
<script>
    $(function () {
        $(document).ready(function(){
            $(document).on("change",'#profile_image', readFile)
            $(document).on("submit",'#profile-form', saveUserToDatabase)
        })
    });



    function saveUserToDatabase(e){
        e.preventDefault();
        if(!checkValidation()) return false;

        let 
        data    = userObject(),
        obj     = {
            url     : `{{ route('admin.auth_user.profile_update','') }}/${data.id}`,
            method  : 'PUT',
            data    : { data: JSON.stringify(data)},
            dataType: "json"
        };

        makeAjax(obj);
    }


    function resetForm(){
        $('#user_id').val(null);
        $('#name').val(null);
        $('#phone').val(null);
        $('#email').val(null);
        // $('#profile-img-preview')?.attr("src",`{{ asset('assets/img/blank-img.png') }}`);
    }


    function makeAjax(obj={}){

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            ...obj,
            beforeSend(){
                console.log("Sending ...");
            },
            success(res){
                if(res?.success){
                    // console.log("xxx",res);
                    _toastMsg(res?.msg,'success');

                    setTimeout(() => {
                        window.location.reload()
                    }, 2000);

                    $('#userName').text($('#name').val());
                    $('#email-text').text($('#email').val());
                    $('#phone-text').text($('#phone').val());
                    $('.profile-img').attr('src', $('#profile-img-preview').attr("src"));
                    $('#profile_image').val(null)
                    $(`[data-toggle="collapse"]`).trigger('click')
                }
            },
            error(err){
                console.log(err);
                _toastMsg((err.responseJSON?.msg ?? err?.responseJSON?.message) ?? 'Something wents wrong!')
            }
        });
    }


    function userObject(){
        return {
            id      : $('#user_id').val().trim() ?? null,
            name    : $('#name').val().trim(),
            phone   : $('#phone').val().trim(),
            email   : $('#email').val().trim(),
            photo   : $('#profile-img-preview')?.attr("src") ?? null
            // user_type
        };
    }


    function readFile() 
    {
        if (this.files && this.files[0]) 
        {
            let FR = new FileReader();
            FR.addEventListener("load", function(e) {
                $(document).find('#profile-img-preview').attr('src', e.target.result);
            }); 
            
            FR.readAsDataURL( this.files[0] );
        }
    }

    function checkValidation(){

        let 
        isvalid = true,
        elements= [...$(document).find(`[required]:visible`)],
        vMsgEl  = null;

        elements.forEach(elem => {

            let field = $(elem)?.attr("name") ? formatNameField(elem) : "This Field";
            if(!$(elem).val()){
                isvalid = false;
                vMsgEl  = $(elem).closest('[data-col]').find('.v-msg');

                validationMsg($(elem), vMsgEl, field + " is Required!");

            }else{
                $(elem).closest('[data-col]').find('.v-msg').text("");
            }
        });

        if(!isvalid) return false;


        return true;

    }

    function formatNameField(elem, isUpper=true){
        let string = $(elem).attr("name").toString().replace(/[-|_]/gim,' ');
        if(isUpper){
            string = string.split(" ").map(s => s.charAt(0).toUpperCase()+ s.substring(1)).join(" ");
        }
        
        return string;
    }

    function validationMsg(elem="", msgViewElem="", msg="This Field is Required"){
        
        msgViewElem.text('').removeClass("text-danger");
        if(!elem.val()){
            msgViewElem.text(msg).addClass("text-danger");
            return false;
        }

        return true;
    }




</script>
@endpush