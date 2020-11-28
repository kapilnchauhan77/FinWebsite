<?php
if ($f == 'add_fd') {
    $fd_array = $_GET['fd_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $error = '';
    if (!empty($fd_array) && !empty($portfolio_id)) {

        foreach ($fd_array as $fd_data) {
            if ($fd_data['fd_transaction_date'] === "NaN"){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Please Enter Date!";
                break;
            }
            if ($fd_data['fd_transaction_date'] > time()){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Future Dates not allowed!";
                break;
            }
            if ($fd_data['fd_transaction_price'] == 0){
                /* $errors[] = "Please Enter Price!"; */
                $error = "Please Enter Price!";
                break;
            }
            if ($fd_data['fd_maturity_date'] == "NaN"){
                /* $errors[] = "Please Enter Quantity!"; */
                $error = "Please Enter Maturity time!";
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
            $portfolio_data_added = AddFDToPortfolio($fd_array, $portfolio_id);
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
                    'fd_array' => $fd_array,
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
            'fd_array' => $fd_array,
            'portfolio_id' => $portfolio_id,
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
