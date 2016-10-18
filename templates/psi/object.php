<?php foreach ($view as $subView) {
    ?>
    <div>
        <?php echo $renderer->render($subView); ?>
    </div>
<?php 
} ?>
