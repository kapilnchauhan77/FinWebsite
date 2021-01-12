<?php
if ($f == 'add_property') {
    $property_array = $_GET['property_array'];
    $portfolio_id = $_GET['portfolio_id'];
    $auto_add = $_GET['auto_add'];
    $error = '';
    if (!empty($property_array) && !empty($portfolio_id)) {

        if ($auto_add == '2'){
            if (($property_array['property_transaction_price'] < 0) || (!$property_array['property_transaction_date'])){
                /* $errors[] = "Please Enter Quantity!"; */
                $error = "Please Do Not Change System files!";
            }
        }
        else if ($auto_add == '0' || $auto_add == '1'){
            foreach ($property_array as $property_data) {
                if ($property_data['property_transaction_date'] === "NaN"){
                    /* $errors[] = "Please Enter Date!"; */
                    $error = "Please Enter All Purchase Dates!";
                    break;
                }
                if ($property_data['property_transaction_date'] > time()){
                    /* $errors[] = "Please Enter Date!"; */
                    $error = "Future Dates Not Allowed!";
                    break;
                }
                if ($property_data['property_transaction_price'] == 0){
                    /* $errors[] = "Please Enter Price!"; */
                    $error = "Please Enter All Invested values";
                    break;
                }
                if ($property_data['property_current_price'] < 0){
                    /* $errors[] = "Please Enter Price!"; */
                    $error = "Please Enter Correct Current Value Of All Your Properties!";
                    break;
                }
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

            if ($auto_add == '2') $portfolio_data_added = SellPropertyFromPortfolio($property_array, $portfolio_id);
            else if ($auto_add == '3') $portfolio_data_added = UpdatePropertyFromPortfolio($property_array, $portfolio_id);
            else $portfolio_data_added = AddPropertyToPortfolio($property_array, $portfolio_id, $auto_add);

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
