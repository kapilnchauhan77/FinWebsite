<?php
if ($f == 'portfolios') {
    if (empty($_GET['id'])) {
        $_GET['id'] = 0;
    }
    if (empty($_POST['portfolio_name'])) {
        $_POST['portfolio_name'] = "";
    }
    if (empty($_POST['portfolio_name']) || empty($_POST['portfolio_title']) || empty(Wo_Secure($_POST['portfolio_title'])) || Wo_CheckSession($hash_id) === false) {
        $errors[] = $error_icon . $wo['lang']['please_check_details'];
    } else if (empty($_POST['accept_terms'])){
        $errors[] = $error_icon . 'Please Accept the Terms and Conditions';
    } else {
        $is_exist = Wo_IsNameExist($_POST['portfolio_name'], 0);
        if (in_array(true, $is_exist)) {
            $errors[] = $error_icon . 'Portfolio Name Exists';
        }
        if (in_array($_POST['portfolio_name'], $wo['site_pages'])) {
            $errors[] = $error_icon . 'Portfolio Name has Invalid Characters!';
        }
        if (strlen($_POST['portfolio_name']) < 5 OR strlen($_POST['portfolio_name']) > 32) {
            $errors[] = $error_icon . 'Portfolio name too long!';
        }
        if (!preg_match('/^[\w]+$/', $_POST['portfolio_name'])) {
            $errors[] = $error_icon . 'Portfolio Name has Invalid Characters!';
        }
        if (strlen($_POST['portfolio_description']) > 200) {
            $errors[] = $error_icon . 'Portfolio Description should be less than 200 characters';
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

        $privacy_level = 0;

        // PRIVACY LEVELS - PUBLIC, VIEWABLE ALL: 0; PUBLIC, VIEWABLE ONLY FOLLOWERS: 1; PUBLIC, VIEWABLE PAID: 2; PRIVATE, EXCLUDE RANKINGS: 3; PRIVATE, HIDE SECTOR ALLOCATION: 4; PRIVATE, EXCLUDE RANKINGS + SECTOR ALLOCATION: 5
        if ($_POST['portfolio_privacy'] == "on"){
            if ($_POST['portfolio_viewable_by_all'] == "on"){
                $privacy_level = 0;
            }
            else if ($_POST['portfolio_viewable_by_followers'] == "on"){
                $privacy_level = 1;
            }
            else {
                $privacy_level = 2;
            }
        }
        else {
            if ($_POST['portfolio_sector_allocation_viewable'] != "on"){
                $privacy_level = 3;
            }
            else if ($_POST['portfolio_rankings_exclusion'] != "on"){
                $privacy_level = 4;
            }
            else{
                $privacy_level = 5;
            }
        }

        $re_page_data  = array(
            'portfolio_id' => Wo_Secure($_GET['id']),
            'portfolio_name' => Wo_Secure($_POST['portfolio_name']),
            'portfolio_title' => Wo_Secure($_POST['portfolio_title']),
            'portfolio_description' => Wo_Secure($_POST['portfolio_description']),
            'privacy_level' => Wo_Secure($privacy_level),
            'timestamp' => Wo_Secure($_GET['t'])
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

        if ($s == 'create_portfolio') {
            $portfolioCreated = Wo_CreatePortfolio($re_page_data);
            if ($portfolioCreated) {
                $data = array(
                    'status' => 200,
                    // 'location' => Wo_SeoLink('index.php?link1=timeline&u=' . Wo_Secure($_POST['page_name'])),
                    'location' => $wo['config']['site_url'] . "/portfolio",
                    $re_page_data,
                    $portfolioCreated
                );
            }
        } else if ($s == 'edit_portfolio') {
            $portfolioEdited = Wo_EditPortfolio($re_page_data);
            if ($portfolioEdited) {
                $data = array(
                    'status' => 200,
                    // 'location' => Wo_SeoLink('index.php?link1=timeline&u=' . Wo_Secure($_POST['page_name'])),
                    'location' => $wo['config']['site_url'] . "/portfolio",
                    $re_page_data,
                    $portfolioEdited
                );
            }
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
