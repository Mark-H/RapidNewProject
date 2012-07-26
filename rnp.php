<?php

/* Poor man's config */
$options = array(

);
$modxRoots = array(
    1 => array(
        'name' => 'Latest Stable',
        'path' => dirname(dirname(dirname(__FILE__))) . '/modx-stable/'
    )
);

require_once 'rnp.class.php';
$rnp = new RapidNewProject($options, $modxRoots);

var_dump($rnp->config);

if (!isset($_POST['rnp_submit'])) {
    echo $rnp->showForm(array());
}
