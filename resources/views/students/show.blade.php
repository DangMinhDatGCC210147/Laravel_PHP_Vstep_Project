@extends('students.layouts.layout-student')

@section('content')
    @php
        $badgeColors = [
            'Listening' => 'bg-primary',
            'Speaking' => 'bg-danger',
            'Reading' => 'bg-success',
            'Writing' => 'bg-secondary',
        ];
    @endphp
    <div class="px-3">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="card">
                <div class="row text-dark card-header navbar">
                    <div class="col-md-1">
                        <ul class="topbar-menu d-flex align-items-center justify-center">
                            <li class="nav-link d-flex" id="theme-mode">
                                <button class="btn btn-warning"><i class="bx bx-moon font-size-18"></i></button>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-start">
                        <h2>{{ $test->test_name }}</h2>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2>Timer: 
                        @foreach ($skills as $skill)
                            <span class="badge bg-primary" id="skill-{{ $skill->id }}-timer }}">
                                {{ $skill->time_limit }}
                            </span>
                        @endforeach
                        </h2>                     
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="badge bg-info"><span style="font-size: 15px">Đã trả lời: 0/10</span></div>
                        <button class="btn btn-success">Nộp bài</button>
                    </div>
                </div>
                <div class="m-2">
                    <div class="row">
                        <div class="col-md-6 overflow-auto border-style" style="height: 32vw;" id="content-area">
                            @foreach ($test->testSkills as $skill)
                                @foreach ($skill->readingsAudios as $readingAudio)
                                    <div class="mb-3 content-block skill-{{ $skill->id }}-part-{{ $readingAudio->part_name }}"
                                        style="display: none;">
                                        @if ($readingAudio->isAudio())
                                            <audio controls>
                                                <source src="{{ asset('storage/' . $readingAudio->reading_audio_file) }}"
                                                    type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @elseif ($readingAudio->isImage())
                                            <img src="{{ asset('storage/' . $readingAudio->reading_audio_file) }}"
                                                alt="Skill Image" class="img-fluid">
                                        @elseif ($readingAudio->isText())
                                            <p>{!! nl2br(e($readingAudio->reading_audio_file)) !!}</p>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        <div class="col-md-6 overflow-auto border-style" style="height: 32vw;">
                            <form action="" method="post">
                                @foreach ($skills as $skill)
                                    @foreach ($skill->questions as $question)
                                        <div class="mb-3 question-block skill-{{ $skill->id }}-part-{{ $question->part_name }}"
                                            style="display: none;">
                                            <strong>
                                                <p>Question {{ $question->question_number }}: {{ $question->question_text }}
                                                </p>
                                            </strong>
                                            @foreach ($question->options as $index => $option)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="question_{{ $question->id }}" id="option_{{ $option->id }}"
                                                        value="{{ $option->id }}">
                                                    <label class="form-check-label" for="option_{{ $option->id }}">
                                                        {{ chr(65 + $index) }}. {{ $option->option_text }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Start -->
    <footer class="footer">
        @foreach ($skills as $skill)
            <div class="skill-section">
                <div class="btn-group">
                    @php
                        $usedParts = [];
                    @endphp
                    @foreach ($skill->questions as $part)
                        <!-- Assuming each skill has parts -->
                        @if (!in_array($part->part_name, $usedParts))
                            <button class="btn btn-secondary btn-sm"
                                data-skill-part="skill-{{ $skill->id }}-part-{{ $part->part_name }}">
                                {{ $part->part_name }}
                            </button>
                            @php
                                $usedParts[] = $part->part_name;
                            @endphp
                        @endif
                    @endforeach
                </div>
                <div class="skill-timer badge {{ $badgeColors[$skill->skill_name] ?? 'bg-primary' }}">
                    {{ $skill->skill_name }} -
                    {{ $skill->time_limit == '01:00:00' ? '60' : explode(':', $skill->time_limit)[1] }}

                </div>
            </div>
        @endforeach

        <!-- Controls Column -->
        <div class="skill-section">
            <div class="btn-group">
                <button class="btn btn-info mb-2">Tiếp tục</button>
                <button class="btn btn-primary mb-2">Lưu bài</button>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        var audioElements;
        var initialSkillParts = @json($skills);
        var initialSkillPart = initialSkillParts[3].id;
        var skillPartIdentifier = 'skill-' + initialSkillPart + '-part-Part 1';

        $(document).ready(function() {
            audioElements = $('audio');

            // Function to show the specified skill part and update button colors
            function showSkillPart(skillPart) {
                // Pause and reset all audio elements
                audioElements.each(function() {
                    this.pause();
                    this.currentTime = 0;
                });

                // Hide all content and question blocks
                $('.content-block, .question-block').hide();

                // Show the specified skill part
                $('[class*="' + skillPart + '"]').show();

                // Update button colors
                $('.footer .btn-group button').removeClass('btn-warning').addClass('btn-secondary');
                $('.footer .btn-group button[data-skill-part="' + skillPart + '"]').removeClass('btn-secondary')
                    .addClass('btn-warning');

                // Save the current skill part to local storage
                localStorage.setItem('currentSkillPart', skillPart);
            }

            // Get the saved skill part from local storage or default to "Listening Part 1"
            var savedSkillPart = localStorage.getItem('currentSkillPart') || skillPartIdentifier;

            // Show the saved skill part or default
            showSkillPart(savedSkillPart);

            $('.footer .btn-group button').click(function() {
                var skillPart = $(this).data('skill-part');
                console.log(skillPart);
                showSkillPart(skillPart);
            });

            $('#submitTestButton').click(function() {
                $('#testForm').submit();
            });
        });
    </script>
@endsection
