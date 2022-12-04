<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDescription;
use App\Models\TransactionResponse;
use Illuminate\Http\Request;

class XenditService
{
    /**
     * Setup xendit parameters.
     * 
     * @param float $amount
     * @param string $reference
     * @return array
     */
    public function setupParameters(float $amount, string $reference) : array
    {
        $amount  = (float) number_format($amount, 2);

        return [
            'reference_id' => $reference,
            'currency' => 'PHP',
            'amount' => $amount,
            'checkout_method' => 'ONE_TIME_PAYMENT',
            'channel_code' => 'PH_GCASH',
            'channel_properties' => [
                'success_redirect_url' => route('xendit.success') . "?reference_id={$reference}",
                'failure_redirect_url' => route('xendit.failed'),
            ],
            'metadata' => [
                'branch_code' => 'tree_branch'
            ]
        ];
    }

    /**
     * Setup url to redirect.
     * 
     * @param array $createEWalletCharge
     * @return string
     */
    public function url(array $createEWalletCharge) : string
    {
        $ua = strtolower(request()->header('User-Agent'));

        if(is_numeric(strpos($ua, 'mobile')))
        {
            return $createEWalletCharge['actions']['mobile_web_checkout_url'];
        }

        return $createEWalletCharge['actions']['desktop_web_checkout_url'];
    }

    /**
     * To failed transaction.
     * 
     * @param Request $request
     */
    public function toFailed(Request $request)
    {
        $response = TransactionResponse::where('reference_id', $request->data['reference_id'])->first();

        if(filled($response))
        {
            $order_descpription = OrderDescription::where('id', $response->order_description_id)->first();
            $reference = $order_descpription->reference;

            $response->update(['status' => $request->data['status']]);

            if($request->data['status'] == 'FAILED')
            {
                $order_descpription->update(['status' => 'pending']);

                Order::where('reference', $reference)->update([
                    'status' => 'pending'
                ]);
            }
        }
    }

    /**
     * To update transaction to succesful status.
     * 
     * @param Request $request
     */
    public function toSuccessful(Request $request)
    {
        $response = TransactionResponse::where('reference_id', $request->data['reference_id'])->first();

        if(!blank($response))
        {
            $order_descpription = OrderDescription::where('id', $response->order_description_id)->first();
            $reference = $order_descpription->reference;

            $order_descpription->update(['status' => 'to_process']);

            Order::where('reference', $reference)->update([
                'status' => 'to_process'
            ]);
            
            $response->update(['status' => 'SUCCESS']);
        }
    }
}