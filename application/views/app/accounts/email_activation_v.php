<?php
    $styles['main'] = "font-family: 'Trebuchet MS', Helvetica, sans-serif; text-align: center; background-color: #FAFAFA; padding-top: 20px; margin: 0px";
    $styles['h1'] = "color: #9C0062; margin-top: 50px;";
    $styles['h3'] = "";
    $styles['p'] = "";
    $styles['a'] = "";
    $styles['button'] = "padding: 1em; background-color: #ffc107; color: #FFF; text-decoration: none; display: block; min-width: 120px; max-width: 240px; margin: 0 auto; border-radius: 0.25em;";
    $styles['footer'] = "margin-top: 50px; color: #AAAAAA; font-size: 0.7em; border-top: 1px solid #DDD; padding: 1em;";
?>
<?php
    $texts['title'] = APP_NAME;
    $texts['paragraph'] = 'Para activar tu cuenta haz clic en el siguiente enlace:';
    $texts['button'] = 'ACTIVAR';
    $texts['link'] = "accounts/activation/{$row_user->activation_key}";
    
    if ( $activation_type == 'recovery' ) 
    {
        $texts['title'] = APP_NAME;
        $texts['paragraph'] = 'Para reestablecer tu contraseña haz clic en el siguiente enlace:';
        $texts['button'] = 'RESTAURAR CONTRASEÑA';
        $texts['link'] = "accounts/recover/{$row_user->activation_key}";
    }
?>
<div style="<?= $styles['main'] ?>">
    <h1 style="<?= $styles['h1'] ?>"><?= $texts['title'] ?></h1>
    <h3><?= $row_user->display_name ?></h3>
    <p><?= $texts['paragraph'] ?></p>
    <br>
    <a style="<?= $styles['button'] ?>" href="<?= URL_APP . $texts['link'] ?>" target="_blank">
        <?= $texts['button'] ?>
    </a>
    <footer style="<?= $styles['footer'] ?>">Creado por Pacarina Media Lab</footer>
</div>