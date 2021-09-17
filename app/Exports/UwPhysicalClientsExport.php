<?php

namespace App\Exports;

use App\UwClients;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UwPhysicalClientsExport implements FromCollection, WithHeadings, WithColumnWidths, WithColumnFormatting, WithStyles
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

        $search = UwClients::select('uw_clients.id','uw_clients.branch_code','uw_clients.local_code',
            'uw_clients.iabs_num', 'uw_clients.claim_id',
            DB::raw('CONCAT(uw_clients.family_name," ",uw_clients.name," ",uw_clients.patronymic) AS full_name'),
            'uw_clients.summa', 'uw_status_names.name as status', 'uw_loan_types.title as loan_name','uw_clients.created_at')
            ->leftJoin('uw_status_names', function($join) {
                $join->on('uw_clients.status', '=', 'uw_status_names.code')
                    ->where('uw_status_names.type', '=', 'phy')
                    ->where('uw_status_names.user_type', '=', 'uw');
            })
            ->leftJoin('uw_loan_types', function($join) {
                $join->on('uw_clients.loan_type_id', '=', 'uw_loan_types.id');
            });

        if($mfo) {
            $search->where('uw_clients.branch_code', '=', $mfo);
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

                $query->orWhere('iabs_num', 'LIKE', '%' . $text . '%');
                $query->orWhere('claim_id', 'LIKE', '%' . $text . '%');
                $query->orWhereRaw("CONCAT(`uw_clients.family_name`, ' ', `uw_clients.name`,' ', `uw_clients.patronymic`) LIKE ?", ['%'.$text.'%']);
                $query->orWhere('pin', 'LIKE', '%' . $text . '%');
                $query->orWhere('summa', 'LIKE', '%' . $text . '%');
            });
        }

        if($date_s) {
            $search->whereBetween('uw_clients.created_at', [$date_s.' 00:00:00',$date_e.' 23:59:59']);
        }

        return $search->get();
    }

    public function columnWidths(): array
    {
        return [
            'B' => 10,
            'C' => 10,
            'D' => 10,
            'E' => 15,
            'F' => 45,
            'G' => 20,
            'H' => 20,
            'I' => 45,
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
            ['Jismoniy mijozlar ro`yhati '.$date],
            [
                "#",
                "MFO",
                "LOCAL",
                "CODE CLIENT",
                "ARIZA RAQAMI",
                "MIJOZ NOMI",
                "KREDIT SUMMASI",
                "KREDIT HOLATI",
                "KREDIT TURI",
                "ARIZA SANASI",
            ]
        ];
    }
}
