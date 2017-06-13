<?php

namespace App\Api\V1\Models;

use App\Api\V1\Enums\VoucherType;
use App\Contracts\Cacheable;
use App\Models\Model;

class Voucher extends Model implements Cacheable
{
    protected $fillable = [
        'cassa_id', 'type', 'date', 'amount', 'cashier_id', 'comment'
    ];

    protected function generateNum(): string
    {
        $number = '';

        if ($this->cassa_id > 0 && !empty($this->type) && !empty($this->date)) {
            $cassa = Cassa::findById($this->cassa_id);
            $typeName = $this->type === VoucherType::INCOME ? 'IN' : 'OUT';
            $num = $this->getNextVoucherNum($this->cassa_id, $this->type, $this->date);

            $number = sprintf('%s-%s/%s-%s', $cassa->name, $typeName, $this->date, $num);
        }

        return $number;
    }

    protected function getNextVoucherNum($cassa_id, $type, $date): int
    {
        return app('db')->table('vouchers')
            ->where('cassa_id', $cassa_id)
            ->where('type', $type)
            ->where('date', $date)->count() + 1;
    }

    public function save(array $options = []): bool
    {
        if ($this->id === null) {
            $this->number = $this->generateNum();
        }

        return parent::save($options);
    }

    public function getCassa(): ?Cassa
    {
        return $this->cassa_id > 0 ? Cassa::findById($this->cassa_id) : null;
    }
}
