<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\JsonRespondController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Transformers\UserResource;

class CustomerController extends Controller
{

    use JsonRespondController;

    public function index(Request $request)
    {
        $customers = User::role('customer')->latest()->paginate(12);

        return UserResource::collection($customers);
    }

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
                'message' => 'Failed to create customer'.$e,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $this->validateUpdateRequest($request);
        
        $customer->update([
            'name' => $request->name
        ]);

        return new UserResource($customer);
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);

        $customer->delete();

        return $this->respond([
            'message' => 'Customer deleted'
        ]);
    }

    private function validateRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
        ]);
        return $validated;
    }

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