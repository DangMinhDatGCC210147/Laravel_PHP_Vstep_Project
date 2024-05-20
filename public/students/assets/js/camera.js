// document.addEventListener('DOMContentLoaded', function () {
//     var video = document.getElementById('video');
//     var canvas = document.getElementById('canvas');
//     var context = canvas.getContext('2d');
//     var captureButton = document.getElementById('capture');
//     var formElement = document.getElementById('imageUploadForm');

//     navigator.mediaDevices.getUserMedia({ video: true })
//         .then(function (stream) {
//             video.srcObject = stream;
//             video.play();
//         })
//         .catch(function (error) {
//             console.error("Không thể truy cập webcam", error);
//         });

//     captureButton.addEventListener('click', function () {
//         context.drawImage(video, 0, 0, canvas.width, canvas.height);
//         canvas.toBlob(function(blob) {
//             var formData = new FormData(formElement);
//             formData.append('image', blob);
//             fetch('/save-image', {
//                 method: 'POST',
//                 body: formData
//             })
//             .then(response => response.json())
//             .then(data => {
//                 console.log('Success:', data);
//             })
//             .catch(error => {
//                 console.error('Error:', error);
//             });
//         }, 'image/jpeg');
//     });
// });
document.addEventListener('DOMContentLoaded', function () {
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var captureButton = document.getElementById('capture');
    var formElement = document.getElementById('imageUploadForm');
    var isCapturing = false; // Ban đầu, video đang được hiển thị

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
            video.srcObject = stream;
            video.play();
        })
        .catch(function (error) {
            console.error("Không thể truy cập webcam", error);
        });

    captureButton.addEventListener('click', function () {
        if (!isCapturing) { // Nếu đang hiển thị video, chuyển sang hiển thị hình ảnh đã chụp
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.style.display = 'block'; // Hiển thị canvas
            video.style.display = 'none'; // Ẩn video

            canvas.toBlob(function(blob) {
                var formData = new FormData(formElement);
                formData.append('image', blob);
                fetch('/save-image', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // console.log('Success:', data);
                    captureButton.textContent = 'Chụp lại';
                    captureButton.className = 'btn btn-warning mt-3';
                    isCapturing = true;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }, 'image/jpeg');
        } else { // Nếu đang hiển thị canvas, chuyển sang hiển thị video
            video.style.display = 'block'; // Hiển thị video
            canvas.style.display = 'none'; // Ẩn canvas
            captureButton.textContent = 'Chụp hình';
            captureButton.className = 'btn btn-danger mt-3';
            isCapturing = false;
        }
    });
});

