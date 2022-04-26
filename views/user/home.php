<?php
require_once(__DIR__ . '/../layouts/head.php');

if (!isLoggedIn() || isAdmin()) {
  header('location:' . $_SESSION['ROOT_URL']);
  exit();
}

require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/header.php');
?>

<div class="main-panel">
  <div class="content-wrapper">
    <h1 class="text-center" style="font-family:'Hurricane',cursive; color:#0d6efd;">WELCOME TO EXAMS SITE</h1>
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

            <div class="d-flex flex-row justify-content-between">
              <h4 class="card-title mb-1">Avilable Exams</h4>
              <p class="text-muted mb-1">Info</p>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="preview-list">

                  <?php
                  if (getAvilableExams()) {
                    foreach (getAvilableExams() as $exam) {

                  ?>
                      <a href="<?php echo $_SESSION['ROOT_URL'] . 'views/user/exam.php?id=' . $exam['id'] ?>">
                        <div class="preview-item border-bottom">
                          <div class="preview-thumbnail">
                            <div class="preview-icon bg-primary">
                              <i class="mdi mdi-file-document"></i>
                            </div>
                          </div>

                          <div class="preview-item-content d-sm-flex flex-grow">
                            <div class="flex-grow">
                              <h6 class="preview-subject"><?php echo $exam['material'] ?></h6>
                              <p class="text-muted mb-0"><?php echo $exam['name'] ?></p>
                            </div>
                            <div class="me-auto text-sm-right pt-2 pt-sm-0">
                              <p class="text-muted"><?php echo time_elapsed_string($exam['created_at']) ?></p>
                              <p class="text-muted mb-0"><?php echo $exam['questions_number'] ?> questions, Dr. <?php echo getUserName($exam['creator_id']) ?> </p>
                            </div>
                          </div>
                        </div>
                      </a>

                    <?php
                    }
                  } else {
                    ?>
                    <p class="text-center text-muted mt-3">No avilable exams yet.</p>

                  <?php
                  }
                  ?>
                </div>
              </div>
            </div>

            <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/footer.php') ?>

            <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/bottom.php') ?>
            