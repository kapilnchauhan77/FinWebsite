<?php
if ($f == 'add_oa') {
    $oa_array = $_GET['oa_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $error = '';
    if (!empty($oa_array) && !empty($portfolio_id)) {

        foreach ($oa_array as $oa_data) {
            if ($oa_data['oa_type'] === ""){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Please Enter Name of your Asset!";
                break;
            }
            if ($oa_data['oa_transaction_date'] === "NaN"){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Please Enter Date!";
                break;
            }
            if ($oa_data['oa_transaction_date'] > time()){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Future Dates not allowed!";
                break;
            }
            if ($oa_data['oa_transaction_price'] == 0){
                /* $errors[] = "Please Enter Price!"; */
                $error = "Please Enter Price!";
                break;
            }
            if ($oa_data['oa_current_price'] == 0){
                /* $errors[] = "Please Enter Quantity!"; */
                $error = "Please Enter Current Value of your Investments!";
                break;
            }
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
            $portfolio_data_added = AddOAToPortfolio($oa_array, $portfolio_id);
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
                    'oa_array' => $oa_array,
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
            'oa_array' => $oa_array,
            'portfolio_id' => $portfolio_id,
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
