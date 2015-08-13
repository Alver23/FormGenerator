<?php
#Created by Michel Gomes Ank
#E-mail: michel@lafanet.com.br
#MSN: mitheus@bol.com.br
#ICQ: 530377777
require "lib/class.php";

#------------------------------------ [START] GENERATE THE FORM ----------------------------------#
$form = new form;
$print = $form->form_start("cadastro","","POST");
$print .= $form->form_text("Name","name","40","Your Full Name","maxlength=\"200\"","<b>Ex:</b> Michel Gomes Ank");
$print .= $form->form_select("Country","country","1","Canada,Brazil,EUA,Japan","C,B,E,J","B");
$print .= $form->form_checkbox("You know the Brazil?","brazil","checked","1");
$print .= $form->form_textarea("More information","more_inf","5","30");
$print .= $form->form_file("Photo","photo","","Files of type: .jpg");
$print .= $form->form_go("Send","Clear");
$print .= $form->form_end();
echo $print;
#------------------------------------- [END] GENERATE THE FORM -----------------------------------#
//crear archivo php
$contenido = $print;
$fp=fopen("archivo.php","x");
fwrite($fp,$contenido);
fclose($fp) ;
?>