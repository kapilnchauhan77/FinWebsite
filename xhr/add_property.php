<?php
if ($f == 'add_property') {
    $property_array = $_GET['property_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $error = '';
    if (!empty($property_array) && !empty($portfolio_id)) {

        foreach ($property_array as $property_data) {
            if ($property_data['property_transaction_date'] === "NaN"){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Please Enter Date!";
                break;
            }
            if ($property_data['property_transaction_date'] > time()){
                /* $errors[] = "Please Enter Date!"; */
                $error = "Future Dates not allowed!";
                break;
            }
            if ($property_data['property_transaction_price'] == 0){
                /* $errors[] = "Please Enter Price!"; */
                $error = "Please Enter Price!";
                break;
            }
            if ($property_data['property_transaction_qty'] == 0){
                /* $errors[] = "Please Enter Quantity!"; */
                $error = "Please Enter Quantity!";
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
            $portfolio_data_added = AddPropertyToPortfolio($property_array, $portfolio_id);
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
                    'property_array' => $property_array,
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
            'property_array' => $property_array,
            'portfolio_id' => $portfolio_id,
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
