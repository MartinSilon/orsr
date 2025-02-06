<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stiahnuť PDF</title>
</head>
<body>
<p>Ak sa sťahovanie nespustí automaticky, kliknite na tlačidlo nižšie.</p>

<a id="download-link" href="{{ $pdfFileUrl }}" download>
    <button>Stiahnuť PDF</button>
</a>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let link = document.getElementById('download-link');
        link.click();
    });


    setTimeout(function(){
        window.location.href = "/";
    }, 4000);
</script>
</body>
</html>


