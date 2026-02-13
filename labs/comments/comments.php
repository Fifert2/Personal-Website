<?php
// TEMP: show PHP errors so we can debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// AI-ASSISTED (ChatGPT, 2025-12-02):
// Basic structure for DB connection, form handling, and prepared statements.

// --- 1. DATABASE CONNECTION (similar to Lab 9 pattern) ---
$host = 'localhost';
$user = 'phpmyadmin';   // TODO: CHANGE THIS to your actual MariaDB username
$pass = 'Adetola15@%$';   // TODO: CHANGE THIS to your actual MariaDB password
$dbname = 'mySIte';

$db = new mysqli($host, $user, $pass, $dbname);

if ($db->connect_error) {
   die('Could not connect to database: ' . $db->connect_error);
}

// --- 2. SETUP VARIABLES FOR FORM / ERRORS ---
$errors = array();
$successMsg = '';

$name    = '';
$email   = '';
$comment = '';
$feature = '';

// --- 3. SERVER-SIDE VALIDATION + INSERT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Trim input
   $name    = trim($_POST['name'] ?? '');
   $email   = trim($_POST['email'] ?? '');
   $comment = trim($_POST['comment'] ?? '');
   $feature = trim($_POST['feature'] ?? '');

   // Required checks
   if ($name === '') {
      $errors['name'] = 'Name is required.';
   }

   if ($email === '') {
      $errors['email'] = 'Email is required.';
   } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Please enter a valid email address.';
   }

   if ($comment === '') {
      $errors['comment'] = 'Comment is required.';
   }

   // Only attempt DB insert if no validation errors
   if (count($errors) === 0) {
      // AI-ASSISTED (ChatGPT): Prepared INSERT pattern, based on Lab 9 insert example.
      $insertQuery = "INSERT INTO siteComments 
         (visitor_name, email, comment_text, feature_suggestion, status)
         VALUES (?, ?, ?, ?, 'approved')";

      $statement = $db->prepare($insertQuery);

      if ($statement === false) {
         $errors['db'] = 'Database error: could not prepare statement.';
      } else {
         // "ssss" = 4 string parameters
         $statement->bind_param("ssss", $name, $email, $comment, $feature);

         if ($statement->execute()) {
            $successMsg = 'Thanks for your comment!';
            // Clear fields after success
            $name = $email = $comment = $feature = '';
         } else {
            $errors['db'] = 'Database error: could not save your comment.';
         }

         $statement->close();
      }
   }
}

// --- 4. FETCH APPROVED COMMENTS (NEWEST FIRST) ---
$comments = array();

// Simple select, similar to Lab 9 select samples.
$selectQuery = "SELECT visitor_name, comment_text, feature_suggestion, created_at
                FROM siteComments
                WHERE status = 'approved'
                ORDER BY created_at DESC";

