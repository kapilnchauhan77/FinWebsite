<?php 
if ($f == 'company_price') {
    $fincode = $_GET['fincode'];
    $timestamp = $_GET['timestamp'];
    if (!empty($fincode)) {
        $company_prices = Wo_GetCompanyPrice($fincode, $timestamp);
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
                'status' => $company_prices,
                'fincode' => $fincode,
                'timestamp' => $timestamp,
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
