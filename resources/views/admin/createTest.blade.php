@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">{{ isset($test_slug) ? 'Edit Test' : 'Create New Test' }}</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Forms</a></li>
                        <li class="breadcrumb-item active">{{ isset($test_slug) ? 'Edit' : 'Create' }} Test</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <a href="{{ route('tableTest.index') }}">Turn back to previous page</a>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ isset($test_slug) ? 'Edit Test' : 'Create Test' }}</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2">
                                <form class="form-horizontal" action="{{ isset($test_slug->id) ? route('test.update', ['test_slug' => $test_slug->slug]) : route('test.store') }}" method="POST">
                                    @csrf
                                    @if(isset($test_slug->id))
                                        @method('PUT')
                                    @endif
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="simpleinput">Test Code</label>
                                        <div class="col-md-10">
                                            <input type="text" id="simpleinput" class="form-control" value="{{ $test_slug->test_code ?? '' }}" name="test_code" required>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="simpleinput">Test Name</label>
                                        <div class="col-md-10">
                                            <input type="text" id="simpleinput" class="form-control" value="{{ $test_slug->test_name ?? '' }}"
                                                placeholder="Test Name" name="test_name" required>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="example-time">Duration</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="time" id="example-time" name="duration" value="{{ $test_slug->duration ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="example-date">Start Date</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="date" name="start_date" id="example-date" value="{{ $test_slug->start_date ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="example-date">End Date</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="date" name="end_date" id="example-date" value="{{ $test_slug->end_date ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label">Lecturer</label>
                                        <div class="col-md-10">
                                            <select class="form-control" name="instructor_id" required>
                                                <option selected disabled>Click to choose</option>
                                                @foreach($lecturers as $lecturer)
                                                    <option value="{{ $lecturer->id}}" {{ (isset($test_slug) && $lecturer->id == $lecturer->id) ? 'selected' : '' }}>{{ $lecturer->name}}</option>
                                                @endforeach
                                            </select>                                            
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-10">
                                            <button type="submit" class="btn btn-primary w-xl">{{ isset($test_slug) ? 'Update' : 'Create' }}</button>
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
