<?php

/* Poor man's config */
$options = array(

);
$modxRoots = array(
    1 => array(
        'name' => 'Latest Stable',
        'path' => dirname(dirname(dirname(__FILE__))) . '/modx-stable/'
    ),
    2 => array(
        'name' => 'modx-stable2',
        'path' => dirname(dirname(dirname(__FILE__))) . '/modx-stable2/'
    )
);

require_once 'rnp.class.php';
$rnp = new RapidNewProject($options, $modxRoots);

var_dump($rnp->config);

if (!isset($_POST['rnp_submit'])) {
    echo $rnp->showForm(array());
} else {
    $buildStatus = $rnp->build($_POST);
    if ($buildStatus !== true) {
        echo '<p class="error">'.$buildStatus.'</p>';
        echo $rnp->showForm($_POST);
    } else {
        echo $rnp->showForm(array());
    }
}
