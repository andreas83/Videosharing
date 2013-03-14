<div class="row-fluid"><h1>Recent Videos</h1></div>
<?php
$video = new Video();
$data=$video->get_list(array("isConverted" => 1, "visibility_setting" => 1), 9);
$i=0;
foreach($data as $row) 
{
  $i++;
  if(($i%3)==0 or $i==1 or $i!=3)
      echo '<div class="row-fluid">';
?>

            <div class="span4">
              <a href="/video/view?id=<?=$row->id; ?>"><img src="/video/view/thumbnail?id=<?=$row->id; ?>&amp;width=200&amp;height=100" width="200" height="100" class="img-polaroid" alt="thumbnail of <?=$row->title; ?>" /></a>
              <h3><a href="/video/view?id=<?=$row->id; ?>"><?=$row->title; ?></a></h3>
            </div>
   
<?
  if(($i%3)==0)
      echo '</div>';

}
?>

