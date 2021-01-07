<?php
if ($f == 'add_mutual_funds') {
    $mf_array = $_GET['mf_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $auto_add = $_GET['auto_add'];
    $mf_available = $_GET['mf_available'];
    $error = '';
    if (!empty($mf_array) && !empty($portfolio_id)) {

        if ($auto_add == '2'){
            if (((-1 * $mf_array['mf_transaction_qty']) > $mf_available) || (($mf_array['mf_transaction_price']) < 0) || (!$mf_array['mf_transaction_date'])){
                /* $errors[] = "Please Enter Quantity!"; */
                $error = "Please Do Not Change System files!";
            }
        }
        else if ($auto_add == '3'){
            if (($mf_array['date'] > time()) || (($mf_array['dividend_amount']) < 0) || (!$mf_array['date'])){
                /* $errors[] = "Please Enter Quantity!"; */
                $error = "Please Do Not Change System files";
            }
        }
        else if ($auto_add == '0' || $auto_add == '1'){
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
            if ($auto_add == '2') $portfolio_data_added = SellMFFromPortfolio($mf_array, $portfolio_id, $mf_available);
            else if ($auto_add == '3') $portfolio_data_added = AddDividendMFToPortfolio($mf_array, $portfolio_id);
            else $portfolio_data_added = AddMFToPortfolio($mf_array, $portfolio_id, $auto_add);

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
