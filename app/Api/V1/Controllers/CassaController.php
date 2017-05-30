<?php

namespace app\Api\V1\Controllers;

use App\Api\V1\Enums\CassaAccessType;
use App\Api\V1\Models\Cassa;
use App\Api\V1\Models\User;
use App\Api\V1\Transformers\CassaTransformer;
use App\Api\V1\Validators\CassaValidator;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Cassa resource representation.
 *
 * @Resource("Cassas", uri="/cassas")
 */
class CassaController extends Controller
{
    /**
     * List the cassas
     *
     * @Get("/")
     * @Versions({"v1"})
     */
    public function index(Request $request): Response
    {
        $this->authorize('list', Cassa::class);

        return $this->responseByParams(Cassa::class, $request, ['key' => 'cassas']);
    }

    /**
     * Get an existing cassa
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     */
    public function show($id): Response
    {
        try {
            $cassa = Cassa::findById($id);
        } catch (ModelNotFoundException $ex) {
            $this->response->errorNotFound("Cassa with id {$id} not found!");
        }

        $this->authorize('load', $cassa);

        return $this->item($cassa, new CassaTransformer, ['key' => 'cassa']);
    }

    /**
     * Create a new cassa
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Request({"name": "HUF cassa", "currency": "HUF", "users": {{"user_id" : 1, "access_type" : "edit"}, {"user_id" : 2, "access_type" : "read"}}})
     */
    public function store(Request $request): Response
    {
        $this->authorize('create', Cassa::class);
        $this->validateRequest($request, new CassaValidator);

        $cassa = new Cassa;
        $cassa->fill($request->only(['name', 'currency']));

        app('db')->transaction(function() use ($cassa, $request) {
            $cassa->save();

            if ($request->has('users')) {
                $this->setCassaUsers($cassa, $request->get('users'));
            }
        });

        return $this->item($cassa, new CassaTransformer, ['key' => 'cassa']);
    }

    /**
     * Update an existing cassa
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Request({"name": "New HUF cassa", "currency": "HUF", "users": {{"user_id" : 1, "access_type" : "edit"}}})
     */
    public function update(Request $request, $cassaId): Response
    {
        $cassa = Cassa::findById($cassaId);

        $this->authorize('update', $cassa);
        $this->validateRequest($request, new CassaValidator($cassa));

        $cassa->fill(array_filter($request->only(['name', 'currency'])));
        $cassa->save();

        if ($request->has('users')) {
            $this->setCassaUsers($cassa, $request->get('users'));
        }

        return $this->item($cassa, new CassaTransformer, ['key' => 'cassa']);
    }

    /**
     * Delete an existing cassa
     *
     * @Delete("/{id}")
     * @Version({"1"})
     */
    public function delete($cassaId): Response
    {
        $cassa = Cassa::findById($cassaId);

        $this->authorize('delete', $cassa);

        $cassa->delete();

        return $this->response->noContent();
    }

    protected function setCassaUsers(Cassa $cassa, array $cassaUsers): void
    {
        $cassa->removeAllCassaUsers();

        foreach ($cassaUsers as $cassaUser) {
            if (!isset($cassaUser['user_id']) || !isset($cassaUser['access_type'])) {
                throw new UnprocessableEntityHttpException('Users must have user_id and access_type');
            }

            $user = User::findOrFail($cassaUser['user_id']);
            $accessType = new CassaAccessType($cassaUser['access_type']);

            $cassa->addCassaUser($user, $accessType);
        }
    }
}
