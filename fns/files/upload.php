<?php

$result = array();
$result['result'] = false;

set_time_limit(0);

if (isset($data['folder']) && !empty($data['folder']) && isset($data['upload']) && !empty($data['upload'])) {
    if (isset($data['real_path']) && $data['real_path']) {
        $path = $data['folder'];
    } else {
        $path = 'assets/files/'.$data['folder'];
    }

    $upload = $data['upload'];
    $path = rtrim($path, '/').'/';
    $only_allow = array();
    $multi_upload = false;

    if (isset($data['multi_upload']) && $data['multi_upload']) {
        $multi_upload = true;
    }

    if (isset($data['create_folder']) && $data['create_folder']) {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }

    if (isset($_FILES[$upload]) && $_FILES[$upload]['error'] !== UPLOAD_ERR_NO_FILE) {
        if (file_exists($path) && is_dir($path)) {
            $multiple_files = false;
            $total_files = 1;

            if ($multi_upload && is_array($_FILES[$upload]['name'])) {
                $total_files = count($_FILES[$upload]['name']);
                $multiple_files = true;
            }

            if (isset($data['saveas']) && !empty($data['saveas'])) {
                $saveas_info = pathinfo($data['saveas']);
            }

            if (isset($data['only_allow']) && !empty($data['only_allow'])) {
                $only_allow = $data['only_allow'];
            }

            if (isset($data['max_files']) && !empty($data['max_files'])) {
                if ($total_files > $data['max_files']) {
                    $total_files = $data['max_files'];
                }
            }
            for ($i = 0; $i < $total_files; $i++) {
                if (!$multiple_files) {
                    $filename = $_FILES[$upload]['name'];
                    $tmpFilePath = $_FILES[$upload]['tmp_name'];

                    if (isset($data['saveas']) && !empty($data['saveas'])) {
                        $filename = $data['saveas'];

                        if (isset($data['use_file_extension']) && $data['use_file_extension']) {
                            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

                            if (!empty($fileExtension)) {
                                $fileInfo = pathinfo($filename);
                                $fileInfo['extension'] = $fileExtension;
                                $filename = $fileInfo['dirname'] . '/' . $fileInfo['filename'] . '.' . $fileInfo['extension'];

                            }
                        }
                    }

                    if (isset($data['append_random_string']) && $data['append_random_string']) {
                        $upload_file_info = pathinfo($filename);
                        $filename = $upload_file_info['filename'].Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$upload_file_info['extension'];
                    } else if (isset($data['prepend_random_string']) && $data['prepend_random_string']) {
                        $filename = random_string(['length' => 6]).Registry::load('config')->file_seperator.$filename;
                    }
                } else {
                    $filename = $_FILES[$upload]['name'][$i];
                    $tmpFilePath = $_FILES[$upload]['tmp_name'][$i];

                    $prevent_files = array('.htaccess', 'php.ini', 'user.ini');
                    if (in_array($filename, $prevent_files)) {
                        $tmpFilePath = null;
                    }

                    if (isset($data['saveas']) && !empty($data['saveas'])) {

                        if (isset($data['use_file_extension']) && $data['use_file_extension']) {
                            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

                            if (!empty($fileExtension)) {
                                $saveas_info['extension'] = $fileExtension;
                            }
                        }

                        if (isset($data['append_timestamp']) && $data['append_timestamp']) {
                            $filename = $saveas_info['filename'].strtotime("now").'_'.$i.'.'.$saveas_info['extension'];
                        } else {
                            $filename = $saveas_info['filename'].random_string(['length' => 6]).'.'.$saveas_info['extension'];
                        }
                    }

                    if (isset($data['append_random_string']) && $data['append_random_string']) {
                        $upload_file_info = pathinfo($filename);
                        $filename = $upload_file_info['filename'].Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$upload_file_info['extension'];
                    } else if (isset($data['prepend_random_string']) && $data['prepend_random_string']) {
                        $filename = random_string(['length' => 6]).Registry::load('config')->file_seperator.$filename;
                    }
                }

                if (isset($data['sanitize_filename']) && $data['sanitize_filename']) {
                    $filename = sanitize_filename($filename);
                }

                if (isset($data['append_file_extension']) && !empty($data['append_file_extension'])) {
                    $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
                    if ($file_extension === '') {
                        $filename = $filename.'.'.$data['append_file_extension'];
                    }
                }

                if ($tmpFilePath != "") {
                    $newFilePath = $path.$filename;
                    if (!file_exists($newFilePath) || isset($data['overwrite']) && $data['overwrite']) {

                        if (move_uploaded_file($tmpFilePath, $newFilePath)) {


                            if (function_exists('finfo_open')) {
                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                $file_type = finfo_file($finfo, $newFilePath);
                                finfo_close($finfo);
                            } else {
                                $file_type = mime_content_type($newFilePath);
                            }

                            if (empty($only_allow) || in_array($file_type, $only_allow)) {

                                chmod($newFilePath, 0644);

                                $skip_file = false;

                                if ($file_type === 'image/jpeg' || $file_type === 'image/png' || $file_type === 'image/gif' || $file_type === 'image/bmp' || $file_type === 'image/x-ms-bmp') {


                                    $file_cross_check = pathinfo($newFilePath);

                                    if ($file_cross_check["extension"] == "php") {
                                        $skip_file = true;
                                    } else {
                                        files('fix_orientation', ['image' => $newFilePath, 'real_path' => true]);
                                    }
                                }
                                if (!$skip_file) {
                                    $result['files'][$i]['file'] = $newFilePath;
                                    $result['files'][$i]['file_type'] = $file_type;
                                    $result['result'] = true;
                                } else {
                                    $result['files_skipped'][] = $filename;
                                    files('delete', ['delete' => $newFilePath, 'real_path' => true]);
                                }

                            } else {
                                $result['files_skipped'][] = $filename;
                                files('delete', ['delete' => $newFilePath, 'real_path' => true]);
                            }
                        }



                    }
                }
            }
        }
    }
}
?>