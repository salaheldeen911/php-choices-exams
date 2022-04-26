<?php
require_once(__DIR__ . '/../layouts/head.php');

if (!isLoggedIn() || !isAdmin()) {
   header('location:' . $_SESSION['ROOT_URL']);
   exit();
}


require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/header.php');
?>
<div class="main-panel">
   <div class="content-wrapper">
      <h1 class="text-center" style="font-family:'Hurricane',cursive; color:#0d6efd;">WELCOME TO EXAMS DASHBORD (Home)</h1>

      <div class="page-header">
         <h3 class="page-title"> Home </h3>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item active" aria-current="page"> Home </li>
            </ol>
         </nav>
      </div>
      <div class="row">

         <div class="col-lg-12 stretch-card">
            <div class="card">
               <div class="card-body">


                  <h4 class="card-title">All Exames</h4>

                  <div class="table-responsive">
                     <table id="exams" class="table table-bordered table-contextual" style="width: 100%;">
                        <thead>
                           <tr>
                              <th width="5%">ID</th>
                              <th width="20%">Material</th>
                              <th width="20%">Exam name</th>
                              <th width="5%">Num of questions</th>
                              <th width="20%">Dr.</th>
                              <th width="10%">Created_at</th>
                              <th width="20%">Actions</th>
                           </tr>
                        </thead>

                     </table>
                  </div>

                  <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/footer.php') ?>

                  <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/jquery.dataTables.min.js' ?>"></script>
                  <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/dataTables.bootstrap4.min.js' ?>"></script>
                  <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/dataTables.responsive.min.js' ?>"></script>

                  <script>
                     $(document).ready(function() {
                        $('#exams').DataTable({
                           "processing": true,
                           "serverSide": true,
                           "data-type": "JSON",
                           "ajax": "<?php echo $_SESSION['ROOT_URL'] .  'controller/admin/get_exams.php' ?>",
                           "fnInitComplete": function(oSettings, json) {
                              $('span.actions a.delete-exam').on('click', function(event) {
                                 event.preventDefault();
                                 if (confirm("are you sure that you want to delete this exam?")) {
                                    id = $(this).data('id');
                                    $(`#${id}`).submit();
                                 }
                                 $('tr').addClass('table-denger')
                              });
                           }
                        });

                        $("p[data-id='logOut']").on('click', function(event) {
                           $("a#logOut").click();
                        })
                     });
                  </script>

                  <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/bottom.php') ?>
                  