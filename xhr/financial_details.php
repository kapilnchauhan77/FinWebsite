<?php 
if ($f == 'financial_details') {
    $fincode = $_GET['fincode'];
    $tab_data = $_GET['tab_data'];
    $fformat = $_GET['fformat'];
    $type_to_get = $_GET['type_to_get'];

    if (!empty($fincode)) {
        $finance_data = get_financial_data($fincode, $tab_data, $fformat, $type_to_get);
        if (!empty($finance_data)) {
            $data = array(
                'data' => $finance_data,
                'status' => 200,
                'fformat' => $fformat,
            );
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        } else {
            $data = array(
                'status' => 400,
                'fincode' => $fincode,
                'tab_data' => $tab_data,
                'fformat' => $fformat,
                'type_to_get' => $type_to_get,
                'finance_data' => $finance_data,
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
