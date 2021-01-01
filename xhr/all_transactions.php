<?php
if ($f == 'all_transactions') {
    $portfolio_id = $_GET['portfolio_id'];
    $type_        = $_GET['type'];
    if (!empty($portfolio_id)) {
        $transactions = SA_getAllTransactions($portfolio_id, $type_);

        $data = array(
            'portfolio_id'                    => $portfolio_id,
            'transactions'                    => $transactions,
            'status'                          => 200
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
