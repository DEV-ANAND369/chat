<?php

function ip_intelligence_blacklist($ip) {
    $blacklistFile = 'assets/cache/ip_intel_blacklist.cache';

    if (file_exists($blacklistFile)) {
        $blacklist = file($blacklistFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return in_array($ip, $blacklist);
    }
    return false;
}

function ip_intelligence($data = null) {
    $result = array();
    $result['success'] = true;
    $ip_address = null;
    $blacklisted_on_file = false;

    if (!empty($data) && is_array($data)) {
        if (isset($data['ip_address'])) {
            $ip_address = $data['ip_address'];
        }
    }

    if (empty($ip_address)) {
        $ip_address = Registry::load('current_user')->ip_address;
    }

    if ($ip_address !== '127.0.0.1') {

        if (!empty(Registry::load('settings')->ip_intelligence) && Registry::load('settings')->ip_intelligence !== 'disable') {

            if (!filter_var($ip_address, FILTER_VALIDATE_IP)) {
                $result['success'] = false;
                $result['error_message'] = 'Invalid IP address';
                return $result;
            }


            if (ip_intelligence_blacklist($ip_address)) {
                $result['success'] = false;
                $blacklisted_on_file = true;
            } else {
                $ip_intel_service = Registry::load('settings')->ip_intelligence;

                if (isset($ip_intel_service) && !empty($ip_intel_service)) {
                    $load_fn_file = 'fns/ip_intelligence/'.$ip_intel_service.'.php';
                    if (file_exists($load_fn_file)) {
                        include($load_fn_file);
                    }
                }
            }
        }
    }

    if (!$result['success'] && !$blacklisted_on_file) {
        $blacklistFile = 'assets/cache/ip_intel_blacklist.cache';
        file_put_contents($blacklistFile, $ip_address . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    return $result;
}