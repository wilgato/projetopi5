<?php session_start();
//DB conncetion
include_once('includes/config.php');
//validating Session
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{


    if(isset($_POST['update']))
  {
  $uid=$_SESSION['aid'];
  $fname=$_POST['fullname'];
  $mobno=$_POST['mobilenumber'];
  
  
     $query=mysqli_query($con, "update tbluser set fullName='$fname', mobileNumber ='$mobno'  where id='$uid'");
    if ($query) {
 
    echo '<script>alert("Profile has been updated")</script>';
  }
  else
    {
      echo '<script>alert("Something Went Wrong. Please try again.")</script>';
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
                    <h1 class="h3 mb-4 text-gray-800">Atualizar Perfil</h1>
     <form method="post"  name="adminprofile" >



  <div class="row">

                        <div class="col-lg-8">

                            <!-- Basic Card Example -->
                            <div class="card shadow mb-4">

                <?php
$uid=$_SESSION['aid'];
$ret=mysqli_query($con,"select * from tbluser where id='$uid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>

                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Data de Cadastro: <?php  echo $row['regDate'];?></h6>
                                </div>
                                <div class="card-body">
     <div class="form-group">
                            <label>Nome</label>
           <input type="text" class="form-control" name="fullname" value="<?php  echo $row['fullName'];?>" required='true'>
                                        </div>

                        <div class="form-group">
                            <label>Email <small>(login de usuario)</small></label>
                       <input type="text" class="form-control" name="email" value="<?php  echo $row['emailid'];?>" readonly='true'> 
                                        </div>
                                        
<div class="form-group">
<label>Telefone</label>
<input type="text" class="form-control" name="mobilenumber" value="<?php  echo $row['mobileNumber'];?>" required='true'>
</div>


                        

<?php } ?>
        <div class="form-group">
                                 <input type="submit" class="btn btn-primary btn-user btn-block" name="update" id="update" value="Update">                           
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