<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Taking Test: <?php echo htmlspecialchars($test['test_title']); ?></title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/adminlte.min.css">
  <style>
    body.lockscreen { height: auto; background-color: #f4f6f9; }
    .lockscreen-wrapper { max-width: 900px; width: 100%; margin-top: 2%; }
    .camera-feed { position: fixed; bottom: 15px; right: 15px; border: 3px solid #dee2e6; border-radius: .25rem; box-shadow: 0 0 10px rgba(0,0,0,0.2); background-color: #000; }
    .question-text { font-size: 1.1rem; font-weight: 500; }
  </style>
</head>
<body id="test-body" class="lockscreen">
<div class="lockscreen-wrapper">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
          <i class="fas fa-file-alt mr-2"></i>
          <?php echo htmlspecialchars($test['test_title']); ?>
        </h3>
        <span id="timer" class="badge badge-danger p-2" style="font-size: 1.2rem;"></span>
      </div>
    </div>

    <div class="card-body">
      <?php
        // ==================================================================
        // FIX: Remove duplicate questions from the array before displaying
        // ==================================================================
        $unique_questions_map = [];
        foreach ($questions as $question) {
            // Using the question ID as the array key automatically handles duplicates.
            $unique_questions_map[$question['id']] = $question;
        }
        // Replace the original array with the filtered, unique one.
        $questions = array_values($unique_questions_map);
      ?>

      <form id="test-form" action="index.php?action=submit-test" method="post">
        <?php
        $is_one_per_page = isset($test['settings']['one_question_per_page']) && $test['settings']['one_question_per_page'];
        ?>
        <?php foreach($questions as $index => $q): ?>
          <div class="question-page" id="page-<?php echo $index; ?>"> 
        <div class="card card-info card-outline mb-4">
          <div class="card-header">
            <h5 class="card-title">Question <?php echo $index + 1; ?></h5>
          </div>
          <div class="card-body">
            <p class="question-text mb-3"><?php echo htmlspecialchars($q['question_text']); ?></p>
            
            <?php if($q['question_type'] === 'multiple_choice'): ?>
              <div class="form-group">
                <?php foreach($q['options'] as $option): ?>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" id="option_<?php echo $option['id']; ?>" name="answers[<?php echo $q['id']; ?>][option]" value="<?php echo $option['id']; ?>" required>
                    <label class="form-check-label" for="option_<?php echo $option['id']; ?>"><?php echo htmlspecialchars($option['option_text']); ?></label>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: // short_answer ?>
              <div class="form-group">
                <textarea class="form-control" name="answers[<?php echo $q['id']; ?>]" rows="4" placeholder="Your answer here..." required></textarea>
              </div>
            <?php endif; ?>
          </div>
        </div>
        </div>
        <?php endforeach; ?>
        <div class="d-flex justify-content-between mb-5">
        <button type="button" id="prev-btn" class="btn btn-secondary btn-lg" onclick="prevPage()" <?php if(!$is_one_per_page) echo 'style="display:none;"'; ?>>Previous</button>
        <button type="button" id="next-btn" class="btn btn-info btn-lg" onclick="nextPage()" <?php if(!$is_one_per_page) echo 'style="display:none;"'; ?>>Next</button>
      </div>
        <div class="card-footer bg-light text-center">
            <button type="submit" class="btn btn-success btn-lg px-5">Submit Test</button>
        </div>
      </form>
    </div>
  </div>
</div>

<video id="camera" width="160" height="120" autoplay class="camera-feed"></video>

<script>
  const testForm = document.getElementById('test-form');
  
  // Disable unload warning when the form is submitted intentionally
  testForm.addEventListener('submit', () => {
    window.onbeforeunload = null;
  });

  // 1. TIMER LOGIC
  const testEndTime = <?php echo json_encode(Session::get('test_end_time') * 1000); ?>;
  const timerDisplay = document.getElementById('timer');

  const timerInterval = setInterval(() => {
    const remaining = Math.round((testEndTime - Date.now()) / 1000);
    if (remaining <= 0) {
      clearInterval(timerInterval);
      timerDisplay.textContent = "Time's Up!";
      alert("Time is up! Your test will now be submitted automatically.");
      testForm.submit();
    } else {
      const mins = Math.floor(remaining / 60);
      const secs = remaining % 60;
      timerDisplay.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
  }, 1000);

  // 2. TAB SWITCHING RESTRICTION
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      alert('You have switched tabs or minimized the window. This action is not allowed and the test will be submitted.');
      testForm.submit();
    }
  });

  // 3. COPY/PASTE/CONTEXT MENU RESTRICTION
  ['cut', 'copy', 'paste', 'contextmenu'].forEach(evt => {
    document.body.addEventListener(evt, e => e.preventDefault());
  });

  // 4. PREVENT CLOSING THE WINDOW
  window.onbeforeunload = () => "Are you sure you want to leave? Your test will be submitted as is.";

  // 5. CAMERA ACTIVATION
  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => { document.getElementById('camera').srcObject = stream; })
      .catch(err => {
        alert("Camera access is required for this test. Please allow camera access and refresh the page.");
        testForm.innerHTML = "<h1>Camera Access Denied. Cannot Start Test.</h1>";
      });
  }
</script>
<script>
    // --- ADD THIS NEW PAGINATION SCRIPT ---
    const isOnePerPage = <?php echo json_encode($is_one_per_page); ?>;
    const totalPages = <?php echo count($questions); ?>;
    let currentPage = 0;

    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');

    function showPage(pageIndex) {
      // Hide all pages
      document.querySelectorAll('.question-page').forEach(page => {
        page.style.display = 'none';
      });
      // Show the current page
      document.getElementById('page-' + pageIndex).style.display = 'block';

      // Update button visibility
      prevBtn.disabled = (pageIndex === 0);
      nextBtn.style.display = (pageIndex === totalPages - 1) ? 'none' : 'inline-block';
      submitBtn.style.display = (pageIndex === totalPages - 1) ? 'inline-block' : 'none';
    }

    function nextPage() {
      if (currentPage < totalPages - 1) {
        currentPage++;
        showPage(currentPage);
      }
    }

    function prevPage() {
      if (currentPage > 0) {
        currentPage--;
        showPage(currentPage);
      }
    }
    
    // Initialize the view
    if (isOnePerPage) {
      showPage(0);
    } else {
      // If not one-per-page, show all questions
      document.querySelectorAll('.question-page').forEach(page => {
        page.style.display = 'block';
      });
    }
    // --- END OF PAGINATION SCRIPT ---
  </script>
</body>
</html>