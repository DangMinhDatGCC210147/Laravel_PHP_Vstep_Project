@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">{{ isset($user) ? 'Edit' : 'Create' }} Lecturer Account</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">{{ isset($user) ? 'Edit' : 'Create' }} New Lecturer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-12"><a href="{{ route('tableLecturer.index') }}">
                <i class="mdi mdi-backburger"></i> Turn back to previous page</a></div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ isset($user) ? 'Edit' : 'Create' }} New Lecturer Account</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2">
                                <form class="form-horizontal"
                                    action="{{ isset($user) ? route('createInstructor.update', $user->slug) : route('createInstructor.store') }}"
                                    method="POST">
                                    @csrf
                                    @if (isset($user))
                                        @method('PUT')
                                    @endif
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="simple-input">Full Name</label>
                                        <div class="col-md-10">
                                            <input type="text" id="simple-input" class="form-control"
                                                value="{{ isset($user) ? $user->name : '' }}" placeholder="Full name"
                                                name="name" required>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="simpleinput">Email</label>
                                        <div class="col-md-10">
                                            <input type="email" id="simpleinput" class="form-control"
                                                value="{{ isset($user) ? $user->email : '' }}" placeholder="Email"
                                                name="email" required>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="example-email">Lecturer ID</label>
                                        <div class="col-md-10">
                                            <input type="text" id="example-email" class="form-control"
                                                placeholder="Lecturer ID" name="lecturer_id"
                                                value="{{ isset($user) ? $user->lecturer_id : '' }}" required>
                                        </div>
                                    </div>
                                        <div class="mb-2 row">
                                            <label class="col-md-2 col-form-label" for="example-password">Password</label>
                                            <div class="col-md-10">
                                                <input type="password" class="form-control" id="example-password"
                                                    value="" placeholder="Password" name="password" required>
                                            </div>
                                        </div>
                                    <div class="mb-2 row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-10">
                                            <button type="submit"
                                                class="btn btn-primary w-xl">{{ isset($user) ? 'Update' : 'Register' }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <!-- end row -->
                </div>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
@endsection
