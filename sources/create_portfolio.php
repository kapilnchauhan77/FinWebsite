<?php
if ($wo['loggedin'] == false) {
  header("Location: " . Wo_SeoLink('index.php?link1=welcome'));
  exit();
}
$wo['description'] = $wo['config']['siteDesc'];
$wo['keywords']    = $wo['config']['siteKeywords'];
$wo['page']        = 'create_portfolio';
$wo['title']       = 'Create Portfolio';
$wo['content']     = Wo_LoadPage('portfolio/create-portfolio');