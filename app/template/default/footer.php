</div>





<script>
document.write('<script src=' +
('__proto__' in {} ? '<?php echo Config::get('address'); ?>/public/js/zepto' : '<?php echo Config::get('address'); ?>/public/js/jquery') +
'.js><\/script>')
</script>

<script src="<?php echo Config::get('address'); ?>/public/js/foundation.min.js"></script>
  <script>
    $(document).foundation();
  </script>



<?php
if (isset($view->CSS))
    echo $view->CSS;

if (isset($view->JS))
    echo $view->JS;
?>



</body></html>