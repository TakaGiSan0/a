<!DOCTYPE html>
<html>

<head>
    <title>Training Matrix</title>
    <style>
        body {
            font-family: Arial, sans-serif, "DejaVu Sans";
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            font-size: 10px;
        }

        td {
            border: 1px solid black;
        }

        .hide-border-right-bottom {
            border-right: none !important;
            border-bottom: none !important;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .footer-table td {
            padding: 8px;
            font-size: 10px;
            vertical-align: top;
            text-align: left;
        }

        .footer-table thead td {
            text-align: left;
        }

        .footer-table th,
        .footer-table td {
            border: none !important;
            text-align: left;
            /* Opsional, sesuai dengan tampilan di gambar */
        }

        .approval-table {
            width: 40%;
            /* Ubah sesuai kebutuhan untuk mengecilkan tabel */
            border-collapse: collapse;
            text-align: center;
            float: right;
            /* Menempatkan tabel ke kanan */
            margin-right: 20px;
            /* Beri sedikit jarak dari tepi kanan */
        }

        .approval-table th,
        .approval-table td {
            border: 1px solid black;
            padding: 5px;
            /* Kurangi padding agar tabel lebih kecil */
            font-size: 14px;
            /* Sesuaikan ukuran font */
        }

        .approval-table .header {
            font-weight: bold;
        }

        .approval-table .sub-header {
            font-size: 12px;
            /* Buat lebih kecil dibanding header */
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Training Matrix Report</h2>
    @if(!empty($deptList))
        <h4 class="subtitle">Departemen: {{ strtoupper($deptList) }}</h4>
    @else
        <h4 class="subtitle">Departemen:</h4>
    @endif
    <table class="table-main">
        <thead>
            <tr>
                <th rowspan="2" class="px-4 py-4 border border-gray-300">Badge No</th>
                <th rowspan="2" class="px-4 py-4 border border-gray-300">Emp Name</th>
                <th rowspan="2" class="px-4 py-4 border border-gray-300">Date of Join</th>
                <th rowspan="2" class="px-4 py-4 border border-gray-300">Dept</th>
                <th colspan="{{ count($masterStations) }}" class="px-4 py-2 text-center border border-gray-300">Station
                </th>
                <th colspan="{{ count($masterSkills) }}" class="px-4 py-2 text-center border border-gray-300">Skill
                    Code</th>
            </tr>
            <tr>
                @foreach($masterStations as $station)
                    <th class="px-4 py-2 border border-gray-300">{{ $station }}</th>
                @endforeach
                @foreach($masterSkills as $skill)
                    <th class="px-4 py-2 border border-gray-300">{{ $skill->skill_code }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($results as $row)
                <tr>
                    <td>{{ $row['badge_no'] }}</td>
                    <td>{{ $row['employee_name'] }}</td>
                    <td>{{ $row['join_date'] }}</td>
                    <td>{{ $row['dept'] }}</td>
                    @foreach($masterStations as $station)
                        <td>{{ $row['stations'][$station] ?? '-' }}</td>
                    @endforeach
                    @foreach ($masterSkills as $skillCode => $skillModel)
                        <td class="text-center">
                            {{-- Ambil nilai ✓ atau - dari array 'skills' di dalam $row --}}
                            {{ $row['skills'][$skillCode] ?? '-' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="px-4 py-3 text-center border border-gray-300">Supply</strong></td>
                @foreach($supplyRow as $supply)
                    <td class="px-4 py-2 text-center border border-gray-300">{{ $supply }}</td>
                @endforeach
                @if(count($masterSkills) > 0)
                    <td rowspan="2" colspan="{{ count($masterSkills) }}" class="hide-border-right-bottom"></td>
                @endif

            </tr>
            <tr>
                <td colspan="4"><strong>Gap</strong></td>
                @foreach($gapRow as $gap)
                    <td>{{ $gap }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
    <table class="footer-table">
        @php
            $chunks = $masterSkills->values()->chunk(5);
            $chunksn = $product->values()->chunk(5);

        @endphp

        <thead>

            <tr>
                <td>LEGEND SKILL CODE :</td>
                @foreach ($chunksn as $chunka)
                    <td>
                        @foreach ($chunka as $product)
                            {{ $product->product_code }} : {{ $product->product_name }}<br>
                        @endforeach
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>SKILL LEVEL :</td>
                <td>1 = LEVEL 1 (work under supervision)<br>2 = LEVEL 2 (work according to standards)<br>3 = Level 3
                    (expert)<br>4 = Level 4 (expert & trainer)</td>
            </tr>
        </thead>


        <tbody>

            <tr>
                <td>LEGEND SKILL CODE :</td>
                @foreach ($chunks as $chunk)
                    <td>
                        @foreach ($chunk as $skill)
                            {{ $skill->skill_code }} : {{ $skill->job_skill }}<br>
                        @endforeach
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>SKILL LEVEL :</td>
                <td>1 = LEVEL 1 (work under supervision)<br>2 = LEVEL 2 (work according to standards)<br>3 = Level 3
                    (expert)<br>4 = Level 4 (expert & trainer)</td>
            </tr>
        </tbody>
    </table>
    <table class="approval-table">
        <tr>
            <th class="header" rowspan="2">Prepared by</th>
            <th colspan="2" class="header">Acknowledged by</th>
            <th class="header" rowspan="2">Verified by (HR Dept)</th>
        </tr>
        <tr>
            <td class="sub-header">Dept Supervisor</td>
            <td class="sub-header">Dept Manager</td>
        </tr>
        <tr>
            <td style="height: 50px;"></td>
            <td style="height: 50px;"></td>
            <td style="height: 50px;"></td>
            <td style="height: 50px;"></td>
        </tr>
        <tr>
            <td style="height: 10px;"></td>
            <td style="height: 10px;"></td>
            <td style="height: 10px;"></td>
            <td style="height: 10px;"></td>
        </tr>
    </table>
</body>

</html>