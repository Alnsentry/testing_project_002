<?php

namespace App\Exports;

use App\Models\MessageHistory;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use Intervention\Image\Facades\Image;


class HistoryExport implements FromQuery, WithTitle, WithHeadings, WithDrawings, WithStyles, ShouldAutoSize
{
    protected $nama;
    protected $daop;
    protected $provinsi;
    protected $jenis_laporan;
    protected $tanggal;
    protected $user_id;

    public function __construct($nama, $daop, $tanggal, $user_id, $provinsi, $jenis_laporan)
    {
        $this->nama = $nama;
        $this->daop = $daop;
        $this->provinsi = $provinsi;
        $this->jenis_laporan = $jenis_laporan;
        $this->tanggal = $tanggal;
        $this->user_id = $user_id;
    }

    public function query()
    {
        $query = MessageHistory::query();

        if ($this->user_id){
            $user_id = $this->user_id;
            $query->where('message_history.user_id', $user_id); // Specify the table for user_id
        }
    
        if ($this->nama) {
            $nama = $this->nama;
            $query->whereHas('user', function ($data) use ($nama) {
                $data->where('users.name', 'ilike', '%' . $nama . '%'); // Specify the table for name
            });
        }
    
        if ($this->provinsi) {
            $provinsi = $this->provinsi;
            $query->whereHas('daop.province', function ($data) use ($provinsi) {
                $data->where('daop.province.nama_provinsi', 'ilike', '%' . $provinsi . '%'); // Specify the table for nama_provinsi
            });
    
            $query->orWhereDoesntHave('daop.province');
        }
    
        if ($this->daop) {
            $daop = $this->daop;
            $query->WhereHas('daop', function ($data) use ($daop) {
                $data->where('daop.nama_daops', 'ilike', '%' . $daop . '%'); // Specify the table for nama_daops
            });
    
            $query->orWhereDoesntHave('daop');
        }
    
        if ($this->tanggal) {
            $tanggal = $this->tanggal;
            // $query->whereDate('message_history.created_at', $tanggal);
            [$start, $end] = explode(' - ', $tanggal);

            // Convert date strings to DateTime objects
            $startDate = \DateTime::createFromFormat('m/d h:i A', $start);
            $startDate->sub(new \DateInterval('PT7H'));
            $endDate = \DateTime::createFromFormat('m/d h:i A', $end);
            $endDate->sub(new \DateInterval('PT7H'));

            // Check if DateTime objects are created successfully
            if ($startDate && $endDate) {
                // Use the date range in your query
                $query->whereBetween('message_history.created_at', [$startDate, $endDate]);
            }
        }
    
        if($this->jenis_laporan) {
            $jenis_laporan = $this->jenis_laporan;
            $query->where('message_history.jenis_laporan', $jenis_laporan); // Specify the table for jenis_laporan
        }

        $query
        ->leftJoin('users', 'message_history.user_id', '=', 'users.id')
        ->leftJoin('daops', 'message_history.daops_id', '=', 'daops.id')
        ->leftJoin('message_history_foto', 'message_history_foto.message_history_id', '=', 'message_history.id')
        ->select([
            'message_history.id',
            'users.name AS name',
            DB::raw('COALESCE(daops.nama_daops, \'Semua Daops\') AS nama_daops'),
            'message_history.jenis_laporan',
            'message_history.pelaporan',
            'message_history.status',
            DB::raw('json_agg(message_history_foto.foto) AS foto'),
            'message_history.created_at',
        ])
        ->orderBy('message_history.id', 'DESC')
        ->groupBy('message_history.id', 'users.name', 'daops.nama_daops', 'message_history.jenis_laporan', 'message_history.pelaporan', 'message_history.status', 'message_history.created_at');

        return $query;
    }

    public function drawings()
    {
        $drawings = [];
        $client = new Client();
        $currentRow = 1; // Start from row 2
        $rowHeights = [];

        foreach ($this->query()->get() as $record) {
            if ($record->foto) {
                $images = json_decode($record->foto, true);

                $currentRow++;
                $x = 0;
                $secondImage = 0;

                foreach ($images as $imageUrl) {
                    if ($secondImage < 2) {
                        if (filter_var($imageUrl, FILTER_VALIDATE_URL) !== false) {
                            $response = $client->get($imageUrl);

                            // Compress and resize the image
                            $compressedImagePath = $this->compressAndResizeImage($response->getBody());

                            if ($compressedImagePath !== null) {
                                $drawing = new Drawing();
                                $drawing->setName('Image');
                                $drawing->setDescription('Image Description');
                                $drawing->setPath($compressedImagePath);

                                // Set the coordinates dynamically based on the current row
                                $drawing->setCoordinates('I' . $currentRow);

                                // Set the height and width here
                                $drawing->setHeight(90); // Set the desired height
                                $drawing->setWidth(90); // Set the desired width

                                // Set the offset to position the image within the cell
                                $drawing->setOffsetX($x); // Set the desired X offset
                                $drawing->setOffsetY(0); // Set the desired Y offset

                                // Add the drawing to the worksheet
                                $drawings[] = $drawing;
                                $x += 90; // Adjust for the next image

                                // Set the row height in the array
                                $rowHeights[$currentRow] = 90;
                            } else {
                                // Handle the case where the file is not found (e.g., log, skip, etc.)
                                \Log::warning('Skipped image due to file not found: ' . $imageUrl);
                            }
                        }

                        $secondImage++;
                    }
                }
            }
        }

        // Store row heights in the session to be used in the styles method
        session(['row_heights' => $rowHeights]);

        return $drawings;
    }

    protected function compressAndResizeImage($imageContent)
    {
        try {
            // Decode the image content
            $image = Image::make($imageContent);

            // Resize the image with an aspect ratio
            $image->resize(90, 90, function ($constraint) {
                $constraint->aspectRatio();
            });

            // Save the compressed image
            $compressedImagePath = tempnam(sys_get_temp_dir(), 'compressed_image_') . '.jpg';
            $image->save($compressedImagePath, 75); // 75 is the quality

            return $compressedImagePath;
        } catch (\Exception $e) {
            // Log or handle the exception
            \Log::error('Error compressing and resizing image: ' . $e->getMessage());
            return null; // Return null or handle the error as needed
        }
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER); // Vertically center content

        $rowHeights = session('row_heights', []); // Retrieve row heights from the session

        foreach ($rowHeights as $row => $height) {
            $sheet->getRowDimension($row)->setRowHeight($height);
        }
    }

    public function title(): string
    {
        return 'Histori Pesan';
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Daop',
            'Jenis Laporan',
            'Pelaporan',
            'Status',
            'Foto',
            'Created'
        ];
    }
}
