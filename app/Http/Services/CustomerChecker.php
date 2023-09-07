<?php 
namespace App\Http\Services;

use App\Models\Customer;
use App\Models\CustomerType;
use Exception;

trait CustomerChecker
{
    private function isCustomerExists($name=null, $mobile=null){
        try {

            $customer = Customer::where([
                ['customer_name', $name],
                ['customer_phone', $mobile],
            ])->first();

            $type = $customer->customerType('customize')->first();
            if(!$customer && !$type)
                throw new Exception("Customer Not Found!", 403);  

            return [
                'success'   => true,
                'msg'       => 'ok',
                'data'      => $customer
            ];

        } catch (\Throwable $th) {
            return [
                'success'   => false,
                'msg'       => $th->getMessage(),
                'data'      => null
            ];
        }
    }


    private function createCustomer(array $fields=[]){
        try {

            $customer = Customer::create($fields);
            if(!$customer)
                throw new Exception("Unable to create customer!", 403);
                
            return [
                'success'   => true,
                'msg'       => 'Customer Created Successfully!',
                'data'      => $customer
            ];

        }  catch (\Throwable $th) {
            return [
                'success'   => false,
                'msg'       => $th->getMessage(),
                'data'      => null
            ];
        }
    }


    private function createCustomerType(array $fields=[]){
        try {

            $customerType = CustomerType::create($fields);
            if(!$customerType)
                throw new Exception("Unable to create customer!", 403);
                
            return [
                'success'   => true,
                'msg'       => 'Customer Type Created Successfully!',
                'data'      => $customerType
            ];

        }  catch (\Throwable $th) {
            return [
                'success'   => false,
                'msg'       => $th->getMessage(),
                'data'      => null
            ];
        }
    }





}