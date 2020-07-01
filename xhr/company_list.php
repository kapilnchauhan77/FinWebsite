<?php
if ($f == 'company_list') {
    $page_num = $_GET['page_num'];
    $pageFilter = $_GET['page_filter'];
    if (!empty($page_num) && !empty($pageFilter) && Wo_CheckMainSession($hash_id) === true) {
        $companies = Wo_GetCompanies(($page_num-1)*15, $pageFilter);
        $the_query = $companies[0];
        if (count($companies) > 0) {
            $html_snippets = '';
            foreach ($companies as $wo['company']) {
                $html_snippets .= Wo_LoadPage('stock_quotes/company-list');
            }
            $data = array(
                'html_snippets' => $html_snippets,
                'status' => 200
            );
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        } else {
            $data = array(
                'status' => 400,
            );
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        };
    } else {
        $data = array(
            'status' => 400,
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
