
<div class="row pt-5 mt-5">
    <div class="col-12">
        <div class='card'>
        <?php
        if (isset($_REQUEST['content'])) {
    echo        base64_decode($_REQUEST['content']);

            }?>
        </div>
    </div>
</div>
<?php


?>