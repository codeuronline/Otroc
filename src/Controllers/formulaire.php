<?php
use App\Models\AnnoncesModel;
use App\Models\EmailModel;
use App\Models\PhotoModel;

$annoncesModel = new AnnoncesModel;
$emailModel = new EmailModel;
$photoModel = new PhotoModel;


$envoieAnnonces=$annoncesModel
->setnom($_POST['nom'])
->setdescription($_POST['description'])
->setprix($_POST['prix']);

$envoieEmail=$emailModel
->setemail($_POST['email']);

$email=$emailModel->findBy(['email'=>$_POST['email']]);
if(empty($email)){
    $emailModel->create($envoieEmail);
    $email=$emailModel->findBy(['email'=>$_POST['email']]);
}    
$envoieAnnonces=$annoncesModel->setid_email($email[0]['id']);
$annoncesModel->create($envoieAnnonces);
$maxAnnonce = $annoncesModel->findMax('id');





for($i=1;$i<=5;$i++){
    $file=$_FILES['image'.$i];
    $fileName=$_FILES['image'.$i]['name'];
    $fileTmpName=$_FILES['image'.$i]['tmp_name'];
    $fileSize=$_FILES['image'.$i]['size'];
    $fileError=$_FILES['image'.$i]['error'];
    $fileType=$_FILES['image'.$i]['type'];

    $fileExt = explode('.',$fileName);
    $fileActualExt = strtolower((end($fileExt)));
    $allowed = array('jpeg','jpeg','png','jpg');

    if(in_array($fileActualExt,$allowed)){
        if($fileError === 0){
            if($fileSize < 1000000){
                $fileNameNew[$i] = uniqid('',true).".".$fileActualExt;
                @mkdir('img/'.$_POST['email'].$maxAnnonce['MAX(id)'].'/', 0755, true);
                $fileDestination = 'img/'.$_POST['email'].$maxAnnonce['MAX(id)'].'/'.$fileNameNew[$i];
                move_uploaded_file($fileTmpName,$fileDestination);
            }else{
                echo "l'image est trop volumineuse";
            }
        }else{
            echo "il y as eu une erreur lors de l'envoie des images";
        }
        
    }else{
        echo "seule les jpeg,jpeg et png sont autorisé";
    }
}
for($i=1;$i<=5;$i++){
    $envoiephoto=$photoModel
    ->setId_annonce($maxAnnonce['MAX(id)'])
    ->setphoto($fileNameNew[$i]);
    $photoModel->create($envoiephoto);
}





// $newnom2=$annoncesModel->getnom();
// echo "<pre>",print_r($envoieAnnonces),print_r($envoieEmail),"</pre>";
// echo print_r($annonce);
?>
