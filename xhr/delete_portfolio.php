<?php
if ($f == 'delete_portfolio') {
    $portfolio_id = $_GET['portfolio_id'];
    if (!empty($portfolio_id)) {
        SA_deletePortfolio($portfolio_id);

        $data = array(
            'portfolio_id' => $portfolio_id,
            'status'       => 200
        );

        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    } else {
        $data = array(
            'status' => 400,
            'portfolio_id' => $portfolio_id
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
