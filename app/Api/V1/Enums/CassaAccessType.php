<?php

namespace App\Api\V1\Enums;

use App\Enum\Enum;

class CassaAccessType extends Enum
{
    const NONE = 'none';
    const READ = 'read';
    const EDIT = 'edit';
}
