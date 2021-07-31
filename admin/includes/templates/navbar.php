<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="app-nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>

    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li><a class="nav-link" href="categories.php"><?php echo lang('SECTIONS') ?></a></li>
        <li><a class="nav-link" href="items.php"><?php echo lang('ITEMS') ?></a></li>
        <li><a class="nav-link" href="members.php"><?php echo lang('MEMBERS') ?></a></li>
        <li><a class="nav-link" href="comments.php"><?php echo lang('COMMENTS') ?></a></li>
        <li><a class="nav-link" href="#"><?php echo lang('STATISTICS') ?></a></li>
        <li><a class="nav-link" href="#"><?php echo lang('LOGS') ?></a></li>
      </ul>

      <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Mohamed</a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="../index.php">Visit Shop</a></li>
            <li><a class="dropdown-item" href="members.php?do=Edit&userId=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
      </div>
    </div>
  </div>
</nav>