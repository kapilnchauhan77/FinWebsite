<?php 
if ($f == 'portfolios') {
    if ($s == 'create_portfolio') {
        if (!empty($_POST['portfolio_name']) && ($_POST['portfolio_name'] == 'wowonder' || $_POST['portfolio_name'] == 'sunshine' || $_POST['portfolio_name'] == $wo['config']['theme']) ) {
            $_POST['portfolio_name'] = "";
        }
        if (empty($_POST['portfolio_name']) || empty($_POST['portfolio_title']) || empty(Wo_Secure($_POST['portfolio_title'])) || Wo_CheckSession($hash_id) === false) {
            $errors[] = $error_icon . $wo['lang']['please_check_details'];
        } else {
            $is_exist = Wo_IsNameExist($_POST['portfolio_name'], 0);
            if (in_array(true, $is_exist)) {
                $errors[] = $error_icon . $wo['lang']['page_name_exists'];
            }
            if (in_array($_POST['portfolio_name'], $wo['site_pages'])) {
                $errors[] = $error_icon . $wo['lang']['page_name_invalid_characters'];
            }
            if (strlen($_POST['portfolio_name']) < 5 OR strlen($_POST['portfolio_name']) > 32) {
                $errors[] = $error_icon . $wo['lang']['page_name_characters_length'];
            }
            if (!preg_match('/^[\w]+$/', $_POST['portfolio_name'])) {
                $errors[] = $error_icon . $wo['lang']['page_name_invalid_characters'];
            }
        }
        if (empty($errors)) {
            $sub_category = '';
            if (!empty($_POST['page_sub_category']) && !empty($wo['page_sub_categories'][$_POST['page_category']])) {
                foreach ($wo['page_sub_categories'][$_POST['page_category']] as $key => $value) {
                    if ($value['id'] == $_POST['page_sub_category']) {
                        $sub_category = $value['id'];
                    }
                }
            }

            $re_page_data  = array(
                'portfolio_name' => Wo_Secure($_POST['portfolio_name']),
                'user_id' => Wo_Secure($wo['user']['user_id']),
                'portfolio_title' => Wo_Secure($_POST['portfolio_title']),
                'portfolio_description' => Wo_Secure($_POST['portfolio_description']),
                'active' => '1'
            );
            if (!empty($fields)) {
                foreach ($fields as $key => $field) {
                    if ($field['required'] == 'on' && empty($_POST['fid_'.$field['id']])) {
                        $errors[] = $error_icon . $wo['lang']['please_check_details'];
                        header("Content-type: application/json");
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                        exit();
                    }
                    elseif (!empty($_POST['fid_'.$field['id']])) {
                        $re_page_data['fid_'.$field['id']] = Wo_Secure($_POST['fid_'.$field['id']]);
                    }
                }
            }

            $register_page = Wo_RegisterPage($re_page_data);
            if ($register_page) {
                $data = array(
                    'status' => 200,
                    'location' => Wo_SeoLink('index.php?link1=timeline&u=' . Wo_Secure($_POST['page_name']))
                );
            }
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();
    }
}
