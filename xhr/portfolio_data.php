<?php
if ($f == 'portfolio_data') {
    $stock_array = $_GET['stock_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $no_of_stocks = $_GET['no_of_stocks'];
    $no_of_unique_stocks = $_GET['no_of_unique_stocks'];
    if (!empty($stock_array) && !empty($portfolio_id) && !empty($no_of_stocks)) {
        $portfolio_data_added = AddStocksToPortfolio($stock_array, $portfolio_id, $no_of_stocks, $no_of_unique_stocks);
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
                'stock_array' => $stock_array,
                'portfolio_id' => $portfolio_id,
                'no_of_stocks' => $no_of_stocks
            );

            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    } else {
        $data = array(
            'status' => 400,
            'stock_array' => $stock_array,
            'portfolio_id' => $portfolio_id
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
