<!DOCTYPE html>
<html>
<head>
    <title>Training Report for {{ $peserta->employee_name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Training Report for {{ $peserta->employee_name }}</h1>
    <p><strong>Badge No:</strong> {{ $peserta->badge_no }}</p>
    <p><strong>Department:</strong> {{ $peserta->dept }}</p>
    <p><strong>Position:</strong> {{ $peserta->position }}</p>

    <h2 style="text-align: center">Training Records</h2>

    @if($grouped_records && $grouped_records->count())
        @foreach($grouped_records as $category_id => $records)
            <h3>Category: {{ $records->first()->trainingCategory->name }}</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Training Name</th>
                        <th>Trainer Name</th>
                        <th>Training Date</th>
                        <th>Theory Result</th>
                        <th>Practical Result</th>
                        <th>Level</th>
                        <th>Final Judgement</th>
                        <th>License</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>{{ $record->training_name }}</td>
                            <td>{{ $record->trainer_name }}</td>
                            <td>{{ $record->training_date }}</td>
                            <td>{{ $record->pivot->theory_result }}</td>
                            <td>{{ $record->pivot->practical_result }}</td>
                            <td>{{ $record->pivot->level }}</td>
                            <td>{{ $record->pivot->final_judgement }}</td>
                            <td>{{ $record->pivot->license == 1 ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @else
        <p>No training records found for this participant.</p>
    @endif
</body>
</html>
