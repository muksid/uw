<?php

namespace App\Exports;

use App\UwJuridicalClient;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UwJuridicalClientsExport implements FromCollection, WithHeadings, WithColumnWidths, WithColumnFormatting, WithStyles
{
    use Exportable;

    protected $request;

    private $row = 0;

    function __construct($request) {
        $this->request = $request;
    }

    public function collection()
    {
        $mfo = $this->request['mfo'];
        $status = $this->request['status'];
        $date_s = $this->request['date_s'];
        $date_e = $this->request['date_e'];
        $text = $this->request['text'];
        $user = $this->request['user'];

        $search = UwJuridicalClient::select('uw_juridical_clients.id','uw_juridical_clients.branch_code','uw_juridical_clients.client_code',
            'uw_juridical_clients.claim_id','uw_juridical_clients.jur_name','uw_juridical_clients.inn','uw_juridical_clients.client_type',
            'uw_juridical_clients.summa','uw_status_names.name as status', 'uw_loan_types.title as loan_name','uw_juridical_clients.created_at')
            ->leftJoin('uw_status_names', function($join) {
                $join->on('uw_juridical_clients.status', '=', 'uw_status_names.code')
                    ->where('uw_status_names.type', '=', 'jur')
                    ->where('uw_status_names.user_type', '=', 'uw');
            })
            ->leftJoin('uw_loan_types', function($join) {
                $join->on('uw_juridical_clients.loan_type_id', '=', 'uw_loan_types.id');
            });

        if($mfo) {
            $search->where('uw_juridical_clients.branch_code', '=', $mfo);
        }

        if($status == 'INC') {
            $search->where('status', 2); // INCOME IN UNDERWRITER (NEW APP)
        } elseif ($status == 'CON') {
            $search->where('status', 3); // CONFIRM FROM UNDERWRITER
        } elseif ($status == 'EDIT') {
            $search->where('status', 0); // EDIT IN INSPECTOR
        } elseif ($status == 'NEW') {
            $search->where('status', 1); // NEW APP IN INSPECTOR
        } elseif ($status == 'PAS') {
            $search->where('status', -1); // PASSIVE APP
        } elseif ($status == 'DEL') {
            $search->where('status', -2); // DELETED APP
        }

        if($user) {
            $search->where('work_user_id', $user);
        }

        if($text) {
            $search->where(function ($query) use ($text) {

                $query->orWhere('client_code', 'LIKE', '%' . $text . '%');
                $query->orWhere('claim_id', 'LIKE', '%' . $text . '%');
                $query->orWhere('jur_name', 'LIKE', '%' . $text . '%');
                $query->orWhere('inn', 'LIKE', '%' . $text . '%');
                $query->orWhere('summa', 'LIKE', '%' . $text . '%');
            });
        }

        if($date_s) {
            $search->whereBetween('uw_juridical_clients.created_at', [$date_s.' 00:00:00',$date_e.' 23:59:59']);
        }

        return $search->get();
    }

    public function columnWidths(): array
    {
        return [
            'B' => 10,
            'C' => 15,
            'D' => 15,
            'E' => 45,
            'F' => 12,
            'H' => 15,
            'I' => 15,
            'J' => 35,
            'L' => 20,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER,
        ];
    }
    public function styles(Worksheet $sheet)
    {

        return [
            // Style the first row as bold text.
            1    => [
                'font' => [
                    'bold' => true,
                    'size' => 18,
                    'text-align' => 'center',
                ],
                'columns' => $sheet->mergeCells('A1:L1')],

            2    => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        $date = Carbon::now()->format('d.m.Y H:i');
        return[
            ['Yuridik mijozlar ro`yhati '.$date],
            [
                "#",
                "BANK MFO",
                "CODE CLIENT",
                "ARIZA RAQAMI",
                "MIJOZ NOMI",
                "STIR",
                "MIJOZ TURI",
                "KREDIT SUMMASI",
                "KREDIT HOLATI",
                "KREDIT TURI",
                "ARIZA SANASI",
            ]
        ];
    }
}
