<?php
if ($f == 'portfolio_stock_extra_data') {
    $stock_fincode = $_GET['stock_fincode'];
    $portfolio_id = $_GET['portfolio_id'];
    if (!empty($portfolio_id) && !empty($stock_fincode)) {
        $extraStockData = Wo_ExtraStockDetailInPortfolio($stock_fincode, $portfolio_id);
        if (!empty($extraStockData)){

            $data = array(
                'extra_stock_data' => $extraStockData,
                'status' => 200
            );

            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }else{

            $data = array(
                'status' => 400,
                'portfolio_id' => $portfolio_id,
                'stock_fincode' => $stock_fincode
            );

            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    } else {
        $data = array(
            'status' => 400,
            'stock_fincode' => $stock_fincode,
            'portfolio_id' => $portfolio_id
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
