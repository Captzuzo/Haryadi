<?php
namespace App\Models;

use Haryadi\Core\Model;

class User extends Model
{
    protected string $table = 'users';
    
    protected array $fillable = [
        // Tambahkan field yang bisa diisi di sini
        'name',
        'email', 
        'password',
        'role'
    ];

    protected array $hidden = [
        'password'
    ];

    public function setPasswordAttribute($password): void
    {
        if (!empty($password)) {
            $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
    }

    /**
     * Verify password
     */
    public function verifyPassword($password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Cari user by email
     */
    public static function findByEmail(string $email): ?self
    {
        return self::where('email', $email)->first();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Create default admin user
     */
    public static function createDefaultAdmin(): void
    {
        $admin = self::findByEmail('admin@haryadi.com');
        
        if (!$admin) {
            self::create([
                'name' => 'Administrator',
                'email' => 'admin@haryadi.com',
                'password' => 'password123',
                'role' => 'admin'
            ]);
        }
    }
    
    // Tambahkan method custom di bawah ini
}
