<?php
if ($f == 'get_share_portfolio') {
    $portfolio_url = $_GET['portfolio_url'];
    if (!empty($portfolio_url)) {
        $wo['portfolio_url'] = $portfolio_url;

        $data = array(
            'html' => Wo_LoadPage('lightbox/share_portfolio'),
            'status' => 200
        );

        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    } else {

        $data = array(
            'status' => 400,
            'portfolio_url' => $portfolio_url
        );

    };
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
