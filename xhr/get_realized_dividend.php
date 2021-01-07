<?php
if ($f == 'get_realized_dividend') {
    $portfolio_id = $_GET['portfolio_id'];
    $type_ = $_GET['type_'];
    if (!empty($portfolio_id)) {
        $realized_gains = SA_getPortfolioRealizedGain($portfolio_id, $type_);
        $dividend_amount = SA_getPortfolioDividend($portfolio_id, $type_);

        $data = array(
            'portfolio_id'                    => $portfolio_id,
            'realized_gains'                  => $realized_gains,
            'dividend_amount'                 => $dividend_amount,
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
