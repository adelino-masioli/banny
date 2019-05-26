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

        #preview {
            display: block;
            width: 354px;
            height: 354px;
            object-fit: fill;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{asset('pwa/instascan.min.js')}}"></script>
</head>

<body>
    <section>
        <div class="content">
            <h1> BANNY</h1>
            <video width="354" height="354" id="preview"></video>
        </div>
    </section>
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



<script>
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
      scanner.addListener('scan', function (content) {
        alert(content);
      });
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[0]);
        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });
</script>

</html>