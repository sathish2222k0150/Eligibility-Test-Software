<?php include __DIR__ . '/../layouts/student-header.php'; ?>
<section class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8 mt-4">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Test Instructions: <?php echo htmlspecialchars($test['test_title']); ?></h3>
          </div>
          <div class="card-body">
            <h4>Before you begin:</h4>
            <ul>
              <li><strong>Course:</strong> <?php echo htmlspecialchars($course_name); ?></li>
              <li><strong>Number of Questions:</strong> <?php echo $question_count; ?></li>
              <li><strong>Duration:</strong> <?php echo $test['duration_minutes']; ?> Minutes</li>
              <li>This test must be completed in one sitting. You cannot pause the timer.</li>
              <li>Do not close the browser window or navigate away from the test page. Doing so will submit your test.</li>
              <li>Your camera may be activated for proctoring purposes.</li>
            </ul>
            <div class="text-center">
              <!-- UPDATED: This button now calls JavaScript to open a new window -->
              <a href="#" onclick="startTest('index.php?action=take-test&id=<?php echo $test['id']; ?>'); return false;" class="btn btn-success btn-lg">
                I Understand, Begin Test
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- NEW: JavaScript to handle the new window opening -->
<script>
function startTest(url) {
  // Define the properties of the new window to be as fullscreen as possible
  const windowFeatures = `
    menubar=no,
    location=no,
    resizable=yes,
    scrollbars=yes,
    status=no,
    width=${screen.width},
    height=${screen.height},
    left=0,
    top=0
  `;
  // Open the new window and give it a name
  window.open(url, 'TestWindow', windowFeatures);
}
</script>

<?php include __DIR__ . '/../layouts/student-footer.php'; ?>