@extends('students.layouts.layout-student')

@section('content')
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
                        <h2>Timer: <span class="badge bg-primary" id="timer">59:46</span></h2>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="badge bg-info"><span style="font-size: 15px">Đã trả lời: 0/10</span></div>
                        <button class="btn btn-success">Nộp bài</button>
                    </div>
                </div>
                <div class="m-2">
                    <div class="row">
                        <div class="col-md-6 overflow-auto border-style" style="height: 520px;" id="content-area">
                            @foreach ($test->testSkills as $skill)
                                @foreach ($skill->questions as $question)
                                    <div class="mb-3 content-block skill-{{ $skill->id }}-part-{{ $question->part_name }}" style="display: none;">
                                        @if ($question->readingsAudio)
                                            @if (Str::endsWith($question->readingsAudio->reading_audio_file, ['.mp3', '.wav']))
                                                <audio controls>
                                                    <source src="{{ asset('storage/' . $question->readingsAudio->reading_audio_file) }}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            @elseif (Str::endsWith($question->readingsAudio->reading_audio_file, ['.jpg', '.jpeg', '.png']))
                                                <img src="{{ asset('storage/' . $question->readingsAudio->reading_audio_file) }}" alt="Skill Image" class="img-fluid">
                                            @else
                                                <p>{!! nl2br(e($question->readingsAudio->reading_audio_file)) !!}</p>
                                            @endif
                                        @else
                                            <p>No associated content.</p>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                        </div>                                                                                                                                      
                        <div class="col-md-6 overflow-auto border-style" style="height: 520px;">
                            @foreach ($skills as $skill)
                                @foreach ($skill->questions as $question)
                                    <div class="mb-3 question-block skill-{{ $skill->id }}-part-{{ $question->part_name }}"
                                        style="display: none;">
                                        <strong><p>Question {{ $question->question_number }}: {{ $question->question_text }}</p></strong>
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
                <div class="skill-timer badge" style="background-color: {{ $skill->timer_color }}">
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
        // $(document).ready(function() {
        //     $('.footer .btn-group button').click(function() {
        //         var skillPart = $(this).data('skill-part');
        //         console.log(skillPart);

        //         // Ẩn tất cả các phần của content-block và question-block
        //         $('.content-block, .question-block').hide();

        //         // Hiển thị phần tử đầu tiên của content-block có class chứa skillPart
        //         $('.content-block[class*="' + skillPart + '"]').first().show();

        //         // Hiển thị tất cả các phần tử của question-block có class chứa skillPart
        //         $('.question-block[class*="' + skillPart + '"]').show();
        //     });
        // });

        $(document).ready(function() {
            $('.footer .btn-group button').click(function() {
                var skillPart = $(this).data('skill-part');
                console.log(skillPart);
                $('.content-block, .question-block').hide();
                $('[class*="' + skillPart + '"]').show();
            });
        });
    </script>
@endsection
