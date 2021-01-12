<?php
if ($f == 'deleteProperty') {
    $portfolio_id = $_GET['portfolio_id'];
    $uid = $_GET['uid'];
    $purchase_price = $_GET['purchase_price'];
    if (!empty($portfolio_id)) {
        $portfolio_data_added = deletePropertyFromPortfolio($portfolio_id, $uid, $purchase_price);

        if ($portfolio_data_added === true){

            $data = array(
                'added' => $portfolio_data_added,
                'status' => 200
            );

            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }else{

            $data = array(
                'status' => 400,
                'error' => $portfolio_data_added,
                'portfolio_id' => $portfolio_id,
            );

            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    } else {
        $data = array(
            'status' => 404,
            'error' => 'Don\'t change internal files!',
            'portfolio_id' => $portfolio_id,
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
