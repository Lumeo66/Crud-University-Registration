<?php include('config.php');
$id = (int)$_GET['id'];
$student = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM university_students WHERE id=$id"));
if (!$student) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);
    $faculty    = mysqli_real_escape_string($conn, $_POST['faculty']);
    $year_level = mysqli_real_escape_string($conn, $_POST['year_level']);
    $address    = mysqli_real_escape_string($conn, $_POST['address']);

    $sql = "UPDATE university_students SET
              full_name='$full_name', student_id='$student_id', email='$email',
              phone='$phone', faculty='$faculty', year_level='$year_level', address='$address'
            WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=Student updated successfully!");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Student — UniReg</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #0a0a0f;
      --card: #1a1a26;
      --border: rgba(255,255,255,0.07);
      --accent: #6c63ff;
      --accent2: #ff6584;
      --text: #f0f0f8;
      --muted: #7a7a9a;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      background: var(--bg); color: var(--text);
      font-family: 'DM Sans', sans-serif; min-height: 100vh;
    }
    body::before {
      content: ''; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: radial-gradient(ellipse at 70% 20%, rgba(255,101,132,0.09) 0%, transparent 50%),
                  radial-gradient(ellipse at 30% 80%, rgba(108,99,255,0.08) 0%, transparent 50%);
      pointer-events: none; z-index: 0;
    }
    header {
      position: sticky; top: 0; z-index: 10;
      padding: 24px 48px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid var(--border);
      backdrop-filter: blur(20px); background: rgba(10,10,15,0.7);
    }
    .logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .logo-icon {
      width: 38px; height: 38px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;
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

    main { position: relative; z-index: 1; max-width: 780px; margin: 0 auto; padding: 56px 24px; }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .form-header { margin-bottom: 40px; animation: slideUp 0.5s ease both; }
    .form-header h1 { font-family: 'Playfair Display', serif; font-size: 40px; font-weight: 900; margin-bottom: 8px; }
    .form-header h1 span {
      background: linear-gradient(90deg, var(--accent2), var(--accent));
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;  
    }
    .form-header p { color: var(--muted); font-size: 15px; font-weight: 300; }

    .student-badge {
      display: inline-flex; align-items: center; gap: 10px;
      background: rgba(255,101,132,0.1);
      border: 1px solid rgba(255,101,132,0.2);
      border-radius: 12px; padding: 10px 16px;
      margin-bottom: 28px; font-size: 14px; color: #ff8fa3;
      animation: slideUp 0.5s ease 0.05s both;
    }

    .form-card {
      background: var(--card); border: 1px solid var(--border);
      border-radius: 24px; padding: 40px;
      animation: slideUp 0.5s ease 0.1s both;
    }

    .section-title {
      font-size: 11px; font-weight: 500; letter-spacing: 2px;
      text-transform: uppercase; color: var(--accent2);
      margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border);
    }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px; }
    .form-group { display: flex; flex-direction: column; gap: 8px; animation: slideUp 0.4s ease both; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group:nth-child(1){animation-delay:0.1s} .form-group:nth-child(2){animation-delay:0.15s}
    .form-group:nth-child(3){animation-delay:0.2s} .form-group:nth-child(4){animation-delay:0.25s}
    .form-group:nth-child(5){animation-delay:0.3s} .form-group:nth-child(6){animation-delay:0.35s}

    label { font-size: 13px; font-weight: 500; color: var(--muted); }

    input, select, textarea {
      background: rgba(255,255,255,0.04); border: 1px solid var(--border);
      color: var(--text); padding: 13px 16px; border-radius: 12px;
      font-family: 'DM Sans', sans-serif; font-size: 14px; outline: none;
      transition: all 0.3s; width: 100%;
    }
    input:focus, select:focus, textarea:focus {
      border-color: var(--accent2);
      background: rgba(255,101,132,0.05);
      box-shadow: 0 0 0 3px rgba(255,101,132,0.1);
    }
    select option { background: #1a1a26; }
    textarea { resize: vertical; min-height: 90px; }

    .form-actions {
      display: flex; gap: 12px; margin-top: 32px;
      padding-top: 28px; border-top: 1px solid var(--border);
    }

    .btn-submit {
      flex: 1; background: linear-gradient(135deg, var(--accent2), #ff8fa3);
      color: white; border: none; cursor: pointer;
      padding: 15px 32px; border-radius: 13px;
      font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 500;
      transition: all 0.3s; box-shadow: 0 4px 20px rgba(255,101,132,0.3);
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(255,101,132,0.45); }

    .btn-cancel {
      padding: 15px 28px; border-radius: 13px;
      border: 1px solid var(--border); background: transparent;
      color: var(--muted); text-decoration: none; font-size: 15px;
      font-family: 'DM Sans', sans-serif; transition: all 0.2s; display: flex; align-items: center;
    }
    .btn-cancel:hover { border-color: rgba(108,99,255,0.4); color: var(--accent); }

    @media(max-width:600px) {
      header { padding: 20px; }
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
    <h1>Edit <span>Student Profile</span></h1>
    <p>Update enrollment and academic information</p>
  </div>

  <div class="student-badge">
    ✎ Editing: <strong><?= htmlspecialchars($student['full_name']) ?></strong> — <?= htmlspecialchars($student['student_id']) ?>
  </div>

  <div class="form-card">
    <form method="POST">

      <div class="section-title">Personal Information</div>
      <div class="form-grid">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" required>
        </div>
        <div class="form-group">
          <label>Student ID</label>
          <input type="text" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>" required>
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
        </div>
        <div class="form-group">
          <label>Phone Number</label>
          <input type="text" name="phone" value="<?= htmlspecialchars($student['phone']) ?>" required>
        </div>
        <div class="form-group full">
          <label>Address</label>
          <textarea name="address"><?= htmlspecialchars($student['address']) ?></textarea>
        </div>
      </div>

      <div class="section-title">Academic Information</div>
      <div class="form-grid">
        <div class="form-group">
          <label>Faculty / Department</label>
          <select name="faculty" required>
            <?php
              $faculties = ['Computer Science','Engineering','Business Administration','Medicine','Law','Arts & Humanities','Natural Sciences','Education'];
              foreach($faculties as $f) {
                $sel = $student['faculty'] == $f ? 'selected' : '';
                echo "<option $sel>$f</option>";
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Year Level</label>
          <select name="year_level" required>
            <?php
              $years = [1=>'Year 1 — Freshman',2=>'Year 2 — Sophomore',3=>'Year 3 — Junior',4=>'Year 4 — Senior',5=>'Year 5 — Graduate'];
              foreach($years as $v=>$label) {
                $sel = $student['year_level'] == $v ? 'selected' : '';
                echo "<option value='$v' $sel>$label</option>";
              }
            ?>
          </select>
        </div>
      </div>

      <div class="form-actions">
        <a href="index.php" class="btn-cancel">Cancel</a>
        <button type="submit" class="btn-submit">✓ Save Changes</button>
      </div>

    </form>
  </div>
</main>

</body>
</html>