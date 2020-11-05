<?php
if ($f == 'get_share_portfolio') {
    $data['status'] = 400;
    if (!empty($_GET['portfolio_id'])) {
        $wo['portfolio'] = Wo_PortfolioCaching(Wo_Secure($_GET['portfolio_id']), false);
        if (!empty($wo['portfolio'])) {
            $data['html'] = Wo_LoadPage('lightbox/share_portfolio');
            $data['status'] = 200;
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