if ($result = $db->query($selectQuery)) {
   while ($row = $result->fetch_assoc()) {
      $comments[] = $row;
   }
   $result->free();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Comments | AbdurRahim Islam</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- Use same CSS as main site / Lab 8 -->
   <link rel="stylesheet" href="/iit/labs/main.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

   <!-- Simple Navigation (based on Lab 8 navbar) -->
   <nav class="navbar">
      <div class="nav-container">
         <div class="nav-logo">
            <h1>AbdurRahim Islam</h1>
            <span class="nav-subtitle">ITWS Developer</span>
         </div>
         <ul class="nav-menu">
            <li><a href="/iit/modern_index.html" class="nav-link">Home</a></li>
            <li><a href="/iit/labs/labs_html_modern.html" class="nav-link">Projects</a></li>
            <li><a href="/iit/labs/comments/comments.php" class="nav-link active">Comments</a></li>
         </ul>
         <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
         </div>
      </div>
   </nav>

   <!-- Page Header (styled like your Lab 8 header) -->
   <section class="page-header">
      <div class="container">
         <div class="page-header-content">
            <span class="page-tag">Quiz 3</span>
            <h1>Site Comments</h1>
            <p>View visitor feedback and leave your own comment about this website.</p>
         </div>
      </div>
   </section>

   <!-- Comments + Form Section -->
   <section class="section lab-section">
      <div class="container">
         <div class="lab-content">

            <!-- Existing Comments -->
            <div class="lab-solution">
               <div class="section-header">
                  <span class="section-tag">Community</span>
                  <h3>Visitor Comments</h3>
               </div>
<!-- AI-ASSISTED (ChatGPT):
               <?php if ($successMsg !== ''): ?>
                  <div class="content-box" style="border-left: 4px solid #4CAF50;">
                     <p><?php echo htmlspecialchars($successMsg); ?></p>
                  </div>
               <?php endif; ?>

               <?php if (count($errors) > 0): ?>
                  <div class="content-box" style="border-left: 4px solid #f44336;">
                     <ul>
                     <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                     <?php endforeach; ?>
                     </ul>
                  </div>
               <?php endif; ?>

               <div class="content-box">
                  <?php if (count($comments) === 0): ?>
                     <p>No comments yet. Be the first to leave one!</p>
                  <?php else: ?>
                     <?php foreach ($comments as $c): ?>
                        <article class="comment-item" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e0e0e0;">
                           <header class="comment-header" style="display:flex;justify-content:space-between; margin-bottom: 0.5rem;">
                              <strong style="color: #0066ff;"><?php echo htmlspecialchars($c['visitor_name']); ?></strong>
                              <span class="comment-date" style="color: #999; font-size: 0.9rem;">
                                 <?php echo htmlspecialchars($c['created_at']); ?>
                              </span>
                           </header>
                           <p class="comment-text" style="margin-bottom: 0.5rem;">
                              <?php echo nl2br(htmlspecialchars($c['comment_text'])); ?>
                           </p>
                           <?php if (!empty($c['feature_suggestion'])): ?>
                              <p class="comment-feature" style="background: #f8f9fa; padding: 0.75rem; border-radius: 6px; font-size: 0.95rem;">
                                 <strong>Feature suggestion:</strong>
                                 <?php echo nl2br(htmlspecialchars($c['feature_suggestion'])); ?>
                              </p>
                           <?php endif; ?>
                        </article>
                     <?php endforeach; ?>
                  <?php endif; ?>
               </div>
            </div>

            <! Comment Form -->
            <div class="lab-task">
               <div class="section-header">
                  <span class="section-tag">Leave a Comment</span>
                  <h3>Your Feedback</h3>
                  <p class="section-subtitle">Required fields: Name, Email, and Comment.</p>
               </div>

               <div class="content-box">
                  <form id="commentForm" method="post" action="comments.php">
                     <div class="form-row">
                        <input 
                           type="text" 
                           name="name" 
                           placeholder="Name *"
                           value="<?php echo htmlspecialchars($name); ?>"
                           class="form-input"
                        />
                     </div>
                     <div class="form-row">
                        <input 
                           type="email" 
                           name="email" 
                           placeholder="Email *"
                           value="<?php echo htmlspecialchars($email); ?>"
                           class="form-input"
                        />
                     </div>
                     <div class="form-row">
                        <textarea
                           name="comment"
                           placeholder="Your comment *"
                           class="form-textarea"
                           rows="4"
                        ><?php echo htmlspecialchars($comment); ?></textarea>
                     </div>
                     <div class="form-row">
                        <textarea
                           name="feature"
                           placeholder="Feature suggestion (optional)"
                           class="form-textarea"
                           rows="3"
                        ><?php echo htmlspecialchars($feature); ?></textarea>
                     </div>
                     <button type="submit" class="btn-submit">Submit Comment</button>
                  </form>
               </div>
            </div>

         </div>
      </div>
   </section>

   <!-- Simple Footer -->
   <footer class="footer">
      <div class="container">
         <div class="footer-content">
            <div class="footer-social">
               <a href="#" class="social-link" aria-label="Facebook">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                     <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                  </svg>
               </a>
               <a href="#" class="social-link" aria-label="LinkedIn">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                     <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                  </svg>
               </a>
            </div>
            <p class="footer-credit">
               Built for ITWS Quiz 3 | AbdurRahim Islam
            </p>
         </div>
      </div>
   </footer>

   <script>
      // Mobile menu toggle (copied style from Lab 8)
      const menuToggle = document.querySelector('.menu-toggle');
      const navMenu = document.querySelector('.nav-menu');

      menuToggle.addEventListener('click', () => {
         menuToggle.classList.toggle('active');
         navMenu.classList.toggle('active');
      });

      // AI-ASSISTED (ChatGPT): Basic jQuery validation, similar complexity to labprojects.js.
      $(document).ready(function () {
         $('#commentForm').on('submit', function (e) {
            let hasError = false;

            // Clear any old inline error text
            $('.client-error').remove();

            let name = $.trim($('input[name="name"]').val());
            let email = $.trim($('input[name="email"]').val());
            let comment = $.trim($('textarea[name="comment"]').val());

            if (name === '') {
               $('input[name="name"]').after('<div class="client-error" style="color:red;font-size:0.9rem;margin-top:0.25rem;">Name is required.</div>');
               hasError = true;
            }

            if (email === '') {
               $('input[name="email"]').after('<div class="client-error" style="color:red;font-size:0.9rem;margin-top:0.25rem;">Email is required.</div>');
               hasError = true;
            } else {
               // Very simple email check
               let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
               if (!emailPattern.test(email)) {
                  $('input[name="email"]').after('<div class="client-error" style="color:red;font-size:0.9rem;margin-top:0.25rem;">Enter a valid email address.</div>');
                  hasError = true;
               }
            }

            if (comment === '') {
               $('textarea[name="comment"]').after('<div class="client-error" style="color:red;font-size:0.9rem;margin-top:0.25rem;">Comment is required.</div>');
               hasError = true;
            }

            if (hasError) {
               e.preventDefault();
            }
         });
      });
   </script>
</body>
</html>