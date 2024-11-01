<?php
$verify_email = explode('/', $permalink);

if (isset($verify_email[1]) && isset($verify_email[2])) {
    $user_id = $verify_email[1];
    $verification_code = $verify_email[2];
    $columns = $join = $where = null;
    $alert_message = Registry::load('strings')->verification_code_expired;
    $alert_type = 'warning';
    $update_data = array();
    Registry::load('settings')->on_load_guest_login_window = 'disable';

    $columns = ['site_users.username', 'site_users.email_address', 'site_users.unverified_email_address', 'site_roles.site_role_attribute'];

    $join["[>]site_roles"] = ['site_users.site_role_id' => 'site_role_id'];
    $where["AND"] = ["site_users.user_id" => $user_id, "site_users.verification_code" => $verification_code];
    $where["LIMIT"] = 1;

    $validate_user = DB::connect()->select('site_users', $join, $columns, $where);

    if (isset($validate_user[0])) {
        if ($validate_user[0]['site_role_attribute'] === 'unverified_users' || !empty($validate_user[0]['unverified_email_address'])) {

            if (!empty($validate_user[0]['unverified_email_address'])) {
                if (filter_var($validate_user[0]['unverified_email_address'], FILTER_VALIDATE_EMAIL)) {
                    $update_data = ['email_address' => $validate_user[0]['unverified_email_address'], 'unverified_email_address' => ''];
                }
            }

            $update_data['verification_code'] = random_string(['length' => 10]);

            if ($validate_user[0]['site_role_attribute'] === 'unverified_users' || $validate_user[0]['site_role_attribute'] === 'guest_users') {
                $default_site_role = DB::connect()->select('site_roles', ['site_roles.site_role_id'], ["site_roles.site_role_attribute" => 'default_site_role']);

                if (isset($default_site_role[0])) {
                    $update_data['site_role_id'] = $default_site_role[0]['site_role_id'];
                }
            }

            DB::connect()->update('site_users', $update_data, ['site_users.user_id' => $user_id]);

            if ($validate_user[0]['site_role_attribute'] === 'unverified_users') {
                include_once('fns/mailer/load.php');

                $mail = array();
                $mail['email_addresses'] = $validate_user[0]['email_address'];
                $mail['category'] = 'welcome_user';
                $mail['user_id'] = $user_id;
                $mail['parameters'] = ['link' => Registry::load('config')->site_url];
                $mail['send_now'] = true;
                mailer('compose', $mail);
            }

            $alert_message = Registry::load('strings')->email_verified;
            $alert_type = 'success';

        }
    }
}