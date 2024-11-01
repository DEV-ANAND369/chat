<?php

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$noerror = true;

if (role(['permissions' => ['site_users' => 'import_users']])) {

    include 'fns/filters/load.php';
    include 'fns/files/load.php';

    $noerror = true;

    if ($noerror) {

        if (isset($_FILES['csv_file']['name']) && !empty($_FILES['csv_file']['name'])) {

            $filename = 'import_users_'.strtotime("now").'.csv';

            $csvMimeTypes = [
                'text/csv',
                'application/csv',
                'text/plain',
                'text/comma-separated-values',
                'application/comma-separated-values',
                'application/vnd.ms-excel',
                'application/octet-stream'
            ];

            $upload_info = [
                'upload' => 'csv_file',
                'folder' => 'assets/cache/',
                'saveas' => $filename,
                'real_path' => true,
            ];

            $csv_file = files('upload', $upload_info);

            if ($csv_file['result']) {

                $csv_file_location = 'assets/cache/'.$filename;
                $save_results = 'assets/cache/import_users/';

                if (!file_exists($save_results)) {
                    mkdir($save_results);
                }

                $save_results .= 'import_csv_result_'.strtotime("now").'.php';

                if (file_exists($csv_file_location)) {

                    if (pathinfo($csv_file_location, PATHINFO_EXTENSION) === 'csv') {

                        if (($handle = fopen($csv_file_location, "r")) !== FALSE) {
                            $import_result = array();
                            $index = 1;


                            while (($data = fgetcsv($handle, 1500, ",")) !== FALSE) {
                                $total_columns = count($data);
                                $skip_row = false;
                                $skip_insert = false;

                                $user_data = [
                                    'add' => 'site_users',
                                    'full_name' => '',
                                    'username' => '',
                                    'email_address' => '',
                                    'password' => '',
                                    'return' => true
                                ];

                                if (isset($data[0]) && $data[0] === 'Full Name' || isset($data[0]) && $data[0] === 'Username') {
                                    $skip_row = true;
                                }

                                if (!$skip_row) {

                                    if (isset($data[0]) && !empty($data[0])) {
                                        $user_data['full_name'] = $data[0];
                                    }

                                    if (isset($data[1]) && !empty($data[1])) {
                                        $user_data['username'] = $data[1];
                                    }

                                    if (isset($data[2]) && !empty($data[2])) {
                                        $user_data['email_address'] = $data[2];
                                    }

                                    if (isset($data[3]) && !empty($data[3])) {
                                        $user_data['password'] = $data[3];
                                    }

                                    if (isset($data[4]) && !empty($data[4])) {
                                        $user_data['site_role'] = $data[4];
                                    }

                                    if (isset($data[5]) && !empty($data[5])) {
                                        $user_data['avatarURL'] = $data[5];
                                    }

                                    if (!empty($user_data['full_name']) && !empty($user_data['email_address']) && !empty($user_data['username']) && !empty($user_data['password'])) {
                                        $import_result[$index]['user_data'] = $user_data['full_name'].'  - '.$user_data['username'];
                                        $import_result[$index]['result'] = add($user_data, ['force_request' => true, 'exclude_filters_function' => true, 'exclude_files_function' => true]);
                                        $index++;
                                    }
                                }
                            }
                            fclose($handle);
                        }
                    }

                    if (isset($import_result) && !empty($import_result)) {
                        $jsonData = json_encode($import_result, JSON_PRETTY_PRINT);
                        file_put_contents($save_results, $jsonData);
                    }

                    unlink($csv_file_location);
                }
            }

        }
        $result = array();
        $result['success'] = true;
        $result['todo'] = 'reload';
        $result['reload'] = 'site_users';

    }
}
?>