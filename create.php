<?php include('config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);
    $faculty    = mysqli_real_escape_string($conn, $_POST['faculty']);
    $year_level = mysqli_real_escape_string($conn, $_POST['year_level']);
    $address    = mysqli_real_escape_string($conn, $_POST['address']);

    $errors = [];

    // Full name: letters and spaces only, no digits
    if (!preg_match('/^[a-zA-Z\s\'\-\.]+$/', $full_name)) {
        $errors[] = "Full Name must contain letters only (no numbers).";
    }

    // Student ID: digits only (and optionally dashes like UNI-2024-001 → allow alphanumeric+dash)
    if (!preg_match('/^[A-Z0-9\-]+$/i', $student_id)) {
        $errors[] = "Student ID must contain only letters, numbers, and dashes.";
    }

    // Phone: digits, spaces, +, dashes only — no letters
    if (!preg_match('/^[\d\s\+\-\(\)]+$/', $phone)) {
        $errors[] = "Phone Number must contain digits only (no letters).";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO university_students (full_name, student_id, email, phone, faculty, year_level, address)
                VALUES ('$full_name','$student_id','$email','$phone','$faculty','$year_level','$address')";
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?msg=Student registered successfully!");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Student — UniReg</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #0a0a0f;
      --surface: #12121a;
      --card: #1a1a26;
      --border: rgba(255,255,255,0.07);
      --accent: #6c63ff;
      --accent2: #ff6584;
      --text: #f0f0f8;
      --muted: #7a7a9a;
      --error: #ff4d6d;
      --error-bg: rgba(255,77,109,0.08);
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      min-height: 100vh;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: radial-gradient(ellipse at 30% 20%, rgba(108,99,255,0.1) 0%, transparent 50%),
                  radial-gradient(ellipse at 70% 80%, rgba(255,101,132,0.07) 0%, transparent 50%);
      pointer-events: none;
      z-index: 0;
    }

    header {
      position: sticky; top: 0; z-index: 10;
      padding: 24px 48px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid var(--border);
      backdrop-filter: blur(20px);
      background: rgba(10,10,15,0.7);
    }

    .logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .logo-icon {
      width: 38px; height: 38px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px;
    }
    .logo-text { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 900; color: var(--text); }
    .logo-text span { color: var(--accent); }

    .back-btn {
      display: flex; align-items: center; gap: 8px;
      color: var(--muted); text-decoration: none; font-size: 14px;
      padding: 8px 16px; border-radius: 10px; border: 1px solid var(--border);
      transition: all 0.2s;
    }
    .back-btn:hover { color: var(--text); border-color: var(--accent); background: rgba(108,99,255,0.08); }

    main {
      position: relative; z-index: 1;
      max-width: 780px; margin: 0 auto; padding: 56px 24px;
    }

    .form-header {
      margin-bottom: 40px;
      animation: slideUp 0.5s ease both;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .form-header h1 {
      font-family: 'Playfair Display', serif;
      font-size: 40px; font-weight: 900;
      line-height: 1.1; margin-bottom: 8px;
    }

    .form-header h1 span {
      background: linear-gradient(90deg, var(--accent), var(--accent2));
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .form-header p { color: var(--muted); font-size: 15px; font-weight: 300; }

    /* Server-side error box */
    .error-box {
      background: var(--error-bg);
      border: 1px solid rgba(255,77,109,0.3);
      border-radius: 14px;
      padding: 16px 20px;
      margin-bottom: 24px;
      animation: slideUp 0.4s ease both;
    }
    .error-box ul { list-style: none; }
    .error-box ul li {
      color: var(--error);
      font-size: 13px;
      padding: 3px 0;
    }
    .error-box ul li::before { content: "⚠ "; }

    .form-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 24px;
      padding: 40px;
      animation: slideUp 0.5s ease 0.1s both;
    }

    .section-title {
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 1px solid var(--border);
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 28px;
    }

    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-group.full { grid-column: 1 / -1; }

    label {
      font-size: 13px;
      font-weight: 500;
      color: var(--muted);
      letter-spacing: 0.3px;
    }

    input, select, textarea {
      background: rgba(255,255,255,0.04);
      border: 1px solid var(--border);
      color: var(--text);
      padding: 13px 16px;
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      outline: none;
      transition: all 0.3s ease;
      width: 100%;
    }

    input::placeholder, textarea::placeholder { color: rgba(122,122,154,0.5); }

    input:focus, select:focus, textarea:focus {
      border-color: var(--accent);
      background: rgba(108,99,255,0.05);
      box-shadow: 0 0 0 3px rgba(108,99,255,0.12);
    }

    /* Inline field error state */
    input.is-invalid, select.is-invalid, textarea.is-invalid {
      border-color: var(--error) !important;
      background: var(--error-bg) !important;
      box-shadow: 0 0 0 3px rgba(255,77,109,0.12) !important;
    }

    .field-hint {
      font-size: 11px;
      color: var(--muted);
      margin-top: 2px;
    }

    .field-error {
      font-size: 11px;
      color: var(--error);
      margin-top: 2px;
      display: none;
    }

    select option { background: #1a1a26; color: var(--text); }

    textarea { resize: vertical; min-height: 90px; }

    .form-actions {
      display: flex; gap: 12px; margin-top: 32px;
      padding-top: 28px; border-top: 1px solid var(--border);
    }

    .btn-submit {
      flex: 1;
      background: linear-gradient(135deg, var(--accent), #8b85ff);
      color: white; border: none; cursor: pointer;
      padding: 15px 32px; border-radius: 13px;
      font-family: 'DM Sans', sans-serif;
      font-size: 15px; font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 20px rgba(108,99,255,0.3);
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 32px rgba(108,99,255,0.45);
    }

    .btn-cancel {
      padding: 15px 28px; border-radius: 13px;
      border: 1px solid var(--border);
      background: transparent; color: var(--muted);
      text-decoration: none; font-size: 15px;
      font-family: 'DM Sans', sans-serif;
      transition: all 0.2s; display: flex; align-items: center;
    }

    .btn-cancel:hover { border-color: rgba(255,77,109,0.4); color: #ff4d6d; }

    .form-group { animation: slideUp 0.4s ease both; }
    .form-group:nth-child(1) { animation-delay: 0.1s; }
    .form-group:nth-child(2) { animation-delay: 0.15s; }
    .form-group:nth-child(3) { animation-delay: 0.2s; }
    .form-group:nth-child(4) { animation-delay: 0.25s; }
    .form-group:nth-child(5) { animation-delay: 0.3s; }
    .form-group:nth-child(6) { animation-delay: 0.35s; }

    @media(max-width: 600px) {
      header { padding: 20px 20px; }
      .form-grid { grid-template-columns: 1fr; }
      .form-card { padding: 24px 20px; }
    }
  </style>
</head>
<body>

<header>
  <a href="index.php" class="logo">
    <div class="logo-icon">🎓</div>
    <div class="logo-text">Uni<span>Reg</span></div>
  </a>
  <a href="index.php" class="back-btn">← Back to Registry</a>
</header>

<main>
  <div class="form-header">
    <h1>Register <span>New Student</span></h1>
    <p>Fill in the enrollment details to register a new student</p>
  </div>

  <?php if (!empty($errors)): ?>
  <div class="error-box">
    <ul>
      <?php foreach ($errors as $err): ?>
        <li><?= htmlspecialchars($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <div class="form-card">
    <form method="POST" id="registerForm" novalidate>

      <div class="section-title">Personal Information</div>
      <div class="form-grid">

        <div class="form-group">
          <label>Full Name</label>
          <!-- letters & spaces only — digits are blocked -->
          <input type="text" name="full_name" id="full_name"
                 placeholder="e.g. Sophea Chan"
                 value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>"
                 required>
          <span class="field-hint">Letters only — no numbers</span>
          <span class="field-error" id="full_name_err">Full name cannot contain digits.</span>
        </div>

        <div class="form-group">
          <label>Student ID</label>
          <!-- digits, letters, dashes only -->
          <input type="text" name="student_id" id="student_id"
                 placeholder="e.g. UNI-2024-001"
                 value="<?= isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : '' ?>"
                 required>
          <span class="field-hint">Letters, numbers and dashes only</span>
          <span class="field-error" id="student_id_err">Student ID can only contain letters, numbers, and dashes.</span>
        </div>

        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" id="email"
                 placeholder="student@university.edu"
                 value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                 required>
          <span class="field-error" id="email_err">Please enter a valid email address.</span>
        </div>

        <div class="form-group">
          <label>Phone Number</label>
          <!-- digits, +, -, spaces only — letters are blocked -->
          <input type="text" name="phone" id="phone"
                 placeholder="+855 12 345 678"
                 value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>"
                 required>
          <span class="field-hint">Digits only — no letters</span>
          <span class="field-error" id="phone_err">Phone number cannot contain letters.</span>
        </div>

        <div class="form-group full">
          <label>Address</label>
          <textarea name="address" placeholder="Home address…"><?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?></textarea>
        </div>
      </div>

      <div class="section-title">Academic Information</div>
      <div class="form-grid">
        <div class="form-group">
          <label>Faculty / Department</label>
          <select name="faculty" required>
            <option value="" disabled <?= !isset($_POST['faculty']) ? 'selected' : '' ?>>Select faculty…</option>
            <?php
              $faculties = ['Computer Science','Engineering','Business Administration','Medicine','Law','Arts & Humanities','Natural Sciences','Education'];
              foreach ($faculties as $f) {
                $sel = (isset($_POST['faculty']) && $_POST['faculty'] === $f) ? 'selected' : '';
                echo "<option $sel>$f</option>";
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Year Level</label>
          <select name="year_level" required>
            <option value="" disabled <?= !isset($_POST['year_level']) ? 'selected' : '' ?>>Select year…</option>
            <?php
              $years = ['1'=>'Year 1 — Freshman','2'=>'Year 2 — Sophomore','3'=>'Year 3 — Junior','4'=>'Year 4 — Senior','5'=>'Year 5 — Graduate'];
              foreach ($years as $v => $label) {
                $sel = (isset($_POST['year_level']) && $_POST['year_level'] == $v) ? 'selected' : '';
                echo "<option value=\"$v\" $sel>$label</option>";
              }
            ?>
          </select>
        </div>
      </div>

      <div class="form-actions">
        <a href="index.php" class="btn-cancel">Cancel</a>
        <button type="submit" class="btn-submit">🎓 Register Student</button>
      </div>

    </form>
  </div>
</main>

<script>
  // ─── Validation Rules ───────────────────────────────────────────────────────
  const rules = {
    full_name: {
      // Must be letters, spaces, apostrophes, hyphens, dots only — NO digits
      pattern: /^[a-zA-Z\s'\-\.]+$/,
      errorId: 'full_name_err',
      message: 'Full name cannot contain digits.'
    },
    student_id: {
      // Letters, digits, dashes — no special chars
      pattern: /^[A-Za-z0-9\-]+$/,
      errorId: 'student_id_err',
      message: 'Student ID can only contain letters, numbers, and dashes.'
    },
    phone: {
      // Digits, +, -, spaces, parentheses only — NO letters
      pattern: /^[\d\s\+\-\(\)]+$/,
      errorId: 'phone_err',
      message: 'Phone number cannot contain letters.'
    }
  };

  // ─── Block invalid keypresses in real-time ───────────────────────────────────
  document.getElementById('full_name').addEventListener('keypress', function(e) {
    // Block digit keys
    if (/\d/.test(e.key)) {
      e.preventDefault();
      showError('full_name', 'full_name_err');
    }
  });

  document.getElementById('phone').addEventListener('keypress', function(e) {
    // Block letter keys
    if (/[a-zA-Z]/.test(e.key)) {
      e.preventDefault();
      showError('phone', 'phone_err');
    }
  });

  // ─── Paste protection ────────────────────────────────────────────────────────
  document.getElementById('full_name').addEventListener('paste', function(e) {
    e.preventDefault();
    const pasted = (e.clipboardData || window.clipboardData).getData('text');
    const cleaned = pasted.replace(/\d/g, ''); // strip digits
    document.execCommand('insertText', false, cleaned);
    if (pasted !== cleaned) showError('full_name', 'full_name_err');
  });

  document.getElementById('phone').addEventListener('paste', function(e) {
    e.preventDefault();
    const pasted = (e.clipboardData || window.clipboardData).getData('text');
    const cleaned = pasted.replace(/[a-zA-Z]/g, ''); // strip letters
    document.execCommand('insertText', false, cleaned);
    if (pasted !== cleaned) showError('phone', 'phone_err');
  });

  // ─── Inline validation on blur ───────────────────────────────────────────────
  Object.keys(rules).forEach(fieldId => {
    const input = document.getElementById(fieldId);
    if (!input) return;

    input.addEventListener('blur', function() {
      const rule = rules[fieldId];
      if (this.value && !rule.pattern.test(this.value)) {
        showError(fieldId, rule.errorId);
      } else {
        clearError(fieldId, rule.errorId);
      }
    });

    input.addEventListener('input', function() {
      const rule = rules[fieldId];
      if (rule.pattern.test(this.value) || this.value === '') {
        clearError(fieldId, rule.errorId);
      }
    });
  });

  // ─── Form submit validation ──────────────────────────────────────────────────
  document.getElementById('registerForm').addEventListener('submit', function(e) {
    let valid = true;

    Object.keys(rules).forEach(fieldId => {
      const input = document.getElementById(fieldId);
      if (!input) return;
      const rule = rules[fieldId];
      if (input.value && !rule.pattern.test(input.value)) {
        showError(fieldId, rule.errorId);
        valid = false;
      }
    });

    // Email check
    const email = document.getElementById('email');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
      email.classList.add('is-invalid');
      document.getElementById('email_err').style.display = 'block';
      valid = false;
    } else {
      email.classList.remove('is-invalid');
      document.getElementById('email_err').style.display = 'none';
    }

    if (!valid) e.preventDefault();
  });

  // ─── Helpers ─────────────────────────────────────────────────────────────────
  function showError(fieldId, errorId) {
    const input = document.getElementById(fieldId);
    const errSpan = document.getElementById(errorId);
    if (input) input.classList.add('is-invalid');
    if (errSpan) errSpan.style.display = 'block';
  }

  function clearError(fieldId, errorId) {
    const input = document.getElementById(fieldId);
    const errSpan = document.getElementById(errorId);
    if (input) input.classList.remove('is-invalid');
    if (errSpan) errSpan.style.display = 'none';
  }
</script>

</body>
</html>
