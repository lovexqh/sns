<div data-role="collapsible-set" data-theme="c" data-content-theme="b">
    <?php
    foreach ($data as $v) {
        ?>
        <div data-role="collapsible">
            <h3><?php echo date('Y-m-d',$v['hTime']).'—'.$v['title'];?></h3>
            <p><?php echo $v['content'];?></p>
        </div>
    <?php
    }
    ?>
</div>

