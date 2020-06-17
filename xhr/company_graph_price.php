<?php 
if ($f == 'company_graph_price') {
    $fincode = $_GET['fincode'];
    $timestamp = $_GET['timestamp'];
    $type = $_GET['type'];
    $DateOption = $_GET['DateOption'];
    $DateCount = $_GET['DateCount'];
    $StartDate = $_GET['StartDate'];
    $EndDate = $_GET['EndDate'];
    if (!empty($fincode)) {
        $company_prices = Wo_GetCompanyGraphPrice($fincode, $timestamp, $type, $DateOption, $DateCount, $StartDate, $EndDate);
        if (!empty($company_prices)) {
            $data = array(
                'company_prices' => $company_prices,
                'status' => 200
            );
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        } else {
            $data = array(
                'status' => 400,
                'fincode' => $fincode,
                'timestamp' => $timestamp,
                'company_prices' => $company_prices,
                'type' => $type
            );
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        };
    } else {
        $data = array(
            'status' => 404,
            'hash_id' => Wo_CheckMainSession($hash_id)
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
