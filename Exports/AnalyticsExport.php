<?php

namespace Modules\Demowebinar\Exports;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;


class AnalyticsExport implements WithHeadings, FromCollection, WithMapping
{
    protected $registrants;

    public function __construct($registrants)
    {
        $this->registrants = $registrants;
    }

    public function collection()
    {
        return $this->registrants;
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Registration Date',
            'IP',
            'Schedule Date',
            'Entered Live Room',
            'Live Time',
            'Is Late Attendee',
            'GDPR Status',
            'Future Communications',
        ];
    }
    public function map($registrants): array
    {
        return [
            $registrants->first_name,
            $registrants->last_name,
            $registrants->email,
            $registrants->phone,
            $registrants->registration_date,
            $registrants->registrantAnalytics ? $registrants->registrantAnalytics->IP : null,
            $registrants->start_time,
            $registrants->entered_live_room,
            $registrants->registrantAnalytics ? $registrants->registrantAnalytics->time_in_live : null,
            $registrants->insertion_point != null ? 'Yes' : 'No',
            $registrants->gdpr,
            $registrants->future_communications,
        ];
    }
}
