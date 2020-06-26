</div>
<div class="container-fluid footer bg-dark text-white text-center">
    <div class="row">
        <?php if ($is_connected) { ?>
            <div class="col-12">FOOTER CONNECTE</div>
        <?php } else { ?>
            <div class="col-12">FOOTER NON CONNECTE</div>
        <?php } ?>
    </div>
</div>
<script src="js/jquery.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.js"></script>
<?php if(isset($scripts) && !empty($scripts)){
    foreach ($scripts as $script) { ?>
        <script src="js/<?php echo $script; ?>"></script>
<?php } } ?>
</body>
</html>