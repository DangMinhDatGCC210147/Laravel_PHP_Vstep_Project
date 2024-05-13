@extends('admin.layouts.layout-admin')

@section('content')
    @php
        $borderColors = [
            'Listening' => 'border-primary',
            'Speaking' => 'border-danger',
            'Reading' => 'border-success',
            'Writing' => 'border-secondary',
        ];

        $buttonColors = [
            'Listening' => 'btn-primary',
            'Speaking' => 'btn-danger',
            'Reading' => 'btn-success',
            'Writing' => 'btn-secondary',
        ];

        $badgeColors = [
            'Listening' => 'bg-primary',
            'Speaking' => 'bg-danger',
            'Reading' => 'bg-success',
            'Writing' => 'bg-secondary',
        ];

    @endphp

    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">
                    <h2>Parts of {{ $skillParts->first()->testSkill->skill_name }}</h2>
                </h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a
                                href="{{ route('testSkills.show', ['test_slug' => $test_slug->slug]) }}">4 skills</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $skillParts->first()->testSkill->skill_name }}</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12"><a href="{{ route('testSkills.show', ['test_slug' => $test_slug->slug]) }}"><i
                        class="mdi mdi-backburger"></i> Turn back to previous page</a></div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <hr>
            @foreach ($skillParts as $part)
                <div class="col-md-6">
                    <div class="card border {{ $borderColors[$part->testSkill->skill_name] ?? 'border-primary' }}">
                        <h5 class="card-header">{{ $part->part_name }}</h5>
                        <div class="card-body">
                            <h5 class="card-title">
                                <div class="row">
                                    <div class="col-6">Status: </div>
                                    <div class="col-6 d-flex justify-content-end">
                                        @php
                                            $questionCount = $questionCounts[$part->id];
                                        @endphp
                                        @if ($questionCount == 15 || $questionCount == 12 || $questionCount == 10 || $questionCount == 8 || $questionCount == 2 || $questionCount == 1)
                                            <span class="badge {{ $badgeColors[$part->testSkill->skill_name] ?? 'bg-primary' }}">Full information</span>
                                        @elseif ($questionCount == 0)
                                            <span class="badge bg-warning">No questions yet</span>
                                        @endif
                                    </div>
                                </div>
                            </h5>
                            <p class="card-text">
                            <div class="row">
                                <div class="col-6">Number of questions: </div>
                                <div class="col-6 d-flex justify-content-end">{{ $questionCounts[$part->id] }}</div>
                            </div>
                            </p>
                            <div class="d-flex justify-content-center">
                                {{-- @if ($part->questions->isNotEmpty())
                                    @if ($part->testSkill->skill_name === 'Reading')
                                        <a href="{{ route('questionsReading.edit', ['test_slug' => $test_slug, 'skill_slug' => $skill_slug, 'part_slug' => $part->slug]) }}"
                                            class="btn {{ $buttonColors[$part->testSkill->skill_name] ?? 'btn-primary' }} waves-effect waves-light">Edit
                                            and View Questions</a>
                                    @elseif ($part->testSkill->skill_name === 'Listening')
                                        <a href="{{ route('questionsListening.edit', ['test_slug' => $test_slug, 'skill_slug' => $skill_slug, 'part_slug' => $part->slug]) }}"
                                            class="btn {{ $buttonColors[$part->testSkill->skill_name] ?? 'btn-primary' }} waves-effect waves-light">Edit
                                            and View Questions</a>
                                    @elseif ($part->testSkill->skill_name === 'Writing')
                                        <a href="{{ route('questionsWriting.edit', ['test_slug' => $test_slug, 'skill_slug' => $skill_slug, 'part_slug' => $part->slug]) }}"
                                            class="btn {{ $buttonColors[$part->testSkill->skill_name] ?? 'btn-primary' }} waves-effect waves-light">Edit
                                            and View Questions</a>
                                    @elseif ($part->testSkill->skill_name === 'Speaking')
                                        <a href="{{ route('questionsSpeaking.edit', ['test_slug' => $test_slug, 'skill_slug' => $skill_slug, 'part_slug' => $part->slug]) }}"
                                            class="btn {{ $buttonColors[$part->testSkill->skill_name] ?? 'btn-primary' }} waves-effect waves-light">Edit
                                            and View Questions</a>
                                    @endif
                                @else
                                    <a href="{{ route('questions.create', ['test_slug' => $test_slug, 'skill_slug' => $skill_slug, 'part_slug' => $part->slug]) }}"
                                        class="btn {{ $buttonColors[$part->testSkill->skill_name] ?? 'btn-primary' }} waves-effect waves-light">Add
                                        Questions</a>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                    <!-- end card-box-->
                </div>
            @endforeach
        </div>
    </div>
@endsection
