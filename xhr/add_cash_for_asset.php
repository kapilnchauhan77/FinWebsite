<?php
if ($f == 'add_cash_for_asset') {
    $cash = $_GET['cash'];
    $portfolio_id = $_GET['portfolio_id'];
    $note = $_GET['note'];
    $date_ = $_GET['date_'];
    if (!empty($cash) && !empty($portfolio_id)) {
        $done = AddCashForAssetManually($portfolio_id, $cash, $note, $date_);
        if ($done === true){
            $data = array(
                'status' => 200,
            );
        } else {
            $data = array(
                'status' => 401,
                'error' => $done,
                'cash' => $cash,
                'portfolio_id' => $portfolio_id,
            );
        }
    } else {
        $data = array(
            'status' => 404,
            'cash' => $cash,
            'portfolio_id' => $portfolio_id,
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
