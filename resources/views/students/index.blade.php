<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lounge</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('students/assets/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
        
</head>

<body class="vh-100 d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Information Structure 1 -->
            <div class="col-12 information-structure-1 text-center mb-4">
                <!-- Vui lòng kiểm tra micro, tai nghe/loa trước khi bấm nhận đề -->
                <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <h5>Sự tự tin, lòng quyết tâm sẽ giúp bạn chiến thắng, University of Greenwich đang đợi bạn!
                            </h5>
                        </div>
                        <div class="carousel-item">
                            <h5>Có B2 rồi mình đi lai rai</h5>
                        </div>
                        <div class="carousel-item">
                            <h5>Thà nhịn đói chứ không nhịn nói, nhịn nói không đạt B2</h5>
                        </div>
                        <div class="carousel-item">
                            <h5>Học hết mình, ôn nhiệt tình... cái đạt B2</h5>
                        </div>
                        <div class="carousel-item">
                            <h5>Vui lòng kiểm tra micro, tai nghe/loa trước khi bấm nhận đề.</h5>
                        </div>
                        <div class="carousel-item">
                            <h5>Vui lòng cấp quyền sử dụng micro cho trình duyệt trước khi thi.</h5>
                        </div>
                        <div class="carousel-item">
                            <h5>Bài thi theo đúng thứ tự 04 kỹ năng: Nghe - Đọc - Viết - Nói.</h5>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <!-- Information Structure 2 -->
            <div class="col-12 information-structure-2">
                <div class="container" id="sub-container">
                    <div class="row justify-content-center">
                        <!-- Column for Image and Button -->
                        <div class="col-md-6 text-center ">
                            <div class="camera">
                                <canvas id="canvas" width="250" height="170" style="display:none;"></canvas>
                                <video id="video" width="250" height="170" autoplay></video>
                            </div>
                            <!-- Capture button -->
                            <button id="capture" class="btn btn-danger mt-3">Chụp hình</button>
                        </div>
                        <!-- Column for User Information -->
                        <div class="col-md-6">
                            <div class="infor">Họ tên: <strong>{{ $user_name }}</strong></div>
                            <div class="infor">Email: <strong>{{ $user_email }}</strong></div>
                            <div class="infor">UserID: <strong>{{ $user_id_student }}</strong></div>
                            <div class="infor">Định dạng thi: <strong>Bậc 3 - 5</strong></div>
                            <div class="infor">Ngày thi: <strong>{{ date('d/m/Y') }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center justify-text">
            <!-- Test Structure Column -->
            <div class="col-md-4">
                <div class="test-structure text-white p-3 ">
                    <div class="skills">
                        <div class="card d-flex align-items-center justify-content-center"> <!-- Sử dụng d-flex để bật Flexbox -->
                            <div class="icon"><i class="bi bi-1-square-fill"></i></div>
                            <div class="text"><h2>CẤU TRÚC BÀI THI</h2></div>
                        </div>
                    </div>
                    <p class="skills mt-3">Kỹ năng số 1: NGHE – 3 phần (47 phút)</p>
                    <p class="skills mt-1">Kỹ năng số 2: ĐỌC – 4 phần (60 phút)</p>
                    <p class="skills mt-1">Kỹ năng số 3: VIẾT – 2 phần (60 phút)</p>
                    <p class="skills mt-1">Kỹ năng số 4: NÓI – 3 phần (12 phút)</p>
                </div>
            </div>
            <!-- Audio Check Column -->
            <div class="col-md-4">
                <div class="audio-check text-white p-3 justify-text">
                    <div class="card d-flex align-items-center justify-content-center"> <!-- Sử dụng d-flex để bật Flexbox -->
                        <div class="icon"><i class="bi bi-2-square-fill"></i></div>
                        <div class="text"><h2>KIỂM TRA ÂM THANH</h2></div>
                    </div>
                    <p><strong>Bước 1:</strong> Mở loa và đeo tai nghe để nghe một đoạn audio bên dưới</p>
                    <audio controls class="mb-3">
                        <source src="test-audio.mp3" type="audio/mp3">
                        Your browser does not support the audio tag.
                    </audio>
                    <p> <strong>Bước 2:</strong> Để mic thu âm sát miệng.</p>
                    <p> <strong>Bước 3:</strong> Nhấp vào nút "Thu âm" để bắt đầu thu âm. Nếu không nghe được giọng nói
                        của mình kiểm tra lại cài đặt hoặc thiết bị.</p>
                    <audio controls class="mb-3">
                        <source src="test-audio.mp3" type="audio/mp3">
                        Your browser does not support the audio tag.
                    </audio>
                    <div class="buttons justify-content-center d-flex mb-3">
                        <button type="button" class="btn btn-danger mt-3 m-1">Thu âm</button>
                        <button type="button" class="btn btn-secondary mt-3 m-1">Nghe lại</button>
                    </div>
                    <p><strong>Bước 4:</strong> Nếu thi sinh không nghe được giọng nói của mình, vui lòng thông báo ngay
                        cho trợ giảng phụ trách phòng thi.</p>
                </div>
            </div>
            <!-- Notes Column -->
            <div class="col-md-4">
                <div class="notes text-white p-3 justify-text">
                    <div class="card d-flex align-items-center justify-content-center"> <!-- Sử dụng d-flex để bật Flexbox -->
                        <div class="icon"><i class="bi bi-3-square-fill"></i></div>
                        <div class="text"><h2>LƯU Ý</h2></div>
                    </div>
                    <p>Khi hết thời gian của từng kỹ năng, hệ thống sẽ tự động chuyển sang kỹ năng tiếp theo. Thí sinh
                        không thể thao tác được với kỹ năng đã làm trước đó.</p>
                    <p>Thí sinh phải nhấp vào nút <strong>"LƯU BÀI</strong> sau khi hoàn thành mỗi phần thi</p>
                    <p>Để chuyển part hay kỹ năng thí sinh click vào nút <strong>"TIẾP TỤC"</strong></p>
                    <div class="underline"></div>
                    <div class="btn-group d-flex justify-content-lg-between">
                        <button type="button" class="btn btn-success mt-3" style="background-color: #27cd18;">NHẬN ĐỀ</button>    
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary mt-3" style=>ĐĂNG XUẤT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
        <script>
            // Tạo biến global để chứa dữ liệu từ session
            window.userData = {
                userName: @json(session('user_name')),
                userEmail: @json(session('user_email')),
                accountId: @json(session('account_id'))
            };
        </script>
    <script src="{{ asset('students/assets/js/camera.js') }}"></script>
</body>

</html>