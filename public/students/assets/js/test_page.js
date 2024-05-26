$(document).ready(function () {
    audioElements = $('audio');

    function formatTimeLimit(timeLimit) {
        if (timeLimit === '01:00:00') {
            return '60:00';
        }
        const parts = timeLimit.split(':');
        return parts[1] + ':' + parts[2];
    }

    function startCountdown(timeLimit) {
        clearInterval(countdownTimer);
        timeRemaining = timeLimit;

        countdownTimer = setInterval(function () {
            timeRemaining--;
            localStorage.setItem('timeRemaining', timeRemaining); // Lưu trạng thái thời gian vào localStorage
            if (timeRemaining <= 0) {
                clearInterval(countdownTimer);
                autoMoveToNextSkill();
            }
            updateTimerDisplay();
        }, 1000);
    }

    function convertTimeLimitToSeconds(timeLimit) {
        const parts = timeLimit.split(':');
        if (parts.length !== 3) {
            return 0; // Giá trị mặc định nếu định dạng không đúng
        }
        return parseInt(parts[0]) * 3600 + parseInt(parts[1]) * 60 + parseInt(parts[2]);
    }

    function updateTimerDisplay() {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        $('#skill-timer').text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
    }

    function showSkillPart(skillPart, skillId) {
        // Pause and reset all audio elements
        audioElements.each(function () {
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
        // Save the current skill part to localStorage
        localStorage.setItem('currentSkillPart', skillPart);
        // Update the answered count display
        updateAnsweredCount(skillPart);
        // Scroll to the top of the container
        $('#content-area').scrollTop(0);
        $('#testForm').closest('.col-md-6').scrollTop(0);
    }

    function updateAnsweredCount(skillPart) {
        var answered = 0;
        var total = $('[class*="' + skillPart + '"].question-block').length;
        $('[class*="' + skillPart + '"].question-block').each(function () {
            if ($(this).find('input[type=radio]:checked').length > 0) {
                answered++;
            }
        });
        $('#answered-count span').text('Đã trả lời: ' + answered + '/' + total);
    }

    function updateSkillButtons() {
        $('.skill-part-btn').prop('disabled', true); // Disable all buttons
        $('.skill-part-btn[data-skill-id="' + skillIds[currentSkillIndex] + '"]').prop('disabled', false); // Enable buttons for current skill
        // Save the current skill index to localStorage
        localStorage.setItem('currentSkillIndex', currentSkillIndex);
    }

    function autoMoveToNextSkill() {
        Swal.fire({
            title: 'Hết thời gian',
            text: "Thời gian của kỹ năng này đã hết. Chuyển sang kỹ năng tiếp theo.",
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
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
                currentSkillTimeLimit = convertTimeLimitToSeconds(nextTimeLimit); // Cập nhật thời gian giới hạn cho kỹ năng tiếp theo
                startCountdown(currentSkillTimeLimit);
                showSkillPart(nextSkillPart, nextSkillId);
            }
        });
    }

    // Get the saved skill part from localStorage or default to the initial skill part
    var savedSkillPart = localStorage.getItem('currentSkillPart') || skillPartIdentifier;
    var savedTimeLimit = localStorage.getItem('currentSkillPartTimeLimit') || '00:47:00'; // Thay đổi giá trị mặc định nếu cần thiết
    timeRemaining = parseInt(localStorage.getItem('timeRemaining')) || convertTimeLimitToSeconds(savedTimeLimit);

    // Initialize partAnswered object
    $('[class*="question-block"]').each(function () {
        var skillPart = $(this).attr('class').split(' ').find(cls => cls.startsWith('skill-'));
        partAnswered[skillPart] = partAnswered[skillPart] || {};
        var questionId = $(this).find('input[type=radio]').attr('name');
        partAnswered[skillPart][questionId] = false;
    });

    // Get the saved skill index from localStorage or default to 0
    currentSkillIndex = parseInt(localStorage.getItem('currentSkillIndex')) || 0;

    // Show the saved skill part or default
    var initialSkillId = skillIds[currentSkillIndex];
    currentSkillTimeLimit = timeRemaining; // Lưu thời gian giới hạn hiện tại
    startCountdown(timeRemaining); // Bắt đầu đếm ngược với thời gian còn lại hiện tại
    showSkillPart(savedSkillPart, initialSkillId);

    // Update the skill buttons state
    updateSkillButtons();

    $('.skill-part-btn').click(function () {
        var skillPart = $(this).data('skill-part');
        var skillId = $(this).data('skill-id');
        showSkillPart(skillPart, skillId);
    });

    $('#next-skill-btn').click(function () {
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
                currentSkillTimeLimit = convertTimeLimitToSeconds(nextTimeLimit); // Cập nhật thời gian giới hạn cho kỹ năng tiếp theo
                startCountdown(currentSkillTimeLimit);
                showSkillPart(nextSkillPart, nextSkillId);
            }
        });
    });

    $('#submitTestButton').click(function () {
        $('#testForm').submit();
    });

    $('input[type=radio]').change(function () {
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

    $('#reset-btn').click(function () {
        // Clear localStorage
        localStorage.clear();
        // Reload the page to reset everything
        location.reload();
    });
});

