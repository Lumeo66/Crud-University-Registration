<?php include('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UniReg — Student Registry</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #0a0a0f;
      --surface: #12121a;
      --card: #1a1a26;
      --border: rgba(255,255,255,0.07);
      --accent: #6c63ff;
      --accent2: #ff6584;
      --gold: #f5c842;
      --text: #f0f0f8;
      --muted: #7a7a9a;
      --success: #43e97b;
      --danger: #ff4d6d;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Animated background */
    body::before {
      content: '';
      position: fixed;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(ellipse at 20% 20%, rgba(108,99,255,0.08) 0%, transparent 50%),
                  radial-gradient(ellipse at 80% 80%, rgba(255,101,132,0.06) 0%, transparent 50%),
                  radial-gradient(ellipse at 50% 50%, rgba(245,200,66,0.03) 0%, transparent 60%);
      animation: bgPulse 12s ease-in-out infinite alternate;
      pointer-events: none;
      z-index: 0;
    }

    @keyframes bgPulse {
      0% { transform: scale(1) rotate(0deg); }
      100% { transform: scale(1.1) rotate(3deg); }
    }

    /* Header */
    header {
      position: relative;
      z-index: 10;
      padding: 32px 48px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid var(--border);
      backdrop-filter: blur(20px);
      background: rgba(10,10,15,0.6);
      position: sticky;
      top: 0;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .logo-icon {
      width: 42px;
      height: 42px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      box-shadow: 0 0 24px rgba(108,99,255,0.4);
    }

    .logo-text {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      font-weight: 900;
      letter-spacing: -0.5px;
    }

    .logo-text span { color: var(--accent); }

    .header-badge {
      background: rgba(108,99,255,0.15);
      border: 1px solid rgba(108,99,255,0.3);
      color: var(--accent);
      padding: 6px 16px;
      border-radius: 100px;
      font-size: 13px;
      font-weight: 500;
    }

    /* Main content */
    main {
      position: relative;
      z-index: 1;
      max-width: 1300px;
      margin: 0 auto;
      padding: 48px 32px;
    }

    /* Page title */
    .page-header {
      margin-bottom: 40px;
      animation: slideDown 0.6s ease both;
    }

    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .page-title {
      font-family: 'Playfair Display', serif;
      font-size: 48px;
      font-weight: 900;
      line-height: 1.1;
      margin-bottom: 8px;
    }

    .page-title .highlight {
      background: linear-gradient(90deg, var(--accent), var(--accent2));
      -webkit-background-clip: text;
      background-clip: text;  
      -webkit-text-fill-color: transparent;
    }

    .page-subtitle {
      color: var(--muted);
      font-size: 16px;
      font-weight: 300;
    }

    /* Stats bar */
    .stats-bar {
      display: flex;
      gap: 20px;
      margin-bottom: 36px;
      animation: slideDown 0.6s ease 0.1s both;
    }

    .stat-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 20px 28px;
      flex: 1;
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 40px rgba(0,0,0,0.3);
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 4px; height: 100%;
      background: linear-gradient(180deg, var(--accent), var(--accent2));
      border-radius: 4px 0 0 4px;
    }

    .stat-number {
      font-family: 'Playfair Display', serif;
      font-size: 36px;
      font-weight: 700;
      color: var(--text);
    }

    .stat-label {
      font-size: 13px;
      color: var(--muted);
      margin-top: 4px;
    }

    /* Toolbar */
    .toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
      gap: 16px;
      animation: slideDown 0.6s ease 0.2s both;
    }

    .search-box {
      position: relative;
      flex: 1;
      max-width: 400px;
    }

    .search-box input {
      width: 100%;
      background: var(--card);
      border: 1px solid var(--border);
      color: var(--text);
      padding: 12px 16px 12px 44px;
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      outline: none;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .search-box input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(108,99,255,0.15);
    }

    .search-box::before {
      content: '⌕';
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      font-size: 20px;
    }

    .btn-add {
      display: flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(135deg, var(--accent), #8b85ff);
      color: white;
      padding: 12px 24px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 500;
      font-size: 14px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 20px rgba(108,99,255,0.35);
      white-space: nowrap;
    }

    .btn-add:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(108,99,255,0.5);
    }

    .btn-add .plus { font-size: 18px; font-weight: 300; }

    /* Alert */
    .alert {
      padding: 14px 20px;
      border-radius: 12px;
      margin-bottom: 24px;
      font-size: 14px;
      animation: slideDown 0.4s ease both;
    }
    .alert-success {
      background: rgba(67,233,123,0.1);
      border: 1px solid rgba(67,233,123,0.25);
      color: var(--success);
    }

    /* Table */
    .table-wrapper {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 20px;
      overflow: hidden;
      animation: slideDown 0.6s ease 0.3s both;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead {
      background: rgba(108,99,255,0.08);
      border-bottom: 1px solid var(--border);
    }

    th {
      padding: 16px 20px;
      text-align: left;
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--muted);
    }

    tbody tr {
      border-bottom: 1px solid var(--border);
      transition: background 0.2s ease;
      animation: rowIn 0.4s ease both;
    }

    @keyframes rowIn {
      from { opacity: 0; transform: translateX(-10px); }
      to { opacity: 1; transform: translateX(0); }
    }

    tbody tr:last-child { border-bottom: none; }

    tbody tr:hover { background: rgba(255,255,255,0.03); }

    td {
      padding: 16px 20px;
      font-size: 14px;
      color: var(--text);
    }

    /* Avatar */
    .avatar {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 15px;
      color: white;
      flex-shrink: 0;
    }

    .name-cell {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .name-info .name { font-weight: 500; }
    .name-info .id-tag { font-size: 12px; color: var(--muted); margin-top: 2px; }

    /* Badge */
    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 100px;
      font-size: 12px;
      font-weight: 500;
    }

    .badge-blue { background: rgba(108,99,255,0.15); color: #a09bff; border: 1px solid rgba(108,99,255,0.25); }
    .badge-pink { background: rgba(255,101,132,0.15); color: #ff8fa3; border: 1px solid rgba(255,101,132,0.25); }
    .badge-gold { background: rgba(245,200,66,0.15); color: #f5d060; border: 1px solid rgba(245,200,66,0.25); }
    .badge-green { background: rgba(67,233,123,0.15); color: #43e97b; border: 1px solid rgba(67,233,123,0.25); }

    /* Action buttons */
    .actions { display: flex; gap: 8px; }

    .btn-icon {
      width: 34px;
      height: 34px;
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-size: 16px;
      transition: all 0.2s ease;
      border: 1px solid transparent;
    }

    .btn-edit {
      background: rgba(108,99,255,0.12);
      color: var(--accent);
      border-color: rgba(108,99,255,0.2);
    }
    .btn-edit:hover { background: var(--accent); color: white; transform: scale(1.1); }

    .btn-delete {
      background: rgba(255,77,109,0.12);
      color: var(--danger);
      border-color: rgba(255,77,109,0.2);
    }
    .btn-delete:hover { background: var(--danger); color: white; transform: scale(1.1); }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 80px 40px;
      color: var(--muted);
    }

    .empty-icon { font-size: 56px; margin-bottom: 16px; opacity: 0.5; }
    .empty-title { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--text); margin-bottom: 8px; }
    .empty-text { font-size: 14px; font-weight: 300; }

    /* Footer */
    footer {
      position: relative;
      z-index: 1;
      text-align: center;
      padding: 32px;
      color: var(--muted);
      font-size: 13px;
      border-top: 1px solid var(--border);
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--bg); }
    ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

    @media (max-width: 768px) {
      header { padding: 20px 24px; }
      main { padding: 32px 16px; }
      .page-title { font-size: 32px; }
      .stats-bar { flex-direction: column; }
      .toolbar { flex-direction: column; align-items: stretch; }
      .search-box { max-width: 100%; }
      th:nth-child(3), td:nth-child(3),
      th:nth-child(5), td:nth-child(5) { display: none; }
    }
  </style>
</head>
<body>

<header>
  <div class="logo">
    <div class="logo-icon">🎓</div>
    <div class="logo-text">Uni<span>Reg</span></div>
  </div>
  <div class="header-badge">Student Registry System</div>
</header>

<main>
  <div class="page-header">
    <h1 class="page-title">Student <span class="highlight">Registry</span></h1>
    <p class="page-subtitle">Manage university enrollments, profiles and academic records</p>
  </div>

  <?php
    $total = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM university_students"))[0];
    $courses = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(DISTINCT faculty) FROM university_students"))[0];
  ?>

  <div class="stats-bar">
    <div class="stat-card">
      <div class="stat-number"><?= $total ?></div>
      <div class="stat-label">Total Students</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?= $courses ?></div>
      <div class="stat-label">Faculties</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?= date('Y') ?></div>
      <div class="stat-label">Academic Year</div>
    </div>
  </div>

  <?php if(isset($_GET['msg'])): ?>
  <div class="alert alert-success">
    ✓ <?= htmlspecialchars($_GET['msg']) ?>
  </div>
  <?php endif; ?>

  <div class="toolbar">
    <div class="search-box">
      <input type="text" id="searchInput" placeholder="Search students by name, ID or faculty…" onkeyup="filterTable()">
    </div>
    <a href="create.php" class="btn-add">
      <span class="plus">+</span> Register Student
    </a>
  </div>

  <div class="table-wrapper">
    <?php
      $colors = ['#6c63ff','#ff6584','#f5c842','#43e97b','#38bdf8','#fb923c'];
      $result = mysqli_query($conn, "SELECT * FROM university_students ORDER BY created_at DESC");
      $rows = mysqli_num_rows($result);
    ?>
    <?php if($rows > 0): ?>
    <table id="studentTable">
      <thead>
        <tr>
          <th>Student</th>
          <th>Student ID</th>
          <th>Email</th>
          <th>Faculty</th>
          <th>Year</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $i = 0;
          while($row = mysqli_fetch_assoc($result)):
            $color = $colors[$i % count($colors)];
            $initial = strtoupper(substr($row['full_name'], 0, 1));
            $statuses = ['Active','Active','Active','On Leave'];
            $status = $statuses[$i % 4];
            $statusClass = $status == 'Active' ? 'badge-green' : 'badge-gold';
            $badgeClasses = ['badge-blue','badge-pink','badge-gold','badge-green'];
            $bc = $badgeClasses[$i % 4];
            $i++;
        ?>
        <tr style="animation-delay: <?= $i * 0.05 ?>s">
          <td>
            <div class="name-cell">
              <div class="avatar" style="background: linear-gradient(135deg, <?= $color ?>, <?= $color ?>99)"><?= $initial ?></div>
              <div class="name-info">
                <div class="name"><?= htmlspecialchars($row['full_name']) ?></div>
                <div class="id-tag"><?= htmlspecialchars($row['phone']) ?></div>
              </div>
            </div>
          </td>
          <td><span style="font-family: monospace; color: var(--muted); font-size: 13px;"><?= htmlspecialchars($row['student_id']) ?></span></td>
          <td style="color: var(--muted); font-size: 13px;"><?= htmlspecialchars($row['email']) ?></td>
          <td><span class="badge <?= $bc ?>"><?= htmlspecialchars($row['faculty']) ?></span></td>
          <td style="color: var(--muted);">Year <?= htmlspecialchars($row['year_level']) ?></td>
          <td><span class="badge <?= $statusClass ?>"><?= $status ?></span></td>
          <td>
            <div class="actions">
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn-icon btn-edit" title="Edit">✎</a>
              <a href="delete.php?id=<?= $row['id'] ?>" class="btn-icon btn-delete" title="Delete" onclick="return confirm('Remove <?= htmlspecialchars($row['full_name']) ?> from registry?')">✕</a>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
      <div class="empty-icon">🎓</div>
      <div class="empty-title">No students registered yet</div>
      <div class="empty-text">Click "Register Student" to add the first enrollment</div>
    </div>
    <?php endif; ?>
  </div>
</main>

<footer>
  UniReg © <?= date('Y') ?> — University Student Registration System
</footer>

<script>
function filterTable() {
  const input = document.getElementById('searchInput').value.toLowerCase();
  const rows = document.querySelectorAll('#studentTable tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(input) ? '' : 'none';
  });
}
</script>
</body>
</html>