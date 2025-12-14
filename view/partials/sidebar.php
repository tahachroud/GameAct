<?php 
function isActive($page) {
    return ($_GET['action'] ?? 'dashboard') === $page ? 'active' : '';
}
?>

<aside class="gameact-sidebar">

    <!-- SIDEBAR TITLE -->


    <!-- GAMER PANEL STYLE MENU -->
    <ul class="sidebar-menu">

        <li>
            <a href="index-community.php?action=dashboard" class="<?= isActive('dashboard') ?>">
                <i class="fa-solid fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="index-community.php?action=posts" class="<?= isActive('posts') ?>">
                <i class="fa-solid fa-newspaper"></i>
                <span>Posts</span>
            </a>
        </li>

        <li>
            <a href="index-community.php?action=comments" class="<?= isActive('comments') ?>">
                <i class="fa-solid fa-comments"></i>
                <span>Comments</span>
            </a>
        </li>

        <li>
            <a href="index-community.php?action=users" class="<?= isActive('users') ?>">
                <i class="fa-solid fa-user"></i>
                <span>Users</span>
            </a>
        </li>

        <li>
            <a href="index-community.php?action=community" class="<?= isActive('community') ?>">
                <i class="fa-solid fa-users"></i>
                <span>Community</span>

                <!-- Dot indicator -->
                <?php if (isActive('community')): ?>
                    <span class="dot"></span>
                <?php endif; ?>
            </a>
        </li>
        <LI><a href="listevents"></a></LI>

    </ul>
</aside>
