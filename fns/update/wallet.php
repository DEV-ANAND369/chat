<?php

include_once 'fns/payments/load.php';

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$noerror = true;

if (role(['permissions' => ['wallet' => 'topup_wallet']])) {
    if (isset($data['amount']) && !empty($data['amount'])) {
        $data['amount'] = trim($data['amount']);
        $data['amount'] = preg_replace('/[^0-9.]/', '', $data['amount']);

        if (!is_numeric($data['amount'])) {
            $data['amount'] = null;
        } else {
            $data['amount'] = intval(ceil($data['amount']));
            if ($data['amount'] <= 0) {
                $data['amount'] = null;
            }
        }
    }

    if (isset($data['payment_gateway_id'])) {

        $columns = $join = $where = null;
        $columns = ['payment_gateways.payment_gateway_id', 'payment_gateways.identifier', 'payment_gateways.credentials'];
        $where["payment_gateways.payment_gateway_id"] = $data['payment_gateway_id'];
        $where["payment_gateways.disabled[!]"] = 1;
        $gateway = DB::connect()->select('payment_gateways', $columns, $where);

        if (isset($gateway[0])) {
            $data['payment_gateway_id'] = $gateway[0]['payment_gateway_id'];
        } else {
            $data['payment_gateway_id'] = null;
        }
    }


    if (!isset($data['amount']) || empty($data['amount'])) {
        $result['error_message'] = Registry::load('strings')->invalid_topup_amount;
        $result['error_key'] = 'invalid_topup_amount';
        $noerror = false;
    }

    if (!isset($data['payment_gateway_id']) || empty($data['payment_gateway_id'])) {
        $result['error_message'] = Registry::load('strings')->invalid_payment_method;
        $result['error_key'] = 'invalid_payment_method';
        $noerror = false;
    }



    if (Registry::load('settings')->require_billing_address === 'yes') {
        $columns = $join = $where = null;
        $columns = ['billed_to', 'street_address', 'city', 'state', 'country', 'postal_code'];
        $where["billing_address.user_id"] = Registry::load('current_user')->id;
        $billing_address = DB::connect()->select('billing_address', $columns, $where);

        if (!empty($billing_address)) {
            if (empty($billing_address[0]['billed_to'])) {
                $billing_address = null;
            }
        }

        if (empty($billing_address)) {
            $result['error_message'] = Registry::load('strings')->billing_address_not_found;
            $result['error_key'] = 'invalid_payment_method';
            $noerror = false;
        }
    }


    if ($noerror) {
        DB::connect()->insert("site_users_wallet", [
            "user_id" => Registry::load('current_user')->id,
            "wallet_amount" => $data['amount'],
            "currency_code" => Registry::load('settings')->default_currency,
            "payment_gateway_id" => $data['payment_gateway_id'],
            "transaction_type" => 1,
            "created_on" => Registry::load('current_user')->time_stamp,
            "updated_on" => Registry::load('current_user')->time_stamp,
        ]);

        if (!DB::connect()->error) {
            $wallet_transaction_id = DB::connect()->id();
            $validation_url = Registry::load('config')->site_url.'topup_wallet/'.$wallet_transaction_id.'/';

            $payment_data = [
                'gateway' => $gateway[0]['identifier'],
                'wallet_transaction_id' => $wallet_transaction_id,
                'purchase' => $data['amount'],
                'transaction_name' => 'TopUp Wallet ['.$wallet_transaction_id.']',
                'credentials' => $gateway[0]['credentials'],
                'description' => 'TopUp Wallet - '.$data['amount'].' ['.Registry::load('settings')->site_name.']',
                'validation_url' => $validation_url,
            ];

            $result = payment_module($payment_data);

            if (isset($result['error_key']) && $result['error_key'] === 'invalid_payment_credentials') {
                DB::connect()->delete("site_users_wallet", [
                    "wallet_transaction_id" => $wallet_transaction_id,
                ]);
            }
        }
    }
}

?>