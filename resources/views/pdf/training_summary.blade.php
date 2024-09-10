<!DOCTYPE html>
<html>
<head>
    <title>Training Summary</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; }
        .content { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Training Summary</h1>
        <p>Training Name: {{ $training_name }}</p>
        <p>Trainer Name: {{ $trainer_name }}</p>
        <p>Document Reference: {{ $doc_ref }}</p>
    </div>
    <div class="content">
        <h3>Participants</h3>
        <ul>
            @foreach($participants as $participant)
                <li>{{ $participant->employee_name }} - {{ $participant->position }}</li>
            @endforeach
        </ul>
    </div>
</body>
</html>
