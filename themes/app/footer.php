        <?php $js = ( defined( 'DEBUG' ) && DEBUG ) ? '.js' : '.min.js'; ?>
        <script type="text/javascript" src="<?php theme_file_url( 'dist/js/scripts' . $js, true, true ) ?>"></script>
    </body>
</html>