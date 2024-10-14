<!DOCTYPE html>
<html>

<head>
    <title>Training Summary</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 12px;
            /* Ukuran font kecil */
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            margin-top: 20px;
        }

        /* Flexbox styling untuk summary section */
        .summary-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            /* Membuat kolom kiri dan kanan */
            margin-bottom: 20px;
        }

        .summary-item {
            width: 48%;
            /* Lebar 48% untuk tiap item agar berada di kiri dan kanan */
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-item strong {
            text-align: left;
            width: 50%;
            /* Menjaga jarak konten kiri */
        }

        .summary-item span {
            text-align: right;
            width: 50%;
            /* Konten kanan berada di kanan */
        }

        /* Styling untuk tabel participants */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Summary Training Record</h1>
    </div>

    <div class="content">
        <!-- Summary Section tanpa table, dengan teks justify -->
        <div class="summary-section">
            <!-- Kolom Kiri (Training Name, Job Skill, Station, dll.) -->
            <div class="summary-item">
                <strong>Training Name:</strong>
                <span>{{ $training_name }}</span>
            </div>
            <div class="summary-item">
                <strong>Job Skill:</strong>
                <span>{{ $job_skill }}</span>
            </div>
            <div class="summary-item">
                <strong>Station:</strong>
                <span>{{ $station }}</span>
            </div>
            <div class="summary-item">
                <strong>Skill Code:</strong>
                <span>{{ $skill_code }}</span>
            </div>

            <!-- Kolom Kanan (Rev, Trainer Name, Training Date, dll.) -->
            <div class="summary-item">
                <strong>Rev:</strong>
                <span>{{ $rev }}</span>
            </div>
            <div class="summary-item">
                <strong>Trainer Name:</strong>
                <span>{{ $trainer_name }}</span>
            </div>
            <div class="summary-item">
                <strong>Training Date:</strong>
                <span>{{ $training_date }}</span>
            </div>
            <div class="summary-item">
                <strong>Doc Ref:</strong>
                <span>{{ $doc_ref }}</span>
            </div>
        </div>

        <!-- Table untuk Participants -->
        <table>
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Badge No</th>
                    <th>Dept</th>
                    <th>Position</th>
                    <th>Theory Result</th>
                    <th>Practical Result</th>
                    <th>Level</th>
                    <th>Final Judgement</th>
                    <th>License</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participants as $participant)
                    <tr>
                        <td>{{ $participant['employee_name'] }}</td>
                        <td>{{ $participant['badge_no'] }}</td>
                        <td>{{ $participant['dept'] }}</td>
                        <td>{{ $participant['position'] }}</td>
                        <td>{{ $participant['theory_result'] }}</td>
                        <td>{{ $participant['practical_result'] }}</td>
                        <td>{{ $participant['level'] }}</td>
                        <td>{{ $participant['final_judgement'] }}</td>
                        <td>
                            <input type="checkbox" {{ $participant['license'] == 1 ? 'checked' : '' }} disabled>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
