<?php

namespace App\Livewire;

use App\Models\Registrasi;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DataRegistrasi extends Component
{
    use WithPagination;

    public $search = '';
    public $paginate = 25;
    protected $paginationTheme = 'tailwind';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <!-- Loading spinner... -->
           <div class="flex flex-col items-center space-y-4 mt-10">
                        <svg class="animate-spin h-12 w-12 text-secondary-blue mt-1/2" viewBox="0 0 50 50">
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4" opacity="0.25" />
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 94.2" />
                        </svg>
                    </div>
        </div>
        HTML;
    }

    public function exportToExcel()
    {
        $query = Registrasi::with('santri');

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('santri', function ($sq) {
                    $sq->where('nama', 'ILIKE', "%{$this->search}%")
                       ->orWhere('nis', 'ILIKE', "%{$this->search}%")
                       ->orWhere('id_santri', 'ILIKE', "%{$this->search}%");
                })->orWhere('kasir', 'ILIKE', "%{$this->search}%");
            });
        }

        $registrasis = $query->orderBy('tgl_bayar', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Santri');
        $sheet->setCellValue('C1', 'NIS');
        $sheet->setCellValue('D1', 'Lembaga');
        $sheet->setCellValue('E1', 'Kategori');
        $sheet->setCellValue('F1', 'Tanggal Bayar');
        $sheet->setCellValue('G1', 'Nominal');
        $sheet->setCellValue('H1', 'Penerima / Kasir');

        // Data
        $row = 2;
        foreach ($registrasis as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->santri->nama ?? '-');
            $sheet->setCellValue('C' . $row, $item->santri->nis ?? '-');
            $sheet->setCellValue('D' . $row, $item->santri->lembaga ?? '-');
            $sheet->setCellValue('E' . $row, ($item->santri->ket ?? '') === 'baru' ? 'Baru' : 'Lanjutan');
            $sheet->setCellValue('F' . $row, $item->tgl_bayar ? $item->tgl_bayar->format('d-m-Y') : '-');
            $sheet->setCellValue('G' . $row, (int) $item->nominal);
            $sheet->setCellValue('H' . $row, $item->kasir);
            $row++;
        }

        // Style header bold
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Auto size columns
        foreach (range('A', 'H') as $colLetter) {
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'data_pembayaran_registrasi_' . now()->format('Y-m-d_His') . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="data.xlsx"',
        ]);
    }

    public function render()
    {
        $query = Registrasi::with('santri');

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('santri', function ($sq) {
                    $sq->where('nama', 'ILIKE', "%{$this->search}%")
                       ->orWhere('nis', 'ILIKE', "%{$this->search}%")
                       ->orWhere('id_santri', 'ILIKE', "%{$this->search}%");
                })->orWhere('kasir', 'ILIKE', "%{$this->search}%");
            });
        }

        $registrasis = $query->orderBy('tgl_bayar', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate($this->paginate);

        $totalRegistrasi = Registrasi::sum('nominal');

        return view('livewire.data-registrasi', [
            'registrasis' => $registrasis,
            'totalRegistrasi' => $totalRegistrasi,
        ]);
    }
}
