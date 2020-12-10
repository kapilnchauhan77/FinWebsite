<?php
if ($f == 'add_cash') {
    $cash_array = $_GET['cash_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $available_cash = $_GET['available_cash'];
    $error = '';
    if (!empty($cash_array) && !empty($portfolio_id)) {

        foreach ($cash_array as $cash_data) {
            if ($cash_data['cash_transaction_date'] === "NaN"){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Please Enter Date!";
                break;
            }
            if ($cash_data['cash_transaction_date'] > time()){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Future Dates not allowed!";
                break;
            }
            if ($cash_data['cash_transaction_price'] == 0){
                /* $errors[] = "Please Enter Price!"; */
                $error = "Please Enter Price!";
                break;
            }
            if ($cash_data['cash_type'] == "Debit" && ((-1 * $cash_data['cash_transaction_price']) > $available_cash)){
                /* $errors[] = "Please Enter Price!"; */
                $error = "Available cash to withdraw: {$available_cash}₹";
                break;
            }
            /* if ($cash_data['cash_transaction_price'] < 0){ */
            /*     $error = "Please Enter Postive Price Only!"; */
            /*     break; */
            /* } */
        }

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
            $internal_note = ($cash_data['cash_type'] == "Debit") ? "Withdrew Cash Manually" : "Deposited Cash Manually";
            $portfolio_data_added = AddCashToPortfolio($cash_array, $portfolio_id, $internal_note);
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
                    'cash_array' => $cash_array,
                    'portfolio_id' => $portfolio_id,
                );

                header("Content-type: application/json");
                echo json_encode($data);
                exit();
            }
        }
    } else {
        $data = array(
            'status' => 404,
            'cash_array' => $cash_array,
            'portfolio_id' => $portfolio_id,
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
