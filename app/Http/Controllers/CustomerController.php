<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Enums\CustomerStatus;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CustomerListResource;
use App\Http\Resources\CustomerResource;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
   public function index() {

    $perPage = request('per_page', 10);
    $search = request('search','');
    $sortField = request('sort_field' ,'updated_at');
    $sortDirection = request('sort_direction','desc');

    $table = $sortField === 'email' ? 'users' : 'customers';

    // $query = Customer::query()->orderBy("$table.$sortField" ,$sortDirection);

    // if($search){
    $query = Customer::query()->where(DB::raw("CONCAT(first_name, ' ', last_name)"),"like","%{$search}%")
        ->join('users', 'customers.user_id', '=', 'users.id')
        ->orWhere('users.email', 'like', "%{$search}%")
        ->orWhere('customers.phone', 'like', "%{$search}%")
        ->orderBy("$table.$sortField", $sortDirection);
    // }

    $paginator = $query->paginate($perPage);

    return  CustomerListResource::collection($paginator);
   }

   public function show(Customer $customer) {
    return new CustomerResource($customer);
   }

   public function update(CustomerRequest $request, Customer $customer)
   {
       $customerData = $request->validated();
       $customerData['updated_by'] = $request->user()->id;
       $customerData['status'] = $customerData['status'] ? CustomerStatus::Active->value : CustomerStatus::Disabled->value;
       $shippingData = $customerData['shippingAddress'];
       $billingData = $customerData['billingAddress'];

       $customer->update($customerData);

       if ($customer->shippingAddress) {
           $customer->shippingAddress->update($shippingData);
       } else {
           $shippingData['customer_id'] = $customer->user_id;
           $shippingData['type'] = AddressType::Shipping->value;
           CustomerAddress::create($shippingData);
       }
       if ($customer->billingAddress) {
           $customer->billingAddress->update($billingData);
       } else {
           $billingData['customer_id'] = $customer->user_id;
           $billingData['type'] = AddressType::Billing->value;
           CustomerAddress::create($billingData);
       }

       return new CustomerResource($customer);
   }

   public function destory(Customer $customer) {
        $customer->delete();
        return response()->noContent();
   }

   public function countries() {
        return CountryResource::collection(Country::query()->orderBy('name', 'asc')->get());
   }
}
