<?php

namespace Database\Seeders;

use App\Models\{User, Equipment, Borrowing};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name'              => 'Irfan Hakim',
            'email'             => 'admin@ukm.edu',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'student_id'        => 'ADMIN001',
            'reliability_score' => 100,
            'membership_level'  => 'gold',
        ]);

        // Admin Supervisor
        User::create([
            'name'              => 'Admin Supervisor',
            'email'             => 'supervisor@ukm.edu',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'student_id'        => 'ADMIN002',
            'reliability_score' => 100,
            'membership_level'  => 'gold',
        ]);

        // Member users
        $members = [
            ['name' => 'Budi Santoso',    'email' => 'budi@ukm.edu',    'student_id' => 'M10423', 'score' => 98, 'level' => 'gold'],
            ['name' => 'Siti Aminah',     'email' => 'siti@ukm.edu',     'student_id' => 'M10501', 'score' => 92, 'level' => 'silver'],
            ['name' => 'Ahmad Daniel',    'email' => 'ahmad@ukm.edu',    'student_id' => 'M10389', 'score' => 85, 'level' => 'silver'],
            ['name' => 'Farah Wahida',    'email' => 'farah@ukm.edu',    'student_id' => 'M10621', 'score' => 70, 'level' => 'bronze'],
            ['name' => 'Lim Wei Shen',    'email' => 'lim@ukm.edu',      'student_id' => 'M10458', 'score' => 95, 'level' => 'gold'],
            ['name' => 'Jessica Wong',    'email' => 'jessica@ukm.edu',  'student_id' => 'M10512', 'score' => 60, 'level' => 'bronze'],
            ['name' => 'Ahmad Zaki',      'email' => 'zaki@ukm.edu',     'student_id' => 'M10423', 'score' => 88, 'level' => 'silver'],
            ['name' => 'Nora Abdullah',   'email' => 'nora@ukm.edu',     'student_id' => 'M10112', 'score' => 91, 'level' => 'silver'],
        ];

        foreach ($members as $m) {
            User::create([
                'name'              => $m['name'],
                'email'             => $m['email'],
                'password'          => Hash::make('password'),
                'role'              => 'member',
                'student_id'        => $m['student_id'],
                'reliability_score' => $m['score'],
                'membership_level'  => $m['level'],
            ]);
        }

        // Equipment
        $equipmentList = [
            ['name'=>'Canon EOS R5',         'category'=>'camera',   'condition'=>'excellent', 'status'=>'available',  'serial'=>'UKM-FIN-2024001'],
            ['name'=>'Sony Alpha A7 III',     'category'=>'camera',   'condition'=>'good',      'status'=>'borrowed',   'serial'=>'UKM-FIN-2024002'],
            ['name'=>'Fujifilm X-T4',         'category'=>'camera',   'condition'=>'excellent', 'status'=>'available',  'serial'=>'UKM-FIN-2024003'],
            ['name'=>'Nikon Z6 II',           'category'=>'camera',   'condition'=>'good',      'status'=>'available',  'serial'=>'UKM-FIN-2024004'],
            ['name'=>'Canon EOS 80D',         'category'=>'camera',   'condition'=>'good',      'status'=>'available',  'serial'=>'UKM-FIN-2024005'],
            ['name'=>'Nikon D850',            'category'=>'camera',   'condition'=>'fair',      'status'=>'borrowed',   'serial'=>'UKM-FIN-2024006'],
            ['name'=>'Sony A7R IV Mirrorless','category'=>'camera',   'condition'=>'excellent', 'status'=>'available',  'serial'=>'UKM-FIN-2024007'],
            ['name'=>'Sigma 24-70mm f/2.8',   'category'=>'lens',     'condition'=>'excellent', 'status'=>'available',  'serial'=>'UKM-FIN-2024008'],
            ['name'=>'Canon RF 50mm f/1.2L',  'category'=>'lens',     'condition'=>'excellent', 'status'=>'available',  'serial'=>'UKM-FIN-2024009'],
            ['name'=>'Sony FE 24-70mm f/2.8 GM','category'=>'lens',   'condition'=>'good',      'status'=>'borrowed',   'serial'=>'UKM-FIN-2024010'],
            ['name'=>'Manfrotto 055 Tripod',  'category'=>'tripod',   'condition'=>'good',      'status'=>'available',  'serial'=>'UKM-FIN-2024011'],
            ['name'=>'Manfrotto BeFree',      'category'=>'tripod',   'condition'=>'good',      'status'=>'available',  'serial'=>'UKM-FIN-2024012'],
            ['name'=>'DJI Ronin RS3',         'category'=>'tripod',   'condition'=>'excellent', 'status'=>'available',  'serial'=>'UKM-FIN-2024013'],
            ['name'=>'Godox SK400II Kit',     'category'=>'lighting', 'condition'=>'fair',      'status'=>'borrowed',   'serial'=>'UKM-FIN-2024014'],
            ['name'=>'Godox SL60W',           'category'=>'lighting', 'condition'=>'good',      'status'=>'available',  'serial'=>'UKM-FIN-2024015'],
            ['name'=>'Aputure 600d Pro',      'category'=>'lighting', 'condition'=>'excellent', 'status'=>'available',  'serial'=>'UKM-FIN-2024016'],
            ['name'=>'Profoto B10 Duo Kit',   'category'=>'lighting', 'condition'=>'excellent', 'status'=>'available',  'serial'=>'UKM-FIN-2024017'],
            ['name'=>'DJI RS 3 Pro Gimbal',   'category'=>'accessory','condition'=>'excellent', 'status'=>'available',  'serial'=>'UKM-FIN-2024018'],
        ];

        foreach ($equipmentList as $e) {
            Equipment::create([
                'name'          => $e['name'],
                'category'      => $e['category'],
                'condition'     => $e['condition'],
                'status'        => $e['status'],
                'serial_number' => $e['serial'],
            ]);
        }

        // Sample borrowing history
        $borrowingData = [
            ['user_id'=>3, 'equipment_id'=>1, 'borrow_date'=>'2024-03-01', 'return_date'=>'2024-03-05', 'actual_return_date'=>'2024-03-05', 'status'=>'returned', 'final_condition'=>'excellent', 'purpose'=>'Student Project for Photography Class'],
            ['user_id'=>4, 'equipment_id'=>8, 'borrow_date'=>'2024-03-10', 'return_date'=>'2024-03-12', 'actual_return_date'=>'2024-03-12', 'status'=>'returned', 'final_condition'=>'good', 'purpose'=>'Workshop documentation'],
            ['user_id'=>5, 'equipment_id'=>16,'borrow_date'=>'2024-03-15', 'return_date'=>'2024-03-17', 'actual_return_date'=>'2024-03-17', 'status'=>'returned', 'final_condition'=>'minor_scratches', 'purpose'=>'Personal portfolio shoot'],
            ['user_id'=>6, 'equipment_id'=>13,'borrow_date'=>'2024-03-20', 'return_date'=>'2024-03-22', 'actual_return_date'=>null, 'status'=>'overdue', 'final_condition'=>'needs_repair', 'purpose'=>'Event videography'],
            ['user_id'=>7, 'equipment_id'=>5, 'borrow_date'=>'2024-03-25', 'return_date'=>'2024-03-28', 'actual_return_date'=>'2024-03-28', 'status'=>'returned', 'final_condition'=>'excellent', 'purpose'=>'Campus event photography'],
            // Active borrowings for Budi (member)
            ['user_id'=>3, 'equipment_id'=>7, 'borrow_date'=>'2024-10-21', 'return_date'=>'2024-10-24', 'actual_return_date'=>null, 'status'=>'approved', 'final_condition'=>null, 'purpose'=>'Commercial shoot assignment'],
            ['user_id'=>3, 'equipment_id'=>10,'borrow_date'=>'2024-10-21', 'return_date'=>'2024-10-24', 'actual_return_date'=>null, 'status'=>'approved', 'final_condition'=>null, 'purpose'=>'Commercial shoot assignment - lens'],
            // Pending requests
            ['user_id'=>9, 'equipment_id'=>7, 'borrow_date'=>'2024-10-24', 'return_date'=>'2024-10-27', 'actual_return_date'=>null, 'status'=>'pending', 'final_condition'=>null, 'purpose'=>'UKM Annual Photography Exhibition'],
            ['user_id'=>8, 'equipment_id'=>14,'borrow_date'=>'2024-10-28', 'return_date'=>'2024-10-30', 'actual_return_date'=>null, 'status'=>'pending', 'final_condition'=>null, 'purpose'=>'Studio lighting workshop event'],
            ['user_id'=>10,'equipment_id'=>11,'borrow_date'=>'2024-10-29', 'return_date'=>'2024-11-01', 'actual_return_date'=>null, 'status'=>'pending', 'final_condition'=>null, 'purpose'=>'Outdoor landscape photography trip'],
        ];

        $admin = User::where('role','admin')->first();
        foreach ($borrowingData as $b) {
            Borrowing::create(array_merge($b, [
                'approved_by' => in_array($b['status'], ['returned','approved']) ? $admin->id : null,
            ]));
        }
    }
}