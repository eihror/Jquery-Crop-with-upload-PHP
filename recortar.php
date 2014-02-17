<?php
error_reporting();
/*
 * Nessa pagina é onde iremos realizar o crop da imagem e salvar na pasta com o complemento _thumb no nome
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
        // Pego o arquivo que tinha sido enviado com o diretorio. ex.: nomedapasta/nomedaimagem.extensao
        $nome           = $_POST['arquivo'];
        // Realizo um explode no nome
        $file           = explode('.', $nome);
        // E assim pego o nome e a extensão
        $nomeArquivo    = $file[0];
        $ext            = $file[1];
        //Pego também a altura e largura que será usada para o crop
	$targ_w         = $_POST['w'];
        $targ_h         = $_POST['h'];
        // E caso a imagem seja jpg irei definir a qualidade da imagem ao máximo
	$jpeg_quality   = 100;
        
        // Repeti a definição do nome da imagem para facilitar o entendimento
	$src = $_POST['arquivo'];
        
        if($ext == 'jpg'){
            // Caso a imagem seja jpg, cria uma imagem jpg
            $img_r = imagecreatefromjpeg($src);
        }else{
            // Caso a imagem seja png, cria uma imagem png
            $img_r = imagecreatefrompng($src);
        }
        
        // Crio a imagem seguindo os tamanhos desejados inseridos previamente no Jquery e enviado para cá
	$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
        
        // E assim realizo o crop na imagem passando o tamanho real dela o tamanho que ficará e qual a area que ficará na imagem nova
	imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);
        
        // Então assim crio a imagem dentro da mesma pasta com a extensão correta e com o complemento _thumb
        if($ext == 'jpg'){
            $imagem = imagejpeg($dst_r, $nomeArquivo.'_thumb.'.$ext ,$jpeg_quality);
        }else{
            $imagem = imagepng($dst_r, $nomeArquivo.'_thumb.'.$ext);
        }
        
        // E exibo na página o a mensagem que está realizado com sucesso
        echo 'imagem recortada com sucesso!<br />';
        echo '<a href="index.php">Voltar</a>';
}

?>