<?php
namespace App\Enums;

enum RoleUsers: string {
    case Admin = 'admin';
    case Vet = 'vet';
    case User = 'owner';
}