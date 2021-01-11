<?php
if ($f == 'other_stock_transactions') {
    $portfolio_id = $_GET['portfolio_id'];
    $type = $_GET['type'];
    if (!empty($portfolio_id)) {
        switch ($type){
            case 'stocks':
                $other_transactions = SA_otherStockTransactions($portfolio_id);
                break;
            case 'mutual_funds':
                $other_transactions = SA_otherMFTransactions($portfolio_id);
                break;
        }
        $data = array(
            'portfolio_id'                    => $portfolio_id,
            'other_stock_transactions'        => $other_transactions,
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
