<?php
// database/seeders/UsersTableSeeder.php

use Haryadi\Core\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->info('Seeding users...');
        
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@haryadi.com',
                'password' => password_hash('super123', PASSWORD_DEFAULT),
                'role' => 'superadmin',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Administrator', 
                'email' => 'admin@haryadi.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'User Demo',
                'email' => 'user@demo.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT), 
                'role' => 'user',
                'email_verified_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ] // PERBAIKAN: Ini harusnya ] bukan }
        ];
        
        foreach ($users as $user) {
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) 
                VALUES (:name, :email, :password, :role, :email_verified_at, :created_at, :updated_at)
            ");
            $stmt->execute($user);
        }
        
        $this->success('Berhasil menambahkan ' . count($users) . ' users!');
    }
}