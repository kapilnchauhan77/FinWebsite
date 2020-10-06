<?php
if ($f == 'historical_data_cache') {
    $portfolio_id = $_GET['portfolio_id'];
    $timeline = $_GET['timeline'];
    if (!empty($portfolio_id)) {
        $fincodes = Wo_GetStocksInPortfolio($portfolio_id);

        if (count($fincodes) > 0) {
            $company_data = array();
            foreach ($fincodes as $fincode) {
                $company_data[] = Wo_GetHistoricalData($fincode, $timeline);
            }
            $data = array(
                'stock_data' => $company_data,
                'status' => 200
            );
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        } else {
            $data = array(
                'status' => 400,
            );
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        };

    } else {
        $data = array(
            'status' => 400,
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
