<div class="row-fluid"><h1>Recent Videos</h1></div>
<?php
$video = new Video();
$data=$video->get_list(array("isConverted" => 1, "visibility_setting" => 1), 6);
$i=0;
foreach($data as $row) 
{
  $i++;
  if(($i%3)==0 or $i==1 or $i!=3)
      echo '<div class="row-fluid">';
?>

            <div class="span4">
              
              <img src="/public/video/<?=$row->user_id;?>/<?=$row->id; ?>/thumb<?=$row->thumb; ?>.png" width="100" class="img-rounded">
              <h2><?=$row->title; ?></h2>
              <p><a class="btn" href="/video/view?id=<?=$row->id; ?>">View Video </a></p>
            </div><!--/span-->
   
<?
  if(($i%3)==0)
      echo '</div>';

}
?>

