<?php
if ($f == 'add_ob') {
    $ob_array = $_GET['ob_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $error = '';
    if (!empty($ob_array) && !empty($portfolio_id)) {

        foreach ($ob_array as $ob_data) {
            if ($ob_data['ob_transaction_date'] === "NaN"){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Please Enter Date!";
                break;
            }
            if ($ob_data['ob_transaction_date'] > time()){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Future Dates not allowed!";
                break;
            }
            if ($ob_data['ob_transaction_price'] == 0){
                /* $errors[] = "Please Enter Price!"; */
                $error = "Please Enter Price!";
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
            $portfolio_data_added = AddOBToPortfolio($ob_array, $portfolio_id);
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
                    'ob_array' => $ob_array,
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
            'ob_array' => $ob_array,
            'portfolio_id' => $portfolio_id,
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
