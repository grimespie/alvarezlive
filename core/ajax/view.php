<?php

$Video = DHMR_Video('video_id = ' . $_POST["video_id"]);
$Video[0]->view_count++;

$Status = DHMU_Video(array("view_count" => $Video[0]->view_count), array("video_id" => $_POST["video_id"]));

?>