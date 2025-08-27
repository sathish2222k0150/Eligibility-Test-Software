<?php include __DIR__ . '/../layouts/staff-header.php'; ?>
<section class="content-header">
  <div class="container-fluid"><h1>Grading Test</h1></div>
</section>
<section class="content">
  <div class="container-fluid">
    <form action="index.php?action=save-grades" method="post">
      <input type="hidden" name="attempt_id" value="<?php echo $_GET['id']; ?>">
      <?php foreach($details as $item): ?>
        <div class="card">
          <div class="card-header">
            <strong>Question (<?php echo $item['points']; ?> Points)</strong>
          </div>
          <div class="card-body">
            <p><?php echo htmlspecialchars($item['question_text']); ?></p>
            <hr>
            <h5>Student's Answer:</h5>
            <?php if($item['question_type'] === 'multiple_choice'): ?>
              <?php 
                $options = explode('|||', $item['options']);
                $student_answer_text = '';
                $correct_answer_text = '';
                // This is a bit complex just to display the text of the selected/correct options
                $option_ids = [];
                preg_match_all('/id=([0-9]+)/', $item['options'], $matches);
                
                // Create a mapping of option ID to option text
                $option_map = [];
                $stmt_temp = Database::getInstance()->getConnection()->prepare("SELECT id, option_text FROM question_options WHERE question_id = ?");
                $stmt_temp->execute([$item['question_id']]);
                foreach($stmt_temp->fetchAll() as $opt){
                    $option_map[$opt['id']] = $opt['option_text'];
                }

                $student_answer_text = $option_map[$item['selected_option_id']] ?? 'Not answered';
                $correct_answer_text = $option_map[$item['correct_option_id']] ?? 'N/A';
                
                $is_correct = ($item['selected_option_id'] == $item['correct_option_id']);
              ?>
              <p class="<?php echo $is_correct ? 'text-success' : 'text-danger'; ?>">
                <?php echo htmlspecialchars($student_answer_text); ?>
              </p>
              <?php if(!$is_correct): ?>
                <p class="text-success">Correct Answer: <?php echo htmlspecialchars($correct_answer_text); ?></p>
              <?php endif; ?>

            <?php else: // Short Answer ?>
              <p><em><?php echo nl2br(htmlspecialchars($item['answer_text'])); ?></em></p>
              <div class="form-group mt-3">
                <label>Award Marks (Out of <?php echo $item['points']; ?>)</label>
                <input type="number" name="grades[<?php echo $item['question_id']; ?>]" class="form-control" value="0" min="0" max="<?php echo $item['points']; ?>" style="width: 100px;">
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
      <button type="submit" class="btn btn-success btn-lg mb-4">Save and Finalize Grade</button>
    </form>
  </div>
</section>
<?php include __DIR__ . '/../layouts/staff-footer.php'; ?>