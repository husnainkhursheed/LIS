@extends('layouts.master')
@section('title')
    @lang('translation.settings')
@endsection
@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            {{-- <img src="{{ URL::asset('build/images/IconicSmiles.png') }}" class="profile-wid-img" alt=""> --}}
            <div class="overlay-content">
                <div class="text-end p-3">
                    {{-- <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                        <input id="profile-foreground-img-file-input" type="file" class="profile-foreground-img-file-input">
                        <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">
                            <i class="ri-image-edit-line align-bottom me-1"></i> Change Cover
                        </label>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!--end col-->
        <div class="col-xxl-12">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i>
                                Change Password
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#socialconfig" role="tab">
                                <i class="far fa-envelope"></i>
                                 Configurations
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#prompt" role="tab">
                                <i class="far fa-envelope"></i>
                                Summary Prompt
                            </a>
                        </li> --}}
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content">
                        @if(Session::has('message'))
                        <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                            {{ Session::get('message') }}
                        </div>

                        <script>
                            // Add a timer to automatically dismiss the alert after 5 seconds (adjust as needed)
                            setTimeout(function() {
                                document.getElementById('alert-message').style.display = 'none';
                            }, 5000); // 5000 milliseconds = 5 seconds
                        </script>
                    @endif
                    @error('password')

                        <div class="alert alert-danger" id="alert-message">
                            {{ $message }}
                        </div>

                        <script>
                            // Add a timer to automatically dismiss the alert after 5 seconds (adjust as needed)
                            setTimeout(function() {
                                document.getElementById('alert-message').style.display = 'none';
                            }, 5000); // 5000 milliseconds = 5 seconds
                        </script>
                    @enderror
                        <!--end tab-pane-->
                        <div class="tab-pane active" id="changePassword" role="tabpanel">
                            <form action="{{ url('/change_password') }}" method="post">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="oldpasswordInput" class="form-label">Old
                                                Password*</label>
                                            <input type="password" class="form-control"  name="current_password" id="oldpasswordInput"
                                                placeholder="Enter current password" value="{{ old('current_password') }}" required>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">New
                                                Password*</label>
                                            <input type="password" class="form-control"  name="password" id="newpasswordInput"
                                                placeholder="Enter new password" value="{{ old('password') }}" required>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">Confirm
                                                Password*</label>
                                            <input type="password" class="form-control"  name="password_confirmation" id="confirmpasswordInput"
                                                placeholder="Confirm password" value="{{ old('password_confirmation') }}" required>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    {{-- <div class="col-lg-12">
                                        <div class="mb-3">
                                            <a href="javascript:void(0);"
                                                class="link-primary text-decoration-underline">Forgot
                                                Password ?</a>
                                        </div>
                                    </div> --}}
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Change
                                                Password</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>

                        </div>
                        <div class="tab-pane" id="socialconfig" role="tabpanel">
                            <form action="{{ url('/social_config') }}" method="post">
                                @csrf
                                <input class="form-control" placeholder="" name="lab_director_nameid" value="{{ $name ? $name->id : ''}}"  hidden="">
                                <div class="row g-2">
                                    <div class="col-lg-12">
                                        <div>
                                            <label for="lab_director_name" class="form-label">Lab director name </label>
                                            <input type="text" class="form-control"  name="lab_director_name" id="lab_director_name"
                                                placeholder="" value="{{$name->lab_director_name ?? ''}}" required>
                                        </div>
                                    </div>


                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection

