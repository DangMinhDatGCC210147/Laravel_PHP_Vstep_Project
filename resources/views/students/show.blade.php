@extends('students.layouts.layout-student')

@section('content')
    @php
        $badgeColors = [
            'Listening' => 'bg-primary',
            'Speaking' => 'bg-danger',
            'Reading' => 'bg-success',
            'Writing' => 'bg-secondary',
        ];
        $skillIds = $skills->pluck('id')->sortDesc()->values(); // Lấy danh sách các skill ID theo thứ tự giảm dần
    @endphp
    <div class="px-3">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="card">
                <div class="row text-dark card-header navbar">
                    <div class="col-md-1">
                        <button class="btn btn-warning d-flex justify-content-center" id="theme-mode"><i class="bx bx-moon font-size-18"></i></button>
                    </div>
                    <div class="col-md-4 text-start">
                        <h2>{{ $test->test_name }}</h2>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2>Timer: 
                            <span class="badge bg-primary" id="skill-timer">
                                47:00
                            </span>
                        </h2>                     
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="badge bg-info" id="answered-count"><span style="font-size: 15px"></span></div>
                        <button class="btn btn-success" id="submitTestButton">Nộp bài</button>
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
                            <form action="" method="post" id="testForm">
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
                            <button class="btn btn-secondary btn-sm skill-part-btn"
                                data-skill-part="skill-{{ $skill->id }}-part-{{ $part->part_name }}" data-time-limit="{{ $skill->time_limit }}" data-skill-id="{{ $skill->id }}">
                                {{ str_replace('_', ' ', $part->part_name) }}
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
                <button class="btn btn-info mb-2" id="next-skill-btn">Tiếp tục</button>
                <button class="btn btn-primary mb-2">Lưu bài</button>
                <button class="btn btn-danger mb-2" id="reset-btn">Làm mới</button>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        var audioElements;
        var skillIds = @json($skillIds);
        var currentSkillIndex = 0; // Chỉ số của kỹ năng hiện tại
        var initialSkillPart = skillIds[currentSkillIndex];
        var skillPartIdentifier = 'skill-' + initialSkillPart + '-part-Part_1';
        var answeredCount = {};
        var partAnswered = {};
    
        $(document).ready(function() {
            audioElements = $('audio');
    
            function formatTimeLimit(timeLimit) {
                if (timeLimit === '01:00:00') {
                    return '60:00';
                }
                const parts = timeLimit.split(':');
                return parts[1] + ':' + parts[2];
            }
    
            function showSkillPart(skillPart, timeLimit, skillId) {
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
                $('.skill-part-btn').removeClass('btn-warning').addClass('btn-secondary');
                $('.skill-part-btn[data-skill-part="' + skillPart + '"]').removeClass('btn-secondary')
                    .addClass('btn-warning');
                // Save the current skill part to local storage
                localStorage.setItem('currentSkillPart', skillPart);
                // Update the timer display
                $('#skill-timer').text(formatTimeLimit(timeLimit));
                // Update the answered count display
                updateAnsweredCount(skillPart);
                // Scroll to the top of the container
                $('#content-area').scrollTop(0);
                $('#testForm').closest('.col-md-6').scrollTop(0);
            }
    
            function updateAnsweredCount(skillPart) {
                var answered = 0;
                var total = $('[class*="' + skillPart + '"].question-block').length;
                $('[class*="' + skillPart + '"].question-block').each(function() {
                    if ($(this).find('input[type=radio]:checked').length > 0) {
                        answered++;
                    }
                });
                $('#answered-count span').text('Đã trả lời: ' + answered + '/' + total);
            }
    
            function updateSkillButtons() {
                $('.skill-part-btn').prop('disabled', true); // Disable all buttons
                $('.skill-part-btn[data-skill-id="' + skillIds[currentSkillIndex] + '"]').prop('disabled', false); // Enable buttons for current skill
                // Save the current skill index to local storage
                localStorage.setItem('currentSkillIndex', currentSkillIndex);
            }
    
            // Get the saved skill part from local storage or default to the initial skill part
            var savedSkillPart = localStorage.getItem('currentSkillPart') || skillPartIdentifier;
            var savedTimeLimit = localStorage.getItem('currentSkillPartTimeLimit') || '60:00';
    
            // Initialize partAnswered object
            $('[class*="question-block"]').each(function() {
                var skillPart = $(this).attr('class').split(' ').find(cls => cls.startsWith('skill-'));
                partAnswered[skillPart] = partAnswered[skillPart] || {};
                var questionId = $(this).find('input[type=radio]').attr('name');
                partAnswered[skillPart][questionId] = false;
            });
    
            // Get the saved skill index from local storage or default to 0
            currentSkillIndex = parseInt(localStorage.getItem('currentSkillIndex')) || 0;
    
            // Show the saved skill part or default
            var initialSkillId = skillIds[currentSkillIndex];
            showSkillPart(savedSkillPart, savedTimeLimit, initialSkillId);
    
            // Update the skill buttons state
            updateSkillButtons();
    
            $('.skill-part-btn').click(function() {
                var skillPart = $(this).data('skill-part');
                var timeLimit = $(this).data('time-limit');
                var skillId = $(this).data('skill-id');
                showSkillPart(skillPart, timeLimit, skillId);
                localStorage.setItem('currentSkillPartTimeLimit', timeLimit);
            });
    
            $('#next-skill-btn').click(function() {
                Swal.fire({
                    title: 'Xác nhận',
                    text: "Bạn sẽ không thể quay lại kỹ năng trước đó. Bạn có chắc chắn muốn tiếp tục?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        currentSkillIndex++;
                        if (currentSkillIndex >= skillIds.length) {
                            currentSkillIndex = skillIds.length - 1; // Ensure we don't go out of bounds
                        }
                        updateSkillButtons();
                        var nextSkillId = skillIds[currentSkillIndex];
                        var nextSkillPart = 'skill-' + nextSkillId + '-part-Part_1';
                        var nextTimeLimit = $('[data-skill-id="' + nextSkillId + '"]').data('time-limit');
                        showSkillPart(nextSkillPart, nextTimeLimit, nextSkillId);
                    }
                });
            });
    
            $('#submitTestButton').click(function() {
                $('#testForm').submit();
            });
    
            $('input[type=radio]').change(function() {
                var skillPart = $(this).closest('.question-block').attr('class').split(' ').find(cls => cls.startsWith('skill-'));
                var questionId = $(this).attr('name');
                
                if (!partAnswered[skillPart][questionId]) {
                    partAnswered[skillPart][questionId] = true;
                    if (!answeredCount[skillPart]) {
                        answeredCount[skillPart] = 0;
                    }
                    answeredCount[skillPart]++;
                }
    
                updateAnsweredCount(skillPart);
            });

            $('#reset-btn').click(function() {
                // Clear localStorage
                localStorage.clear();
                // Reload the page to reset everything
                location.reload();
            });
        });
    </script>       
@endsection
