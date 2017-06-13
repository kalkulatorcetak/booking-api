<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Models\Voucher;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class VoucherTransformer extends TransformerAbstract
{
    public function transform(Voucher $voucher): array
    {
        return [
            'id'    => $voucher->id,
            'cassa_id'  => $voucher->cassa_id,
            'type' => $voucher->type,
            'date' => $voucher->date,
            'number' => $voucher->number,
            'amount' => $voucher->amount,
            'cashier_id' => $voucher->cashier_id,
            'comment' => $voucher->comment,
            'added' => (new Carbon($voucher->created_at))->toDateTimeString(),
            'modified' => (new Carbon($voucher->updated_at))->toDateTimeString(),
        ];
    }
}
