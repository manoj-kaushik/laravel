<?php

namespace Modules\Demowebinar\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Demowebinar\Entities\Master\WebinarTypes;
use Modules\Demowebinar\Entities\Master\Timezones;

class MasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->webinarTypeSeeder();
        $this->timezoneSeeder();
        $this->languageSeeder();
    }

    private function webinarTypeSeeder()
    {

        //Truncate table
        WebinarTypes::truncate();

        $webinarTypes = [
            ['slug' => 'auto', 'name' => 'Automated'],
            ['slug' => 'live', 'name' => 'Live'],
            ['slug' => 'series', 'name' => 'Series']
        ];

        //Insert Data
        WebinarTypes::insert($webinarTypes);
    }

    private function questionTypeSeeder()
    {

        //Truncate table
        QuestionTypes::truncate();

        $questionTypes = [
            ['slug' => 'one_answer', 'name' => 'Single option selection.'],
            ['slug' => 'multiple_answer', 'name' => 'Multiple option selection.'],
            ['slug' => 'short_answer', 'name' => 'Short answer'],
            ['slug' => 'long_answer', 'name' => 'Long answer']
        ];

        //Insert Data
        QuestionTypes::insert($questionTypes);
    }

    private function timezoneSeeder()
    {

        //Truncate table
        Timezones::truncate();

        $list = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL_WITH_BC);
        $timezoneArray = [
            'US/Pacific',
            'US/Mountain',
            'US/Central',
            'US/Eastern',
            'US/Arizona',
            'GMT',
            'Europe/London',
            'Australia/Sydney',
        ];

        $data = [];
        foreach ($timezoneArray as $tz) {
            $data[] = $this->getOffsetString($tz);
        }

        foreach ($list as $tz) {
            if (!in_array($tz, $timezoneArray)) {
                $data[] = $this->getOffsetString($tz);
            }
        }

        //Insert Data
        Timezones::insert($data);
    }
    
}
