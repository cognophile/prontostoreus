<?php $this->layout = 'InvoiceComponent.default'; ?>
<?php 
    foreach ($data as $key => $value) {
        ?><p> <?= $value ?> </p>
    <?php
    }  
?>


