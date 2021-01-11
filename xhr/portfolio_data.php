<?php
if ($f == 'portfolio_data') {
    $stock_array = $_GET['stock_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $no_of_stocks = $_GET['no_of_stocks'];
    $auto_add = $_GET['auto_add'];
    $stocks_available = $_GET['stocks_available'];
    $error = '';
    if (!empty($stock_array) && !empty($portfolio_id)) {

        if ($auto_add == '2'){
            if (((-1 * $stock_array['stock_transaction_qty']) > $stocks_available) || (($stock_array['stock_transaction_price']) < 0) || (!$stock_array['stock_transaction_date'])){
                /* $errors[] = "Please Enter Quantity!"; */
                $error = "Please Do Not Change System files";
            }
        }
        else if ($auto_add == '3'){
            if (($stock_array['date'] > time()) || (($stock_array['dividend_amount']) < 0) || (!$stock_array['date'])){
                /* $errors[] = "Please Enter Quantity!"; */
                $error = "Please Do Not Change System files";
            }
        }
        else if ($auto_add == '0' || $auto_add == '1'){
            foreach ($stock_array as $stock_data) {
                if ($stock_data['stock_transaction_date'] === "NaN"){
                    /* $errors[] = "Please Enter Date!"; */
                    $error = "Please Enter Date!";
                    break;
                }
                if ($stock_data['stock_transaction_date'] > time()){
                    /* $errors[] = "Please Enter Date!"; */
                    $error = "Future Dates not allowed!";
                    break;
                }
                if ($stock_data['stock_transaction_price'] == 0){
                    /* $errors[] = "Please Enter Price!"; */
                    $error = "Please Enter Price!";
                    break;
                }
                if ($stock_data['stock_transaction_qty'] == 0){
                    /* $errors[] = "Please Enter Quantity!"; */
                    $error = "Please Enter Quantity!";
                    break;
                }
            }
        } else $error = 'Please do not change system files!';

        if ($error !== ''){

                $data = array(
                    'error' => $error,
                    'status' => 401
                );

                header("Content-type: application/json");
                echo json_encode($data);
                exit();
        }
        else{

            if ($auto_add == '2') $portfolio_data_added = SellStocksFromPortfolio($stock_array, $portfolio_id, $stocks_available);
            else if ($auto_add == '3') $portfolio_data_added = AddDividendStocksToPortfolio($stock_array, $portfolio_id);
            else $portfolio_data_added = AddStocksToPortfolio($stock_array, $portfolio_id, $no_of_stocks, $auto_add);

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
        }
    } else {
        $data = array(
            'status' => 404,
            'stock_array' => $stock_array,
            'portfolio_id' => $portfolio_id,
            'no_of_stocks' => $no_of_stocks
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
