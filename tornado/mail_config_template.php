<?php
    global $mail;//se define mail como global
    $mail = new PHPMailer();//se crea el objeto mail del vendor php mailer
    $mail->isSMTP();//se establece como smtp
    $mail->isHTML(true);//se le indica que el formato sera html
    $mail->SMTPAuth=true;//se le indica el smtp tiene autenticacion
    $mail->SMTPSecure='ssl';//se indica que se utiliza ssl
    $mail->Host = 'smtp.gmail.com';//se establece host
    $mail->Port = 465;//se establece puerto
    $mail->Username = "correo@gmail.com";//se establece correo
    $mail->Password = "password";//se establece password
    $mail->SetFrom("correo@gmail.com", "correo@gmail.com");//se establece from
