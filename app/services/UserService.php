<?php
namespace App\Services;

use App\Repositories\UserRepository;
use App\Events\UserCreated;

class UserService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function getUserById(int $id): ?array
    {
        return $this->userRepository->findById($id);
    }

    public function createUser(array $data): array
    {
        // Validation
        if (empty($data['email'])) {
            throw new \InvalidArgumentException('Email is required');
        }

        // Create user
        $user = $this->userRepository->create($data);

        // Dispatch event
        App::getInstance()->event()->dispatch(new UserCreated($user));

        return $user;
    }
}