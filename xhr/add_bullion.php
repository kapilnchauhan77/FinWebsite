<?php
if ($f == 'add_bullion') {
    $bullion_array = $_GET['bullion_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $auto_add = $_GET['auto_add'];
    $error = '';
    if (!empty($bullion_array) && !empty($portfolio_id)) {

        foreach ($bullion_array as $bullion_data) {
            if ($bullion_data['bullion_transaction_date'] === "NaN"){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Please Enter Date!";
                break;
            }
            if ($bullion_data['bullion_transaction_date'] > time()){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Future Dates not allowed!";
                break;
            }
            if ($bullion_data['bullion_transaction_price'] == 0){
                /* $errors[] = "Please Enter Price!"; */
                $error = "Please Enter Price!";
                break;
            }
            if ($bullion_data['bullion_transaction_qty'] == 0){
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
            $portfolio_data_added = AddBullionToPortfolio($bullion_array, $portfolio_id, $auto_add);
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
                    'bullion_array' => $bullion_array,
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
            'bullion_array' => $bullion_array,
            'portfolio_id' => $portfolio_id,
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
