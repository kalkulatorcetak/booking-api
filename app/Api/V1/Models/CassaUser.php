<?php

namespace App\Api\V1\Models;

use App\Api\V1\Enums\CassaAccessType;
use App\Models\Model;

class CassaUser extends Model
{
    protected $table = 'cassa_user';

    public $timestamps = false;

    protected $fillable = [
        'cassa_id', 'user_id', 'access_type'
    ];

    public function setCassa(Cassa $cassa): void
    {
        $this->cassa_id = $cassa->id;
    }

    public function getCassa(): Cassa
    {
        return Cassa::findOrFail($this->cassa_id);
    }

    public function setUser(User $user): void
    {
        $this->user_id = $user->id;
    }

    public function getUser(): User
    {
        return User::findOrFail($this->user_id);
    }

    public function setCassaAccessType(CassaAccessType $cassaAccessType): void
    {
        $this->access_type = $cassaAccessType->getValue();
    }

    public function getCassaAccessType(): CassaAccessType
    {
        return new CassaAccessType($this->access_type);
    }
}
