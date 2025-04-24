<?php

namespace App\Imports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use DateTime;

class MasterDataImport implements ToModel, WithStartRow
{

    public function startRow(): int
    {
        return 2; // Mulai dari baris ke-2 (baris pertama adalah header)
    }    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    protected $userId;

    public function collection(Collection $rows)
{
    foreach ($rows as $index => $row) {
        Log::info("Baris ke-{$index}", $row->toArray());
    }
}
    public function __construct($userId)
    {
        $this->userId = $userId;
    }
    public function model(array $row)
{
    // Cek apakah badge_no sudah ada di database
    $peserta = Peserta::where('badge_no', $row[0])->first();

    if ($peserta) {
        // Jika sudah ada, jangan tambahkan
        Log::info("Peserta dengan badge_no {$row[0]} sudah ada. Data di-skip.");
        return null;
    }
    

    // Konversi join_date
    $formattedDate = $this->formatJoinDate($row[2]);

    if (!$formattedDate) {
        Log::warning("Tanggal join_date tidak valid untuk badge_no {$row[0]}: {$row[4]}");
        return null; // Jika tanggal tidak valid, abaikan data ini
    }
    // Buat instance Peserta
    return new Peserta([
        'badge_no' => $row[0],
        'employee_name' => $row[1],
        'dept' => $row[3],
        'position' => $row[4],
        'join_date' => $formattedDate,
        'category_level' => $row[5],
        'status' => 'Active',
        'gender' => $row[6],
        'user_id' => $this->userId, // Ambil user_id dari auth
    ]);
}

/**
 * Fungsi untuk memformat tanggal join_date.
 *
 * @param mixed $date
 * @return string|null
 */
private function formatJoinDate($date)
{
    try {
        if (is_numeric($date)) {
            // Jika tanggal dalam format Excel (serial number)
            $dateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date);
            return $dateObject->format('Y-m-d');
        } else {
            // Jika dalam format string (contoh: d/m/Y)
            $dateObject = DateTime::createFromFormat('d/m/Y', $date);
            if ($dateObject) {
                return $dateObject->format('Y-m-d');
            }
        }
    } catch (\Exception $e) {
        Log::error("Error parsing join_date: {$e->getMessage()}");
        return null;
    }

    return null; // Jika semua upaya gagal
}

}
