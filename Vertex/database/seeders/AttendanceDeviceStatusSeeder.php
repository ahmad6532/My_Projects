<?php

namespace Database\seeders;

use App\Models\AttendanceDeviceStatus;
use Illuminate\Database\Seeder;

class AttendanceDeviceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'ZK-Face',
            'ZK-Fingerprint',
            'ZK-Card',
            'ZK-Pin',
            'On-Premises-App-Face',
            'On-Premises-App-Fingerprint',
            'On-Premises-App-Pin',
            'Employee-App-Face',
            'Employee-App-Fingerprint',
            'Employee-App-Pin',
            'Manually',
        ];

        foreach ($statuses as $status) {
            AttendanceDeviceStatus::create([
                'device_status' => $status,
            ]);

        }
    }
}