// Ngăn chặn các tổ hợp phím phổ biến mở Developer Tools
document.addEventListener('keydown', function (event) {
    if (event.keyCode == 123) { // F12
        event.preventDefault();
    } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Ctrl+Shift+I
        event.preventDefault();
    } else if (event.ctrlKey && event.shiftKey && event.keyCode == 74) { // Ctrl+Shift+J
        event.preventDefault();
    } else if (event.ctrlKey && event.keyCode == 85) { // Ctrl+U
        event.preventDefault();
    } else if (event.ctrlKey && event.keyCode == 83) { // Ctrl+S
        event.preventDefault();
    } else if (event.ctrlKey && event.keyCode == 80) { // Ctrl+P
        event.preventDefault();
    }
});

// Ngăn chặn chuột phải
document.addEventListener('contextmenu', function (event) {
    event.preventDefault();
});

// Ngăn chặn các hành động copy, cut, paste
document.addEventListener('copy', function (event) {
    event.preventDefault();
});

document.addEventListener('cut', function (event) {
    event.preventDefault();
});

document.addEventListener('paste', function (event) {
    event.preventDefault();
});

// Ngăn chặn chọn văn bản
document.addEventListener('selectstart', function (event) {
    event.preventDefault();
});

// Phương pháp phát hiện Developer Tools
function detectDevTools() {
    const element = new Image();
    Object.defineProperty(element, 'id', {
        get: function () {
            alert('Developer Tools are not allowed.');
            window.location.reload();
        }
    });
    console.log(element);
}

setInterval(detectDevTools, 1000);

// Ngăn chặn menu chuột phải bổ sung
document.addEventListener('mousedown', function (event) {
    if (event.button === 2 || event.button === 3) {
        event.preventDefault();
    }
});

// Disable text selection CSS
document.documentElement.style.userSelect = 'none';
document.documentElement.style.msUserSelect = 'none';
document.documentElement.style.mozUserSelect = 'none';

document.addEventListener('DOMContentLoaded', function () {
    var audio = document.getElementById('audioPlayer');

    audio.addEventListener('play', function () {
        // Vô hiệu hóa thanh tiến trình khi âm thanh đang phát
        disableSeekBar();
    });

    function disableSeekBar() {
        audio.addEventListener('seeking', preventSeeking);
    }

    function preventSeeking(event) {
        // Ngăn chặn tua tới lui khi âm thanh đang phát
        if (!audio.paused) {
            event.preventDefault();
            audio.currentTime = audio.currentTime; // Giữ nguyên thời gian hiện tại
        }
    }

    // Xóa sự kiện ngăn chặn tua khi âm thanh bị tạm dừng
    audio.addEventListener('pause', function () {
        audio.removeEventListener('seeking', preventSeeking);
    });
});