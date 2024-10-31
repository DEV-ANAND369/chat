<?php
include 'fns/firewall/load.php';
include_once 'fns/sql/load.php';
include_once 'fns/SleekDB/Store.php';
include 'fns/variables/load.php';

use SleekDB\Store;

if (isset($_GET['embed_url']) && !empty($_GET['embed_url'])) {
    if (Registry::load('current_user')->logged_in) {

        $embed_url = urldecode($_GET['embed_url']);
        $embed_url = htmlspecialchars($embed_url, ENT_QUOTES, 'UTF-8');

        $allowed_hosts = ['paymentwall.com', 'api.paymentwall.com'];

        if (!empty($embed_url)) {

            $parsed_url = parse_url($embed_url);
            $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';

            if (!in_array($host, $allowed_hosts)) {
                $embed_url = null;
            }
        }

        if (!empty($embed_url)) {
            echo '<style> body, html { margin: 0; padding: 0; height: 100%; } iframe { border: none;width: 100%; height: 100%; }</style>';
            echo '<iframe src="'.$embed_url.'" allowfullscreen></iframe>';
            exit;
        }
    }
}

$domain_url_path = urldecode(Registry::load('config')->url_path);
$domain_url_path = parse_url($domain_url_path);
$domain_url_path = $domain_url_path['path'];
$domain_url_path = preg_split('/\//', $domain_url_path);

$wallet_transaction_id = null;

if (isset($domain_url_path[1])) {
    $wallet_transaction_id = filter_var($domain_url_path[1], FILTER_SANITIZE_NUMBER_INT);
}

if (empty($wallet_transaction_id) && isset($_COOKIE['current_wallet_tp_trans']) && !empty($_COOKIE['current_wallet_tp_trans'])) {
    $wallet_transaction_id = filter_var($_COOKIE['current_wallet_tp_trans'], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($wallet_transaction_id)) {
        $validation_url = Registry::load('config')->site_url.'topup_wallet/'.$wallet_transaction_id.'/';
        redirect($validation_url);
    }
}

if (isset($_POST['error']) && isset($_POST['error']['code']) || isset($_POST['razorpay_payment_id']) || isset($_POST['token']) || isset($_POST['mer_txnid'])) {
    $validation_url = Registry::load('config')->site_url.'topup_wallet/'.$wallet_transaction_id.'/';
    ?>
    <script>
        window.setTimeout(function() {
            window.location.href = "<?php echo $validation_url ?>";
        }, 500);
    </script>
    <?php
    exit;
}

