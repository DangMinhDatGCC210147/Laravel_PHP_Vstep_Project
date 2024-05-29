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
                        <button class="btn btn-warning d-flex justify-content-center" id="theme-mode"><i
                                class="bx bx-moon font-size-18"></i></button>
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
                    <div class="row" id="content-row">
                        <div class="col-md-6 overflow-auto border-style" style="height: 32vw;" id="content-area">
                            @foreach ($test->testSkills as $skill)
                                @foreach ($skill->readingsAudios as $readingAudio)
                                    <div class="mb-3 content-block skill-{{ $skill->id }}-part-{{ $readingAudio->part_name }}"
                                        style="display: none;">
                                        @if ($readingAudio->isAudio())
                                            <audio controls controlsList="nodownload" id="audioPlayer">
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
                        <div class="col-md-6 overflow-auto border-style" style="height: 32vw;" id="form-area">
                            @foreach ($skills as $skill)
                                <form
                                    @if ($skill->skill_name == 'Listening') action="/saveListening"
                                    @elseif($skill->skill_name == 'Speaking')
                                        action="/saveSpeaking"
                                    @elseif($skill->skill_name == 'Reading')
                                        action="/saveReading"
                                    @elseif($skill->skill_name == 'Writing')
                                        action="/saveWriting" @endif
                                    method="post" id="testForm-{{ $skill->id }}" class="testForm">
                                    @csrf
                                    @foreach ($skill->questions as $index => $question)
                                        <div class="mb-3 question-block skill-{{ $skill->id }}-part-{{ $question->part_name }}"
                                            style="display: none;">
                                            <strong>
                                                <p>Question {{ $question->question_number }}: {{ $question->question_text }}
                                                </p>
                                            </strong>
                                            @if ($skill->skill_name == 'Writing')
                                                <!-- Textarea for Writing responses -->
                                                <div class="showCount d-flex justify-content-end">
                                                    <strong>
                                                        <div id="wordCount_{{ $question->id }}" class="countWord">0 words
                                                        </div>
                                                    </strong>
                                                </div>
                                                <textarea name="responses[{{ $question->id }}]" id="response_{{ $question->id }}" class="form-control" rows="19"
                                                    placeholder="Type your response here..."></textarea>
                                            @elseif($skill->skill_name == 'Speaking')
                                                @foreach ($question->options as $index => $option)
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="option_{{ $option->id }}">
                                                            {{ $index + 1 }}. {{ $option->option_text }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                <div class="audio-response d-flex pt-3 pb-3">
                                                    <button id="recordButton" class="btn btn-info">Start
                                                        Recording</button>
                                                    <button id="stopButton" class="btn btn-warning" disabled>Stop
                                                        Recording</button>
                                                    <audio id="audio" controls></audio>
                                                </div>
                                            @else
                                                @foreach ($question->options as $index => $option)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="responses[{{ $question->id }}]"
                                                            id="option_{{ $option->id }}"
                                                            value="{{ $option->option_text }}">
                                                        <label class="form-check-label" for="option_{{ $option->id }}">
                                                            {{ chr(65 + $index) }}. {{ $option->option_text }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                                </form>
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
                            <button class="btn btn-secondary btn-sm skill-part-btn"
                                data-skill-part="skill-{{ $skill->id }}-part-{{ $part->part_name }}"
                                data-time-limit="{{ $skill->time_limit }}" data-skill-id="{{ $skill->id }}">
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
                <button class="btn btn-primary mb-2" id="save-btn">Lưu bài</button>
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
        var countdownTimer;
        var timeRemaining;
        var currentSkillTimeLimit;
    </script>
    <script>
        let mediaRecorder;
        let chunks = [];
        const recordButton = document.getElementById('recordButton');
        const stopButton = document.getElementById('stopButton');
        const audio = document.getElementById('audio');

        recordButton.addEventListener('click', startRecording);
        stopButton.addEventListener('click', stopRecording);

        function startRecording() {
            navigator.mediaDevices.getUserMedia({
                    audio: true
                })
                .then(function(stream) {
                    mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.ondataavailable = function(e) {
                        chunks.push(e.data);
                    };
                    mediaRecorder.onstop = function(e) {
                        const blob = new Blob(chunks, {
                            'type': 'audio/ogg; codecs=opus'
                        });
                        chunks = [];
                        const audioURL = URL.createObjectURL(blob);
                        audio.src = audioURL;
                    };
                    mediaRecorder.start();
                })
                .catch(function(err) {
                    console.error('Error accessing microphone', err);
                });

            recordButton.disabled = true;
            stopButton.disabled = false;
        }

        function stopRecording() {
            mediaRecorder.stop();
            recordButton.disabled = false;
            stopButton.disabled = true;
        }
    </script>
    <script src="{{ asset('students/assets/js/test_page.js') }}"></script>
@endsection
