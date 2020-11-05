<?php
if ($f == 'portfolio_privatization') {
    $privatize = $_GET['privatize'];
    $portfolio_id = $_GET['portfolio_id'];
    if (!empty($privatize)) {
        $done = SA_privatize_portfolio($privatize, $portfolio_id);
        if ($done === true){

            $data = array(
                'status' => 200,
                /* 'privatize' => $privatize, */
                /* 'portfolio_id' => $portfolio_id */
            );

            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }else{

            $data = array(
                'status' => 400,
                'error' => $done
            );

            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    } else {
        $data = array(
            'status' => 400,
            'privatize' => $privatize,
            'portfolio_id' => $portfolio_id
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
