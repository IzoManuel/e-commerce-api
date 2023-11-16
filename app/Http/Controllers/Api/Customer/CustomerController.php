<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\JsonRespondController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Transformers\UserResource;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerController extends Controller
{
    use JsonRespondController;

    /**
     * Get the list of customers
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | Illuminate\Http\JsonRsponse
     */
    public function index(Request $request)
    {
        try {
            $customers = User::role('customer')->latest()->paginate(12);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return UserResource::collection($customers);
    }

    /**
     * Create a customer
     * 
     * @param Request $request
     * @return CustomerResource | Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        try {
            DB::beginTransaction();
            $customer = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $customer->assignRole('customer');

            DB::commit();

            return new UserResource($customer);
            
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithError([
                'message' => 'Failed to create customer'
            ]);
        }
    }

    /**
     * Update a customer
     * 
     * @param Request $request
     * @param int $customerId
     */
    public function update(Request $request, $customerId)
    {
        try {
            $customer = User::findOrFail($customerId);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        $this->validateUpdateRequest($request);

        try {
            $customer->update([
                'name' => $request->name,
                'random_field' => 'random_value'
            ]);
        } catch (QueryException $e) {
            return $this->respondNotTheRightParameters();
        }

        return new UserResource($customer);
    }

    /**
     * Delete a customer
     * 
     * @param int $customerId
     */
    public function destroy($customerId)
    {
        try {
            $customer = User::findOrFail($customerId);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }
        

        $customer->delete();

        return $this->respondObjectDeleted($customer->id);
    }

    /**
     * Validate the request for create
     * 
     * @param Request $request
     * @return boolean true | Illuminate\Http\JsonResponse
     */
    private function validateRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
        ]);
        return $validated;
    }

    /**
     * Validate the request for update
     * 
     * @param Request @request
     * @return boolean true | Illuminate\Http\JsonResponse
     */
    public function validateUpdateRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            // 'email' => 'required|unique:users|email',
            // 'password' => 'required|min:8',
        ]);
        return $validated;
    }
}