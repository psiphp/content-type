<ul>
    <li>
        <?php foreach ($view as $viewElement) {
    ?>
            <?php echo $renderer->render($viewElement) ?>
        <?php 
} ?>
    </li>
</ul>
