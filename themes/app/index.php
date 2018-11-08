<?php include_header(); ?>

<section class="pb_cover_v3 overflow-hidden cover-bg-indigo cover-bg-opacity text-left pb_gradient_v1 pb_slant-light" id="section-home">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-6">
                <h2 class="heading mb-3"><?php _e( 'Vá de carona para a Faculdade!', VZR_TEXTDOMAIN ); ?></h2>
                <div class="sub-heading">
                    <p class="mb-4"><?php _e( 'Apenas colegas da faculdade podem ver e oferecer caronas. Confiável. Rápido. De graça.', VZR_TEXTDOMAIN ); ?></p>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5 relative align-self-center icon-as-logo">
                <i class="ion-android-car"></i>
            </div>
        </div>
    </div>
</section>
<!-- END section -->

<section class="pb_section bg-light pb_slant-white pb_pb-250" id="section-about">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-6 text-center mb-5">
                <h5 class="text-uppercase pb_font-15 mb-2 pb_color-dark-opacity-3 pb_letter-spacing-2">
                    <strong><?php _e( 'Saiba mais', VZR_TEXTDOMAIN ); ?></strong>
                </h5>
                <h2><?php _e( 'Como funciona?', VZR_TEXTDOMAIN ); ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md- col-sm-6">
                <div class="media d-block pb_feature-v1 text-left">
                    <div class="pb_icon">
                        <i class="ion-ios-email-outline pb_icon-gradient"></i>
                    </div>
                    <div class="media-body">
                        <h5 class="mt-0 mb-3 heading" data-match-height="about-title">
                            <?php _e( 'Crie uma conta', VZR_TEXTDOMAIN ); ?>
                        </h5>
                        <p class="text-sans-serif"><?php _e( 'Apenas alunos com o e-mail da Faculdade podem oferecer e pedir carona.', VZR_TEXTDOMAIN ); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md- col-sm-6">
                <div class="media d-block pb_feature-v1 text-left">
                    <div class="pb_icon">
                        <i class="ion-map pb_icon-gradient"></i>
                    </div>
                    <div class="media-body">
                        <h5 class="mt-0 mb-3 heading" data-match-height="about-title">
                            <?php _e( 'Pesquise uma rota', VZR_TEXTDOMAIN ); ?>
                        </h5>
                        <p class="text-sans-serif"><?php _e( 'Cadastre o ponto de partida e de chegada e procure por motoristas no seu caminho.', VZR_TEXTDOMAIN ); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md- col-sm-6">
                <div class="media d-block pb_feature-v1 text-left">
                    <div class="pb_icon">
                        <i class="ion-android-car pb_icon-gradient"></i>
                    </div>
                    <div class="media-body">
                        <h5 class="mt-0 mb-3 heading" data-match-height="about-title">
                            <?php _e( 'Pronto!', VZR_TEXTDOMAIN ); ?>
                        </h5>
                        <p class="text-sans-serif"><?php _e( 'O motorista vai receber uma notificação e aprovar a carona. Então avisaremos você.', VZR_TEXTDOMAIN ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END section -->

<section class="pb_section pb_slant-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-5">
                <img src="<?php get_theme_image( 'phone.png' ); ?>" alt="Image placeholder" class="img-fluid">
            </div>
            <div class="col-lg-8 pl-md-5 pl-sm-0">
                <div class="row">
                    <div class="col">
                        <h2><?php _e( 'Ligamos pessoas', VZR_TEXTDOMAIN ); ?></h2>
                        <p class="pb_font-20"><?php _e( 'Depois de ter a carona aprovada iremos liberar as informações de contato do motorista para o caroneiro e então eles poderão combinar os detalhes da carona.', VZR_TEXTDOMAIN ); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg">
                        <div class="media pb_feature-v2 text-left mb-1 mt-5" data-match-height="features-box">
                            <div class="pb_icon d-flex mr-3 align-self-start pb_w-15">
                                <i class="ion-ios-locked-outline pb_icon-gradient"></i>
                            </div>
                            <div class="media-body">
                                <h3 class="mt-2 mb-2 heading" data-match-height="features-title">
                                    <?php _e( 'Segurança', VZR_TEXTDOMAIN ); ?>
                                </h3>
                                <p class="text-sans-serif pb_font-16">
                                    <?php _e( 'As informações de contato só serão divulgadas quando a carona for aceita. Também é obrigatório ter um e-mail da Faculdade para fazer login.', VZR_TEXTDOMAIN ); ?>
                                </p>
                            </div>
                        </div>
                        <div class="media pb_feature-v2 text-left mb-1 mt-5" data-match-height="features-box">
                            <div class="pb_icon d-flex mr-3 align-self-start pb_w-15">
                                <i class="ion-merge pb_icon-gradient"></i>
                            </div>
                            <div class="media-body">
                                <h3 class="mt-2 mb-2 heading" data-match-height="features-title">
                                    <?php _e( 'Automatizado', VZR_TEXTDOMAIN ); ?>
                                </h3>
                                <p class="text-sans-serif pb_font-16">
                                    <?php _e( 'Quando um motorista cadastra sua rota, enviamos um alerta para eles avisando que existe uma nova possibilidade de carona.', VZR_TEXTDOMAIN ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="media pb_feature-v2 text-left mb-1 mt-5" data-match-height="features-box">
                            <div class="pb_icon d-flex mr-3 align-self-start pb_w-15">
                                <i class="ion-android-bus pb_icon-gradient"></i>
                            </div>
                            <div class="media-body">
                                <h3 class="mt-2 mb-2 heading" data-match-height="features-title">
                                    <?php _e( 'Economize o busão', VZR_TEXTDOMAIN ); ?>
                                </h3>
                                <p class="text-sans-serif pb_font-16">
                                    <?php _e( 'Pegar uma carona com um colega é muito melhor do que vir apertado no busão. Aproveite e faça amizade com o motorista.', VZR_TEXTDOMAIN ); ?>
                                </p>
                            </div>
                        </div>
                        <div class="media pb_feature-v2 text-left mb-1 mt-5" data-match-height="features-box">
                            <div class="pb_icon d-flex mr-3 align-self-start pb_w-15">
                                <i class="ion-android-car pb_icon-gradient"></i>
                            </div>
                            <div class="media-body">
                                <h3 class="mt-2 mb-2 heading" data-match-height="features-title">
                                    <?php _e( 'Seja um motorista', VZR_TEXTDOMAIN ); ?>
                                </h3>
                                <p class="text-sans-serif pb_font-16">
                                    <?php _e( 'Além de ter a confiança de transportar um colega, vocês podem rachar a gasolina e diminuir as despesas com o carro. Além de uma boa conversa, é claro!', VZR_TEXTDOMAIN ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END section -->

<section class="pb_section pb_slant-white" id="section-faq">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-6 text-center mb-5">
                <h5 class="text-uppercase pb_font-15 mb-2 pb_color-dark-opacity-3 pb_letter-spacing-2">
                    <strong>FAQ</strong>
                </h5>
                <h2><?php _e( 'Perguntas Frequentes', VZR_TEXTDOMAIN ); ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div id="pb_faq" class="pb_accordion" data-children=".item">
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#pb_faq" href="#pb_faq1" aria-expanded="true" aria-controls="pb_faq1" class="pb_font-22 py-4">
                            <?php _e( 'Como crio minha conta?', VZR_TEXTDOMAIN ); ?>
                        </a>
                        <div id="pb_faq1" class="collapse show" role="tabpanel">
                            <div class="py-3">
                            <p><?php _e( 'Basta clicar no botão no topo da página entrar com o seu e-mail da Fametro. Lembre-se: ele é mais ou menos assim: <strong>nome.sobrenome@aluno.fametro.com.br</strong>.', VZR_TEXTDOMAIN ); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#pb_faq" href="#pb_faq2" aria-expanded="false" aria-controls="pb_faq2" class="pb_font-22 py-4">
                            <?php _e( 'Quero ser um motorista. O que preciso?', VZR_TEXTDOMAIN ); ?>
                        </a>
                        <div id="pb_faq2" class="collapse" role="tabpanel">
                            <div class="py-3">
                                <p><?php _e( 'Você precisa ter um carro ou moto e a habilitação necessária para conduzí-lo, conforme a legislação. Lembre-se que além de você mesmo, o seu colega é sua responsabilidade também.', VZR_TEXTDOMAIN ); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#pb_faq" href="#pb_faq3" aria-expanded="false" aria-controls="pb_faq3" class="pb_font-22 py-4">
                            <?php _e( 'Quero uma carona. Preciso pagar?', VZR_TEXTDOMAIN ); ?>

                        </a>
                        <div id="pb_faq3" class="collapse" role="tabpanel">
                            <div class="py-3">
                                <p><?php _e( 'Não somos um aplicativo de motoristas, mas sim uma plataforma para unir colegas que moram perto. Você e seu motorista são livres para combinarem o que quiserem.', VZR_TEXTDOMAIN ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="section-contact" class="pb_xl_py_cover overflow-hidden pb_slant-light pb_gradient_v1 cover-bg-opacity-8"  style="background-image: url(<?php get_theme_image( 'placeholder/1900x1200_img_5.jpg' ); ?>)">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-5 justify-content-center">
                <h2 class="heading mb-5 pb_font-40"><?php _e( 'Fale com o desenvolvedor!', VZR_TEXTDOMAIN ); ?></h2>
                <div class="sub-heading">
                    <p class="mb-4"><?php _e( 'Ainda ficou com dúvida? Mande uma mensagem para a gente que prometemos tentar responder o mais rápido possível.', VZR_TEXTDOMAIN ); ?></p>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
                 <form action="/ajax/contato" method="POST" class="ajax-form bg-white rounded pb_form_v1">
                    <div class="form-group">
                        <input name="contact-name" type="text" class="form-control py-3 reverse" placeholder="<?php _e( 'Seu nome', VZR_TEXTDOMAIN ); ?>" required>
                    </div>
                    <div class="form-group">
                        <input name="contact-email" type="mail" class="form-control py-3 reverse" placeholder="<?php _e( 'Seu e-mail', VZR_TEXTDOMAIN ); ?>" required>
                    </div>
                    <div class="form-group">
                        <textarea name="contact-message" class="form-control py-3 reverse" rows="6" placeholder="<?php _e( 'Digite sua mensagem.', VZR_TEXTDOMAIN ); ?>" required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-lg btn-block pb_btn-pill  btn-shadow-blue" value="<?php _e( 'Enviar', VZR_TEXTDOMAIN ); ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- END section -->

<?php include_footer();
