<?php 
$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("pipe", "w"),  // stderr is a file to write to
);
$pipes = array();

$process = proc_open('git describe --tags', $descriptorspec, $pipes);
if (is_resource($process)) {
    $tag = stream_get_contents($pipes[1]);
    $error = stream_get_contents($pipes[2]);
    if (!empty($error)) {
        echo 'ERROR: ' . $error;
        return;
    }

    echo $tag;

} else {
    echo 'ERROR';
}
