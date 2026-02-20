<!DOCTYPE html>
<html>
<head><title>Nouveau Message</title></head>
<body>
    <h2>Nouveau message de : {{ $data['email'] }}</h2>
    <p><strong>Sujet :</strong> Support Technique / Contact</p>
    <hr>
    <p>{{ $data['message'] }}</p>
</body>
</html>