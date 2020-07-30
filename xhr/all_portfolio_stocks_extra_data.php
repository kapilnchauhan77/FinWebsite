<?php
if ($f == 'all_portfolio_stocks_extra_data') {
    $portfolio_id = $_GET['portfolio_id'];
    if (!empty($portfolio_id)) {
        $extraStockDataFullPortfolio = Wo_ExtraStockDetailForAllStocksInPortfolio($portfolio_id);
        if (!empty($extraStockDataFullPortfolio)){

            $data = array(
                'portfolio_id'                => $portfolio_id,
                'extraStockDataFullPortfolio' => $extraStockDataFullPortfolio,
                'status'                      => 200
            );

            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }else{

            $data = array(
                'status' => 400,
                'portfolio_id' => $portfolio_id,
                'message' => 'found_empty'
            );

            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
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
