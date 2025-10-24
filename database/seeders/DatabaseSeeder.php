<?php

use UsersTableSeeder;
use Haryadi\Core\Database;
use Haryadi\Core\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->info('Memulai seeding database...');
        
        // Panggil seeder lainnya
        $this->call(UsersTableSeeder::class);
        // $this->call(PostsTableSeeder::class);
        // $this->call(CategoriesTableSeeder::class);
        
        $this->success('Database seeding selesai!');
    }
}
