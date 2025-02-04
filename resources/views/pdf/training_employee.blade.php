<!DOCTYPE html>
<html>

<head>
    <title>Training Report for {{ $peserta->employee_name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
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
    <h1 style="text-align: center;">Training Report for {{ $peserta->employee_name }}</h1>
    <p><strong>Badge No:</strong> {{ $peserta->badge_no }}</p>
    <p><strong>Department:</strong> {{ $peserta->dept }}</p>
    <p><strong>Position:</strong> {{ $peserta->position }}</p>

    @php

        $all_categories = \App\Models\category::all();
    @endphp

    @if ($all_categories && $all_categories->count())
        @foreach ($all_categories as $category)
            <h2>@if ($category->name === 'Internal')
                Internal Training
            @elseif ($category->name === 'External')
                External Training
            @elseif ($category->name === 'Neo')
            New Employee Induction (NEO)
            @elseif ($category->name === 'Project')
                Project Training
            @elseif ($category->name === 'N/A')
                Unknown Training
            @else
                Unknown Training    
            @endif
            </h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Training Name</th>
                        <th>Trainer Name</th>
                        <th>Training Date</th>
                        <th>Level</th>
                        <th>Final Judgement</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Ambil records untuk peserta dalam kategori ini
                        $records = $grouped_records[$category->id] ?? collect(); // Gunakan `collect()` jika tidak ada records
                    @endphp

                    @if ($records->isNotEmpty())
                        @foreach ($records as $record)
                            <tr>
                                <td>{{ $record->training_name }}</td>
                                <td>{{ $record->trainer_name }}</td>
                                <td>{{ $record->formatted_date_range }}</td>
                                <td>{{ $record->pivot->level }}</td>
                                <td>{{ $record->pivot->final_judgement }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No training records found in this category.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
    @else
        <p>No categories found.</p>
    @endif
</body>

</html>
