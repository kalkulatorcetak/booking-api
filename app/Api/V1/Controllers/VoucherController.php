<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Voucher;
use App\Api\V1\Transformers\VoucherTransformer;
use App\Api\V1\Validators\VoucherValidator;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Voucher resource representation.
 *
 * @Resource("Vouchers", uri="/vouchers")
 */
class VoucherController extends Controller
{
    /**
     * List the vouchers
     *
     * @Get("/")
     * @Versions({"v1"})
     */
    public function index(Request $request): Response
    {
        $this->authorize('list', Voucher::class);

        return $this->responseByParams(Voucher::class, $request, ['key' => 'vouchers']);
    }

    /**
     * Get an existing voucher
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     */
    public function show($id): Response
    {
        try {
            $voucher = Voucher::findById($id);
        } catch (ModelNotFoundException $ex) {
            $this->response->errorNotFound("Voucher with id {$id} not found!");
        }

        $this->authorize('load', $voucher);

        return $this->item($voucher, new VoucherTransformer, ['key' => 'voucher']);
    }

    /**
     * Create a new voucher
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Request({"cassa_id": 1, "type": "income", "date": "2017-06-13", "amount": 15400, "cashier_id": 2, "comment": "initial income"})
     */
    public function store(Request $request): Response
    {
        $this->authorize('create', Voucher::class);
        $this->validateRequest($request, new VoucherValidator);

        $voucher = new Voucher;
        $voucher->fill($request->only(['cassa_id', 'type', 'date', 'amount', 'cashier_id', 'comment']));
        $voucher->save();

        return $this->item($voucher, new VoucherTransformer, ['key' => 'voucher']);
    }

    /**
     * Update an existing voucher
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Request({"type": "income", "date": "2017-06-13", "amount": 15400, "cashier_id": 2, "comment": "initial income"})
     */
    public function update(Request $request, $voucherId): Response
    {
        $voucher = Voucher::findById($voucherId);

        $this->authorize('update', $voucher);
        $this->validateRequest($request, new VoucherValidator($voucher));

        $voucher->fill(array_filter($request->only(['type', 'date', 'amount', 'cashier_id', 'comment'])));
        $voucher->save();

        return $this->item($voucher, new VoucherTransformer, ['key' => 'voucher']);
    }

    /**
     * Delete an existing voucher
     *
     * @Delete("/{id}")
     * @Version({"1"})
     */
    public function delete($voucherId): Response
    {
        $voucher = Voucher::findById($voucherId);

        $this->authorize('delete', $voucher);

        $voucher->delete();

        return $this->response->noContent();
    }
}
