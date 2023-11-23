@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('content')
    <div class="card mb-4">
        <h5 class="card-header">Profile Details</h5>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <form id="formAccountSettings" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <!-- Account -->
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <img src="{{asset('img/staffs/'.Auth::user()->photo_profile)}}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                    <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                        <span class="d-none d-sm-block">Upload new photo</span>
                        <i class="bx bx-upload d-block d-sm-none"></i>
                        <input type="file" id="upload" class="account-file-input" name="photo_profile" hidden accept="image/png, image/jpeg, image/jpg" />
                        </label>
                        <!-- <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                        <i class="bx bx-reset d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Reset</span>
                        </button> -->

                        <p class="text-muted mb-0">Allowed JPG, JPEG or PNG. Max size of 800K</p>
                    </div>
                </div>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="name" class="form-label">Name</label>
                        <input class="form-control" type="text" id="name" name="name" placeholder="Enter your name" value="{{Auth::user()->name}}" autofocus required/>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control" type="text" name="username" id="username" value="{{Auth::user()->username}}" disabled placeholder="Enter your username"/>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="roles" class="form-label">Role</label>
                        <input class="form-control" type="text" name="roles" id="roles" value="@if(!empty(Auth::user()->getRoleNames())) @foreach(Auth::user()->getRoleNames() as $v){{ $v }}@endforeach @endif" disabled placeholder="Enter your roles"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="email" class="form-label">E-mail</label>
                        <input class="form-control" type="email" id="email" name="email" value="{{Auth::user()->email}}" placeholder="Enter your email" disabled/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="password" class="form-label">Password</label>
                        <input class="form-control" type="password" id="password" name="password" placeholder="Enter your password"/>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="password-confirmation" class="form-label">Password Confirmation</label>
                        <input class="form-control" type="password" id="password-confirmation" name="password-confirmation" placeholder="Enter your password confirmation"/>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2">Save changes</button>
                    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                </div>
            </div>
            <!-- /Account -->
        </form>
    </div>
@endsection
