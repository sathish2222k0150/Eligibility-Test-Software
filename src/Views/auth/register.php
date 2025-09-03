<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Eligibility Test | Log in</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo"><b>Student</b> Registration</div>
  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>

    
      <?php
        if (isset($_SESSION['register_feedback'])) {
            $feedback = $_SESSION['register_feedback'];
            echo '<div class="alert alert-' . $feedback['type'] . '">'
                . htmlspecialchars($feedback['message']) .
                '</div>';
            unset($_SESSION['register_feedback']);
        }
      ?>
     

      <form action="index.php?action=register" method="post">
        <div class="input-group mb-3">
          <input type="text" name="full_name" class="form-control" placeholder="Full name" required>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group">
          <select name="course_id" class="form-control" required>
            <option value="">-- Select Your Course --</option>
            <?php foreach($courses as $course): ?>
              <option value="<?php echo $course['id']; ?>">
                <?php echo htmlspecialchars($course['course_name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
        </div>
      </form>

      <a href="index.php" class="text-center">I already have a membership</a>
    </div>
  </div>
</div>
</body>
</html>
