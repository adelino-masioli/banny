<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3387C7">
    <meta name="description" content="Banny PROMO">
    <link rel="icon" href="{{asset('pwa/icons/icon-128x128.png')}}">

    <title>BANNY</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{asset('pwa/manifest.json')}}">

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #3387C7;
            color: #fff;
            font-family: 'Roboto', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        section {
            display: flex;
            width: 100%;
            height: 100%;
            align-items: center;
            align-content: center;
            justify-content: center;
        }

        h1 {
            font-weight: 300;
            margin: 0 0 5px 0;
            font-size: 14px;
            text-align: center;
        }

        #qr-video {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <div>
        <span id="cam-has-camera"></span>
        <video muted playsinline id="qr-video"></video>
    </div>
    <div id="file-selector"></div>
</body>



<script>
    var base_service = "{{asset('pwa/service-worker.js')}}";
    // CODELAB: Register service worker.
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register(base_service)
        .then(function () {
            console.log('service worker registered');
        })
        .catch(function () {
            console.warn('service worker failed');
        });
    }
</script>



<script type="module">
    var url_1 = "{{asset('pwa/qr-scanner.min.js')}}";
    var url_2 = "{{asset('pwa/qr-scanner-worker.min.js')}}";

    import QrScanner from './pwa/qr-scanner.min.js';
    QrScanner.WORKER_PATH = './pwa/qr-scanner-worker.min.js';
    const video = document.getElementById('qr-video');
    const camHasCamera = document.getElementById('cam-has-camera');
    const camQrResult = document.getElementById('cam-qr-result');
    const camQrResultTimestamp = document.getElementById('cam-qr-result-timestamp');
    const fileSelector = document.getElementById('file-selector');
    const fileQrResult = document.getElementById('file-qr-result');
    function setResult(label, result) {
        label.textContent = result;
        camQrResultTimestamp.textContent = new Date().toString();
        label.style.color = 'teal';
        clearTimeout(label.highlightTimeout);
        label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
    }
    // ####### Web Cam Scanning #######
    //QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);
    const scanner = new QrScanner(video, result => setResult(camQrResult, result));
    scanner.start();
    scanner.setInversionMode('original');
    // ####### File Scanning #######
    fileSelector.addEventListener('change', event => {
        const file = fileSelector.files[0];
        if (!file) {
            return;
        }
        QrScanner.scanImage(file)
            .then(result => setResult(fileQrResult, result))
            .catch(e => setResult(fileQrResult, e || 'No QR code found.'));
    });
</script>

</html>