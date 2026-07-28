<?php

namespace App\Enums;

enum RoleEnums : string
{
    case Staff = 'staff';
    case Administrator = 'administrator';
    case SuperAdministrator = 'super administrator';
}
