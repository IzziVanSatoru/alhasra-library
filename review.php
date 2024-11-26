<?php
include 'includes/functions.php';

$reviews = getBooks($conn); // Assume you have reviews stored with book details
?>
<?php include 'templates/header.php'; ?>
<div style="padding: 20px;">
    <h2>Book Reviews</h2>
    <div>
        <?php while ($row = $reviews->fetch_assoc()): ?>
            <div style="border: 1px solid #ddd; margin-bottom: 20px; padding: 10px;">
                <h3><?= $row['title']; ?> by <?= $row['author']; ?></h3>
                <p>Rating: <?= rand(4, 5); ?> / 5</p>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
