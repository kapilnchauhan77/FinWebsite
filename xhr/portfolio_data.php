<?php
if ($f == 'portfolio_data') {
    $stock_array = $_GET['stock_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $no_of_stocks = $_GET['no_of_stocks'];
    $auto_add = $_GET['auto_add'];
    $error = '';
    if (!empty($stock_array) && !empty($portfolio_id)) {

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

        if ($auto_add != '0' && $auto_add != '1') $error = 'Please do not change system files!';

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
            $portfolio_data_added = AddStocksToPortfolio($stock_array, $portfolio_id, $no_of_stocks, $auto_add);
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
