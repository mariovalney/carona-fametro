        <footer class="pb_footer bg-light" role="contentinfo">
            <div class="container">
                <div class="row text-center">
                    <div class="col">
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <a href="https://bitbucket.org/valney-team/carona-fametro" class="p-2">
                                    <i class="fa fa-github"></i><br>
                                    <span class="pb_font-14">
                                        <?php _e( 'Contribua!', VZR_TEXTDOMAIN ); ?>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <p class="pb_font-14"><?php echo date( 'Y' ) . ' &copy; ' . __( 'Carona Fametro. Todos os direitos reservados', VZR_TEXTDOMAIN ); ?></p>
                        <p class="pb_font-14"><?php printf( __( 'Desenvolvido por %s utilizando %s. Design por %s.', VZR_TEXTDOMAIN ), '<a href="https://mariovalney.com.br">Mário Valney</a>', '<a href="https://projetos.mariovalney.com/avant/">Avant</a>', '<a href="https://uicookies.com/">uicookies.com</a>' ); ?></p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- loader -->
        <div id="pb_loader" class="show fullscreen">
            <svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#1d82ff"/></svg>
        </div>

        <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API; ?>" type="text/javascript"></script>

        <?php $js = ( defined( 'DEBUG' ) && DEBUG ) ? '.js' : '.min.js'; ?>
        <script type="text/javascript" src="<?php theme_file_url( 'dist/js/scripts' . $js, true, true ) ?>"></script>
        <script type="text/javascript">
            var CF = CF || {};
            CF.campi = JSON.parse('<?php echo json_encode( \Avant\Modules\Entities\Route::valid_campi() ); ?>');
            CF.markers = {
                campus: '<?php get_theme_image( 'map-icon-campus.png' ) ?>',
                start: '<?php get_theme_image( 'map-icon-start.png' ) ?>',
                return: '<?php get_theme_image( 'map-icon-return.png' ) ?>',
                one: '<?php get_theme_image( 'map-icon-one.png' ) ?>',
                two: '<?php get_theme_image( 'map-icon-two.png' ) ?>',
            };
        </script>
    </body>
</html>