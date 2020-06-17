<?php 
if ($f == 'extra_graphs_data') {
    $fincode = $_GET['fincode'];
    $extra_g_d = $_GET['extra_g_d'];

    if (!empty($fincode)) {
        $get_extra_graph_data = get_extra_graph_data($fincode, $extra_g_d);
        if (!empty($get_extra_graph_data)) {
            $data = array(
                'data' => $get_extra_graph_data,
                'status' => 200,
            );
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        } else {
            $data = array(
                'status' => 400,
                'fincode' => $fincode,
                'extra_g_d' => $extra_g_d,
            );
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        };
    } else {
        $data = array(
            'status' => 404,
            'hash_id' => Wo_CheckMainSession($hash_id)
        );
    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
