<?php
if ($f == 'add_mutual_funds') {
    $mf_array = $_GET['mf_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $auto_add = $_GET['auto_add'];
    $error = '';
    if (!empty($mf_array) && !empty($portfolio_id)) {

        foreach ($mf_array as $mf_data) {
            if ($mf_data['mf_transaction_date'] === "NaN"){
                $error = "Please Enter Date!";
                break;
            }
            if ($mf_data['mf_transaction_date'] > time()){
                $error = "Future Dates not allowed!";
                break;
            }
            if ($mf_data['mf_transaction_price'] == 0){
                $error = "Please Enter Price!";
                break;
            }
            if ($mf_data['mf_transaction_qty'] == 0){
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
            $portfolio_data_added = AddMFToPortfolio($mf_array, $portfolio_id, $auto_add);
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
                    'mf_array' => $mf_array,
                    'portfolio_id' => $portfolio_id,
                    'no_of_mf' => $no_of_mf
                );

                header("Content-type: application/json");
                echo json_encode($data);
                exit();
            }
        }
    } else {
        $data = array(
            'status' => 404,
            'mf_array' => $mf_array,
            'portfolio_id' => $portfolio_id,
            'no_of_mf' => $no_of_mf
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