if (Registry::load('current_user')->logged_in) {

    if (!empty($wallet_transaction_id)) {

        add_cookie('current_wallet_tp_trans', 0);

        $columns = $join = $where = null;
        $columns = [
            "site_users_wallet.user_id", "site_users_wallet.transaction_info", "site_users_wallet.wallet_amount",
            "site_users_wallet.payment_gateway_id", "site_users_wallet.transaction_info", "site_users_wallet.transaction_status",
            'payment_gateways.identifier', 'payment_gateways.credentials'
        ];
        $join["[>]payment_gateways"] = ['site_users_wallet.payment_gateway_id' => 'payment_gateway_id'];
        $where = [
            "site_users_wallet.wallet_transaction_id" => $wallet_transaction_id,
            "site_users_wallet.transaction_type" => 1,
            "site_users_wallet.transaction_status" => 0,
            "site_users_wallet.payment_gateway_id[!]" => null,
            "site_users_wallet.user_id" => Registry::load('current_user')->id
        ];

        $wallet_transaction = DB::connect()->select('site_users_wallet', $join, $columns, $where);

        if (isset($wallet_transaction[0])) {
            $wallet_transaction = $wallet_transaction[0];
        }

        if (isset($wallet_transaction['wallet_amount']) && isset($wallet_transaction['identifier']) && !empty($wallet_transaction['identifier'])) {

            $validation_url = Registry::load('config')->site_url.'topup_wallet/'.$wallet_transaction_id.'/';

            if ($wallet_transaction['identifier'] === 'bank_transfer') {
                $bank_transfer_url = Registry::load('config')->site_url.'bank_transfer/'.$wallet_transaction_id.'/';
                redirect($bank_transfer_url);
            }

            if ((int)$wallet_transaction['transaction_status'] === 0) {

                include_once 'fns/payments/load.php';

                $transaction_info = array();

                $validate_data = [
                    'validate_purchase' => $wallet_transaction_id,
                    'gateway' => $wallet_transaction['identifier'],
                    'credentials' => $wallet_transaction['credentials']
                ];

                if (!empty($wallet_transaction['transaction_info'])) {
                    $payment_session_data = json_decode($wallet_transaction['transaction_info'], true);

                    if (!empty($payment_session_data)) {

                        $transaction_info = $payment_session_data;

                        if (isset($payment_session_data['payment_session_id'])) {
                            $validate_data['payment_session_id'] = $payment_session_data['payment_session_id'];
                        } else if (isset($payment_session_data['payment_session_data'])) {
                            $validate_data['payment_session_data'] = $payment_session_data['payment_session_data'];
                        }
                    }
                }

                $payment_status = payment_module($validate_data);

                if (isset($payment_status['transaction_info'])) {
                    $transaction_info = array_merge($transaction_info, $payment_status['transaction_info']);
                }


                if (isset($payment_status['success']) && $payment_status['success']) {

                    include_once 'fns/wallet/load.php';

                    $wallet_data = [
                        'credit' => $wallet_transaction['wallet_amount'],
                        'user_id' => Registry::load('current_user')->id
                    ];
                    UserWallet($wallet_data);

                    DB::connect()->update('site_users_wallet',
                        ['transaction_status' => 1, 'transaction_info' => $transaction_info, 'wallet_fund_status' => 1,
                            "updated_on" => Registry::load('current_user')->time_stamp],
                        ['wallet_transaction_id' => $wallet_transaction_id]
                    );

                    $layout_variable = array();
                    $layout_variable['title'] = $layout_variable['status'] = Registry::load('strings')->success;
                    $layout_variable['description'] = Registry::load('strings')->transaction_successful_message;
                    $layout_variable['button'] = Registry::load('strings')->continue_text;
                    $layout_variable['successful'] = true;
                    include_once 'layouts/transaction_status/layout.php';
                    exit;

                } else {
                    DB::connect()->update('site_users_wallet', ['transaction_status' => 2, 'transaction_info' => $transaction_info], ['wallet_transaction_id' => $wallet_transaction_id]);

                    $layout_variable = array();
                    $layout_variable['title'] = $layout_variable['status'] = Registry::load('strings')->failed;
                    $layout_variable['description'] = Registry::load('strings')->transaction_failed_message;
                    $layout_variable['button'] = Registry::load('strings')->continue_text;
                    $layout_variable['successful'] = false;

                    include_once 'layouts/transaction_status/layout.php';
                    exit;
                }


            } else {

                $layout_variable = array();
                $layout_variable['title'] = $layout_variable['status'] = Registry::load('strings')->failed;
                $layout_variable['description'] = Registry::load('strings')->transaction_failed_message;
                $layout_variable['button'] = Registry::load('strings')->continue_text;
                $layout_variable['successful'] = false;

                include_once 'layouts/transaction_status/layout.php';
                exit;
            }

        } else {
            $wallet_transaction_id = null;
        }

    }

    if (empty($wallet_transaction_id)) {
        $layout_variable = array();
        $layout_variable['title'] = $layout_variable['status'] = Registry::load('strings')->failed;
        $layout_variable['description'] = Registry::load('strings')->invalid_transaction;
        $layout_variable['button'] = Registry::load('strings')->continue_text;
        $layout_variable['successful'] = false;

        include_once 'layouts/transaction_status/layout.php';
        exit;
    }
} else {
    redirect('404');
}

?>