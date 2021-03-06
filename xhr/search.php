<?php
if ($f == "search") {
    $data = array(
        'status' => 200,
        'html' => ''
    );
    if ($s == 'recipients' AND $wo['loggedin'] == true && isset($_GET['query'])) {
        foreach (Wo_GetMessagesUsers($wo['user']['user_id'], $_GET['query']) as $wo['recipient']) {
            $data['html'] .= Wo_LoadPage('messages/messages-recipients-list');
        }
    }
    if ($s == 'normal' && isset($_GET['query'])) {
        $data['query'] = array();
        foreach (Wo_GetSearch($_GET['query']) as $wo['result']) {
            $data['html'] .= Wo_LoadPage('header/search');
            // $data['query'][] = $wo['result'];
        }
    }
    if ($s == 'mutual_fund' && isset($_GET['query'])) {
        $data['query'] = array();
        foreach (SA_GetMFSearch($_GET['query']) as $wo['result']) {
            $data['html'] .= Wo_LoadPage('addMFPortfolio/mf_search_selection');
        }
    }
    if ($s == 'stock_quote' && isset($_GET['query'])) {
        $data['query'] = array();
        foreach (SA_GetStock_Quote_Search($_GET['query']) as $wo['result']) {
            $data['html'] .= Wo_LoadPage('portfolio/stock_search_selection');
        }
    }
    if ($s == 'hash' && isset($_GET['query'])) {
        foreach (Wo_GetSerachHash($_GET['query']) as $wo['result']) {
            $data['html'] .= Wo_LoadPage('header/hashtags-result');
        }
    }
    if ($s == 'recent' && $wo['loggedin'] == true) {
        foreach (Wo_GetRecentSerachs() as $wo['result']) {
            if (!empty($wo['result'])) {
                $data['html'] .= Wo_LoadPage('header/search');
            }
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
