<?php
    error_reporting();
 ?>   
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8" /> 
    <title>Crop de imagem com PHP e Jquery</title>
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/jquery.Jcrop.js" type="text/javascript"></script>
    <script src="js/jquery.color.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery.Jcrop.extras.css" type="text/css" />
    <link rel="stylesheet" href="img/demos.css" type="text/css" />
    
    <style>
        /*O grande truque é deixar a imagem que será exibida sob o crop fique 100% 
        em relação ao layout assim não quebrando o layout do site que você estiver desenvolvendo*/
        .imagem_corte{width: 100%; height: auto;}
    </style>

  </head>

  <body>
    <?php
    //1º vamos verificar se existe arquivo para upload ou não
    //Caso exista iremos fazer pegar o nome do arquivo, criar ele a partir do temporário
    if($_FILES){
        
        // Salvo numa variavel nome completo da imagem. ex.: nomedaimagem.jpg
        $nome           = $_FILES['arquivo']['name'];
        
        // Faço um explode para verificar qual é a extensão
        $file = explode('.', $nome);
                       
        if($file[1] == 'jpg'){
            // caso seja jpg, a imagem a ser criada é jpg
            $image = imagecreatefromjpeg($_FILES['arquivo']['tmp_name']);
        }else{
            // caso seja png, a imagem a ser criada é png
            $image = imagecreatefrompng($_FILES['arquivo']['tmp_name']);
        }
     
        // Salvo em duas variaveis o nome e  a extensão
        $nomeArquivo    = $file[0];
        $ext            = $file[1];
        
        //$tipoArquivo    = $_FILES['arquivo']['type'];
        // Pego arquivo temporário e salvo numa variavel
        $tmpArquivo     = $_FILES['arquivo']['tmp_name'];
        
        // E realizo o upload para a pasta desejada
        move_uploaded_file($tmpArquivo, 'img/'.$nome);
            
    ?>
      
      <!-- Então assim troco a tela para o form abaixo onde ele receberá a imagem e o Jquery para realizar onde será o corte -->
      <!-- Após selecionar onde será o corte irei enviar esses valores via post para a página recortar.php -->
      <form class="" method="post" enctype="multipart/form-data" action="recortar.php">
            <img src="img/<?php echo $nome; ?>" class="imagem_corte" id="target" alt="<?php echo $nome; ?>" />
            <input type="hidden" id="arquivo" class="imagem_corte" name="arquivo" value="img/<?php echo $nome; ?>" />
            <input type="hidden" id="x" name="x" />
            <input type="hidden" id="y" name="y" />
            <input type="hidden" id="w" name="w" />
            <input type="hidden" id="h" name="h" />
            
            <button class="large-12" type="submit">Enviar</button>
      </form>
    
    <?php }else{ ?>
      <!-- Esse é o form que pegará a imagem que desejo upar -->
    <form class="" method="post" enctype="multipart/form-data" action="">
        <input type="file" class="input" name="arquivo">
        <br />
        <br />
        <button class="large-12" type="submit">Enviar</button>
    </form>
    
    <?php } ?>
            
    <script type="text/javascript">

      jQuery(function($){
        var jcrop_api, boundx, boundy;
        // Aqui está o javascript que irá realizar a criação do local onde será o crop
        $('#target').Jcrop({
            // onChange e onSelect irá funcionar de acordo com o tamanho da imagem e irá 
            // redmensionar a area de crop de acordo a que defini para ele e a resolução da imagem
            onChange: updatePreview,
            onSelect: updatePreview,
            // Essas 4 posições são na seguinte ordem
            // posição x de inicio da area de crop,
            // posição y de inicio da area de crop,
            // tamanho total de largura da area de crop,
            // tamanho total de altura da area de crop.
            setSelect: [ 0, 0,  450, 350 ],
            // Pega o tamanho real da imagem que estou usando para cropar
            trueSize: [<?php echo imagesx($image); ?>, <?php echo imagesy($image); ?>],
            // Permite que eu possa movimentar a area selecionada
            allowMove: true,
            // Impede que eu possa aumentar o tamanho da area selecionada, pois queremos cropar ela do tamanho exato
            allowResize: false,
            // Impede que a area de cropa seja desfeita caso cliquem na imagem
            allowSelect: false
        },function(){
            // Usa a API para pegar o tamanho real da imagem
            var bounds = this.getBounds();
            boundx = bounds[0];
            boundy = bounds[0.75];
            
        });

        function updatePreview(c){
            if (parseInt(c.w) > 0){
                var rx = <?php echo imagesx($image); ?> / c.w;
                var ry = <?php echo imagesy($image); ?> / c.h;

                $('#preview').css({
                    width: Math.round(rx * boundx) + 'px',
                    height: Math.round(ry * boundy) + 'px',
                    marginLeft: '-' + Math.round(rx * c.x) + 'px',
                    marginTop: '-' + Math.round(ry * c.y) + 'px'
                });
            }

            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
        };

      });

    </script> 
      
  </body>
</html>

