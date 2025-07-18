<!DOCTYPE html>
<html>

<head>
    <title>Training Summary</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 12px;
        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .logo {

            height: 30px;
            margin-bottom: 10px;
        }

        .header-text {
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-align: center;
        }

        .event-number {
            font-size: 14px;
        }

        .content {
            width: 100%;
        }

        /* Flexbox styling untuk summary section */
        .summary-section {
            margin-bottom: 20px;
            border-collapse: collapse;
            text-align: left;
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

        table {
            width: 100%;
            border-collapse: collapse;
            /* Menghilangkan garis antar sel */
        }

        td,
        th {
            text-align: left;
            /* Mengatur teks agar berada di kiri */
            padding: 8px;
            /* Menambahkan padding untuk spasi antar teks */
            border: none;
            /* Menghilangkan garis tabel */
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

        .event-number {
            text-align: center font-size: 14px;
            /* Ukuran teks event number */
        }

        .footer {
            margin-top: 20px;
            text-align: left;
            font-size: 12px;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/icon.png') }}" class="logo">
        <h1>Summary Training Record</h1>
        <div class="event-number" style="text-align: center; font-size: 14px;">
            Event Number : TR-{{ $no }}
        </div>

    </div>

    <div class="content">
        <!-- Summary Section tanpa table, dengan teks justify -->
        <table class="summary-section">
            <tr>
                <td width="50%">
                    <strong>Training Name:</strong> {{ $training_name }} <br>
                    <strong>Station:</strong> {{ $station }} <br>
                    <strong>Training Category:</strong> {{ $training_category }} <br>
                    <strong>Rev:</strong> {{ $rev }} <br>
                </td>
                <td width="50%">
                    <strong>Trainer Name:</strong> {{ $trainer_name }} <br>
                    <strong>Training Date:</strong> {{ $date_range }}<br>
                    <strong>Doc Ref:</strong> {{ $doc_ref }} <br>
                    <strong>Training Duration:</strong> {{ $training_duration }} Minute<br>
                </td>
            </tr>

            <tr>
                <td width="50%">
                    <strong>Skill Code:</strong>
                    @foreach ($skills as $skill)
                        <BR> {{ $skill['skill_code'] }}
                    @endforeach

                </td>
                <td width="50%">
                    <strong>Job Skill:</strong>
                    @foreach ($skills as $skill)
                        <BR> {{ $skill['job_skill'] }}
                    @endforeach
                </td>
            </tr>
        </table>

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
    <div class="footer">
        <p style="text-align: left; font-size: 12px; margin-top: 20px;">
        History Approval - Requestor = {{ $requestor }}
            <table style="width: 50%; margin-top: 10px; border: 1px solid #ccc; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr>
                    <th style="text-align: center;">No</th>
                    <th style="text-align: center;">Approval</th>
                    <th style="text-align: center;">Comment</th>
                    <th style="text-align: center;">Date</th>
                </tr>
            </thead>
            @php $no = 0; @endphp
            @foreach ($history as $index => $row)
            
            <tr>
                <td style="text-align: center; font-size: 12px;">
                    {{ ++$no }}
                </td>
                <td style="text-align: center; font-size: 12px;">
                    {{ $row['approval'] }}
                </td>
                <td style="text-align: center; font-size: 12px;">
                    {{ $row['comment'] ?? '-' }}
                </td>
                <td style="text-align: center; font-size: 12px;">
                {{ \Carbon\Carbon::parse($row['updated_at'])->format('d M Y, H.i') }}
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</body>

</html>