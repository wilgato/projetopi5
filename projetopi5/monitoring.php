<?php session_start();
//DB conncetion
include_once('includes/config.php');
//validating Session
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{


if(isset($_POST['submit'])){
  
$userid=$_SESSION['aid'];
$paciente_id = $_POST["registro"];
$temp_amb=$_POST['temp_amb'];
$um_amb=$_POST['um_amb'];
$pres_amb=$_POST['pres_amb'];
$spo2=$_POST['spo2'];
$bpm=$_POST['bpm'];
$temp_corp=$_POST['temp_corp'];
$status = 1;





$query="insert into tblmedicao(paciente_id,user_id,temp_amb,um_amb,pres_amb, spo2, bpm, temp_corp, status) values
('$paciente_id','$userid','$temp_amb','$um_amb','$pres_amb','$spo2','$bpm','$temp_corp','$status')";
$result =mysqli_query($con, $query);
if ($result) {
echo "<script>alert('" . utf8_encode('Informações inseridas com Sucesso!') . "');</script>";
  echo "<script>window.location.href='list_monitor.php'</script>";
} 
else {
   echo "<script>alert('" . utf8_encode('Erro de cadastro.') . "');</script>";  
echo "<script>window.location.href='monitoring.php'</script>";
}
}
?>

<!DOCTYPE html>
<html lang="Pt_Br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Datas de Monitoramento</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
<style type="text/css">
label{
    font-size:16px;
    font-weight:bold;
    color:#000;
}

</style>


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

<?php include_once('includes/sidebar.php');?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
          <?php include_once('includes/topbar.php');?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Adicionar Monitoramento de acompanhamento</h1>
<form name="addphlebotomist" method="post">
  <div class="row">

                        <div class="col-lg-8">

                            <!-- Basic Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary"><?php echo utf8_encode("Informações Detalhadas"); ?></h6>
                                </div>
                                <div class="card-body">
  

                        <div class="form-group">
                            <label>Selecione o Paciente</label>
                                            <select class="form-control" id="registro" name="registro"  required="true">
                                                <option value="">Selecione</option>
<?php $uid=$_SESSION['aid'];
$query=mysqli_query($con,"select * from tblpaciente ");
while($row=mysqli_fetch_array($query)){ ?>
                                                <option value="<?php echo $row['registro'];?>"><?php echo utf8_encode($row['name']);?></option>
<?php } ?>
                                            </select>
                                        </div>
        <div class="form-group">
     <label>Temperatura Corporal</label>
    <input type="text" class="form-control" id="temp_corp" name="temp_corp"  placeholder="Temperatura Corporal" maxlength="5"   required="true" >
</div>
       
        <div class="form-group">
     <label> BPM </label>
    <input type="text" class="form-control" id="bpm" name="bpm"  placeholder="Batimentos Cardiacos" maxlength="5"   required="true" >
</div>

        <div class="form-group">
     <label>SPO2</label>
    <input type="text" class="form-control" id="spo2" name="spo2"  placeholder="<?php echo utf8_encode("Saturação Sanguínea"); ?>" maxlength="5"   required="true" >
</div>
            <div class="form-group">
     <label>Temperatura Ambiente</label>
    <input type="text" class="form-control" id="temp_amb" name="temp_amb"  placeholder="<?php echo utf8_encode("Temperatura Ambiente"); ?>" maxlength="5"   required="true" >
</div>
        
               <div class="form-group">
     <label>Umidade Ambiente</label>
    <input type="text" class="form-control" id="um_amb" name="um_amb"  placeholder="<?php echo utf8_encode("Umidade Ambiente"); ?>" maxlength="5"   required="true" >
</div>
               
                       <div class="form-group">
     <label><?php echo utf8_encode("Pressão Ambiente"); ?></label>
    <input type="text" class="form-control" id="pres_amb" name="pres_amb"  placeholder="<?php echo utf8_encode("Pressão Ambiente"); ?>" maxlength="5"   required="true" >
</div>
           
        
        

 

        <div class="form-group">
                                 <input type="submit" class="btn btn-primary btn-user btn-block" name="submit" id="submit">                           
                             </div>                                        

                                </div>
                            </div>

                        </div>

               

                    </div>
</form>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

           <?php include_once('includes/footer.php');?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
           <?php include_once('includes/footer2.php');?>



    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
<?php } ?>