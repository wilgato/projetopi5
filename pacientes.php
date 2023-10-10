<?php

function generateRandomRegistration() {
    return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
}
session_start();
//DB conncetion
include_once('includes/config.php');
//validating Session
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{


if(isset($_POST['submit'])){
//getting post values
$registro = generateRandomRegistration();
$name = utf8_decode($_POST['name']);
$senha=$_POST['senha'];
$sexo=$_POST['sexo'];
$idade=$_POST['idade'];
$obs=$_POST['obs'];
$status=$_POST['status'];
$uid=$_SESSION['aid'];
$query="insert into tblpaciente(registro,senha,name,sexo,idade,status, obs) values('$registro','$senha','$name','$sexo','$idade','$status','$obs')";
$result =mysqli_query($con, $query);
if ($result) {
echo '<script>alert("Paciente adicionado com sucesso.")</script>';
  echo "<script>window.location.href='pacientes.php'</script>";
} 
else {
    echo "<script>alert('Algo deu errado. Por favor, tente novamente.');</script>";  
echo "<script>window.location.href='pacientes.php'</script>";
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
                    <h1 class="h3 mb-4 text-gray-800">Adicionar Paciente</h1>
<form name="addphlebotomist" method="post">
  <div class="row">

                        <div class="col-lg-8">

                            <!-- Basic Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary"><?php echo utf8_encode("Informações do Paciente"); ?></h6>
                                </div>
                                <div class="card-body">
     <div class="form-group">
                            <label>Nome</label>
                                    <input type="text" class="form-control" id="name" name="name"  placeholder="Nome"   required="true" >
                                     
                                        </div>
      <div class="form-group">
                         <label>Senha</label>
                                    <input type="text" class="form-control" id="senha" name="senha"  placeholder="senha"   required="true" >
                                     
                                        </div>

                        <div class="form-group">
                            <label>Sexo</label>
                                            <select class="form-control" id="sexo" name="sexo"  required="true">
                                                <option value="">Selecione</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Feminino">Feminino</option>
                                                <option value="Outro">Outro</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                             <label>Idade do Paciente</label>
                            <input type="text" class="form-control" id="idade" name="idade" placeholder="Idade do Paciente" pattern="[0-9]+" title="2 numeric characters only" required="true" maxlength="3" >
                                          
                                        </div>
                                        
                                          
                                         <div class="form-group">
                         <label>Obs:</label>
                                    <input type="textarea" class="form-control" id="obs" name="obs"  placeholder="<?php echo utf8_encode("Observções"); ?>"   required="true" >
                                     
                                        </div>
                  <input type="hidden" class="form-control" id="status" name="status"  value="1" >
                                     
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