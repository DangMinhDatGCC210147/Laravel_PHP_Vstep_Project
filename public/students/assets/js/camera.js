document.addEventListener('DOMContentLoaded', function () {
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var captureButton = document.getElementById('capture');

    // Activate webcam
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
            video.srcObject = stream;
            video.play();
        })
        .catch(function (error) {
            console.error("Không thể truy cập webcam", error);
        });

    // Handle the capture button click
    captureButton.addEventListener('click', function () {
        if (captureButton.textContent === 'Chụp hình') {
            // Draw the video frame to the canvas
            context.drawImage(video, 0, 0, 250, 170);
            video.style.display = 'none'; // Hide the video
            canvas.style.display = 'block'; // Show the canvas
            captureButton.textContent = 'Chụp lại';
            captureButton.className = 'btn btn-warning mt-3'; // Change button color to yellow
        } else {
            video.style.display = 'block'; // Show the video
            canvas.style.display = 'none'; // Hide the canvas
            captureButton.textContent = 'Chụp hình';
            captureButton.className = 'btn btn-danger mt-3'; // Change button color back to red
        }
    });
});