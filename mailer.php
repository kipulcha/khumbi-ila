<?php
require_once './config.php';
    if($_FILES['image']['name'] != "")
    {
        $extensions =  array('gif','png' ,'jpg');
        $filename = $_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!in_array($ext,$extensions) ) {
            header("Location:./advertise?msg=imageExtError");
            exit;
        }else{
            $imageName = date('ymd') . time() . $_POST['company_name'] . $_FILES['image']['name'];
            $target_dir = "./admin/site_images/advertise/";
            $target_file = $target_dir . basename($imageName);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                /*header("Location:./advertise?msg=imageUploaded");
            exit;*/
            }else{
                header("Location:./advertise?msg=imageUploadError");
            exit;
            }
           
        }
    }
    $insertData = array("company_name" => $_POST['company_name'],"name" => $_POST['name'],"image" => $imageName, "phone" => $_POST['phone'], "email" => $_POST['email'], "address1" => $_POST['address1'], "address2" => $_POST['address2'], "message" => $_POST['message'], "service" => $_POST['service'], "created_at" => $currentDate, "status"=>'0');
    $insert = $mydb->insert("tbl_advertise", $insertData);

    if ($insert) {
        $from = $_POST["email"];
        $mail_title = $_POST["service"];
        $date = date('F d,Y');
        $to = "sales@pandoralist.com";
        $message1 =
        '<table style="background:#e5e5e5;">
        <tr><td colspan="3" style="height:50px;">&nbsp;</td></tr>

        <tr>
        <td style="width:100px;">&nbsp;</td>
        <td style="width:1000px;">
        <table style="font-family: Verdana, Geneva, sans-serif; background:#fff; font-size:14px;">
        <tr>
        <td>
        <p style="background:#044398; color:#ffffff; font-size:40px; line-height:50px; padding:20px;">' . $mail_title . '</p>
        <p style="padding:0 20px;">Dear' . $_POST["name"] . ',</p>
        <p style="padding:0 20px;">' . $_POST["message"] . '</p>
        <p style="padding:0 20px;">Regards,</p>
        <p style="padding:0 20px;">' . $_POST["name"] . '</p>
        </td>
        </tr>
        </table>
        </td>
        <td style="width:100px;">&nbsp;</td>
        </tr>
        </table>';

        $headers = "From: $from \r\n";
        $headers .= "Reply-To: sales@pandoralist.com\r\n";
        $headers .= "Return-Path: sales@pandoralist.com\r\n";
        $headers .= "CC:ktmrushservices@gmail.com\r\n";
        $headers .= "BCC: noreply@pandoralist.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        if (mail($to, $mail_title, $message1, $headers)){
            header("Location:./advertise?msg=sent");
        } else {
            header("Location:./advertise?msg=error");
        }
    } else {
        //print_r($mydb->error);
        header("Location:./advertise?msg=error");

    }
