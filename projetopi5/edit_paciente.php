<?php session_start();
//DB conncetion
include_once('includes/config.php');
//validating Session
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{


if(isset($_POST['submit'])){
//getting post values

$name = utf8_decode($_POST['name']);
$senha=$_POST['senha'];
$sexo=$_POST['sexo'];
$idade=$_POST['idade'];
$obs=$_POST['obs'];
$status=$_POST['status'];
$uid=$_SESSION['aid'];
$fmid=intval($_GET['fmid']);
$query = "UPDATE tblpaciente SET name='$name', senha='$senha', sexo='$sexo', idade='$idade', obs='$obs', status='$status' WHERE id='$fmid'";

$result =mysqli_query($con, $query);
if ($result) {

echo "<script>alert('" . utf8_encode('Informações Atualizadas com Sucesso!') . "');</script>";
  echo "<script>window.location.href='list_pacientes.php'</script>";
} 
else {
   echo "<script>alert('" . utf8_encode('Erro de Atualização.') . "');</script>";
echo "<script>window.location.href='list_pacientes.php?id=$fmid'</script>";

}
}
?>

<!DOCTYPE html>
<html lang="Pt_br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

     <title>MR Monitoramento Remoto</title>

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
                    <h1 class="h3 mb-4 text-gray-800">Editar Paciente</h1>
<form name="addphlebotomist" method="post">
  <div class="row">

                        <div class="col-lg-8">

                            <!-- Basic Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary"><?php echo utf8_encode("Informação do Paciente"); ?></h6>
                                </div>
                                <div class="card-body">
<?php $uid=$_SESSION['aid'];
$fmid=intval($_GET['fmid']);
$query=mysqli_query($con,"select * from tblpaciente where id='$fmid'");
$cnt=1;
while($row=mysqli_fetch_array($query)){
?>

                                <div class="form-group">
                            <label>Nome</label>
                                    <input type="text" class="form-control" id="name" name="name"  placeholder="Nome Completo"   required="true" value="<?php echo utf8_encode($row['name']);?>">
                                     
                                        </div>
     
                                <div class="form-group">
                            <label>Senha</label>
                                    <input type="text" class="form-control" id="senha" name="senha"  placeholder="Senha"   required="true" value="<?php echo $row['senha'];?>">
                                     
                                        </div>
                        <div class="form-group">
                            <label>Sexo:</label>
                                            <select class="form-control" id="sexo" name="sexo"  required="true">
                                                <option value="<?php echo $row['sexo'];?>"><?php echo $row['sexo'];?></option>
                                                <option value="">Selecione</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Feminino">Feminino</option>
                                                <option value="Outro">Outro</option>
                                            </select>
                                            </select>
                                        </div>
                           
                                       <div class="form-group">
                                             <label>Idade do Paciente</label>
                            <input type="text" class="form-control" id="idade" name="idade" placeholder="Idade do Paciente" pattern="[0-9]+" title="2 numeric characters only" required="true" maxlength="3" value="<?php echo $row['idade'];?>">
                                          
                                        </div>
                                        
                              
                                         <div class="form-group">
                         <label>Obs:</label>
                                    <input type="textarea" class="form-control" id="obs" name="obs"  placeholder="<?php echo utf8_encode("Observções"); ?>"   required="true" value="<?php echo utf8_encode($row['obs']);?>">
                                     
                                        </div>
                                         
                                            <div class="form-group">
                         <label><?php echo utf8_encode("Situação:"); ?></label>
                                    <input type="text" class="form-control" id="status" name="status"    placeholder="<?php echo utf8_encode("Ativo"); ?>"   required="true" value="<?php echo $row['status'];?>">
                                     
                                        </div> 
         
                        
<?php } ?>

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