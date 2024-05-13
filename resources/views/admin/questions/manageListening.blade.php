@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">
                    <h2>Listening Skill - {{ $test_slug->test_name }}</h2>
                </h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item">
                            <a href="#">List of Skills</a>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-6">
                <a href="{{ route('testSkills.show', $test_slug->slug) }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page
                </a>
            </div>
        </div>
    </div>
    @if ($passages == null)
        <div class="container py-4">
            <div class="card shadow">
                <div class="card-body p-5">
                    <form
                        action="{{ route('listening.questions.store', ['test_slug' => $test_slug, 'skill_id' => $skill->id]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Part 1: Questions 1-8 --}}
                        <div class="mb-4">
                            <h3>Part 1 - Listening Skill - {{ $test_slug->test_name }}</h3>
                            <input type="file" name="audio_file[1]" class="form-control mb-2" required>
                            @for ($q = 1; $q <= 8; $q++)
                                <div class="mb-3">
                                    <label class="form-label">Question {{ $q }}</label>
                                    <input type="text" name="questions[{{ $q }}][text]"
                                        class="form-control mb-2" placeholder="Enter question {{ $q }}">
                                    <div class="row">
                                        @foreach (range(1, 4) as $option)
                                            <div class="col-md-6">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="questions[{{ $q }}][correct_answer]"
                                                        value="{{ $option }}"
                                                        id="defaultCheck{{ $q }}-{{ $option }}">
                                                    <label class="form-check-label"
                                                        for="defaultCheck{{ $q }}-{{ $option }}">
                                                        Option {{ $option }}
                                                    </label>
                                                    <input type="text"
                                                        name="questions[{{ $q }}][options][{{ $option }}]"
                                                        class="form-control" placeholder="Option {{ $option }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endfor
                        </div>

                        {{-- Part 2: Questions 9-20 --}}
                        <div class="mb-4">
                            <h3>Part 2 - Listening Skill - {{ $test_slug->test_name }}</h3>
                            <input type="file" name="audio_file[2]" required class="form-control mb-2">
                            @for ($q = 9; $q <= 20; $q++)
                                <div class="mb-3">
                                    <label class="form-label">Question {{ $q }}</label>
                                    <input type="text" name="questions[{{ $q }}][text]"
                                        class="form-control mb-2" placeholder="Enter question {{ $q }}">
                                    <div class="row">
                                        @foreach (range(1, 4) as $option)
                                            <div class="col-md-6">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="questions[{{ $q }}][correct_answer]"
                                                        value="{{ $option }}"
                                                        id="defaultCheck{{ $q }}-{{ $option }}">
                                                    <label class="form-check-label"
                                                        for="defaultCheck{{ $q }}-{{ $option }}">
                                                        Option {{ $option }}
                                                    </label>
                                                    <input type="text"
                                                        name="questions[{{ $q }}][options][{{ $option }}]"
                                                        class="form-control" placeholder="Option {{ $option }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endfor
                        </div>

                        {{-- Part 3: Questions 21-35 --}}
                        <div class="mb-4">
                            <h3>Part 3 - Listening Skill - {{ $test_slug->test_name }}</h3>
                            <input type="file" name="audio_file[3]" class="form-control mb-2" required>
                            @for ($q = 21; $q <= 35; $q++)
                                <div class="mb-3">
                                    <label class="form-label">Question {{ $q }}</label>
                                    <input type="text" name="questions[{{ $q }}][text]"
                                        class="form-control mb-2" placeholder="Enter question {{ $q }}">
                                    <div class="row">
                                        @foreach (range(1, 4) as $option)
                                            <div class="col-md-6">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="questions[{{ $q }}][correct_answer]"
                                                        value="{{ $option }}"
                                                        id="defaultCheck{{ $q }}-{{ $option }}">
                                                    <label class="form-check-label"
                                                        for="defaultCheck{{ $q }}-{{ $option }}">
                                                        Option {{ $option }}
                                                    </label>
                                                    <input type="text"
                                                        name="questions[{{ $q }}][options][{{ $option }}]"
                                                        class="form-control" placeholder="Option {{ $option }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="buttons d-flex justify-content-center">
                            <button type="submit" class="btn btn-warning">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @else
        <h2>Edit</h2>
    @endif
@endsection
