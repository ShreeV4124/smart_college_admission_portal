<link rel="stylesheet" href="styles/dashboard.css">

    <div class="dashboard-container">

        <!-- Header inside grid -->
        <div class="header">
            <strong class="logo">My College</strong>
            <div class="page-title">Dashboard</div>
            <p>Welcome, <strong><?= htmlspecialchars($user['username']) ?></strong>!</p>

        </div>

        <!-- Sidebar -->
        <div class="menuBar">
            <a href="view.php">View All Students</a>
            <hr style="border: 1px solid #ccc;">

            <a href="view_users.php">View All Faculty</a>
            <hr style="border: 1px solid #ccc;">

            <a href="view_courses.php">View All Courses</a>
            <hr style="border: 1px solid #ccc;">

            <?php if ($user['role'] == 'admin' || $user['role'] == 'faculty'): ?>
            <a href="<?= BASE_URL ?>/Payment/view_payments.php">View All Payments</a>
            <hr style="border: 1px solid #ccc;">
            <?php endif; ?>
        
            <?php if ($user['role'] == 'student'): ?>
            <a href="<?= BASE_URL ?>/Payment/view_payments.php">Payments History</a>
            <hr style="border: 1px solid #ccc;">
            <?php endif; ?>


            <?php if ($user['role'] == 'admin'): ?>
                
                <a href="FeeDetails/generate_fees_form.php">Generate Course Fees</a>
                <hr style="border: 1px solid #ccc;">
                
                <a href="FeeDetails/add_fee_head_form.php">Generate Fee Heads</a>
                <hr style="border: 1px solid #ccc;">
                
                <a href="FeeDetails/add_fee_template_form.php">Generate Fee Template</a>
                        <hr style="border: 1px solid #ccc;">

            <?php endif; ?>

            
            <a href="logout.php">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="content">
            <?php if ($user['role'] == 'student'): ?>
            <br><br>
            
            <p>Hey, <strong><?= htmlspecialchars($user['username']) ?></strong>! Check all the pending work to be done, confirm your admission in our College</p><br>
            <p>Email: <?= htmlspecialchars($user['email']) ?></p>

            
                <p>Mobile: <?= htmlspecialchars($user['mobile']) ?></p>
                <p>DOB: <?= htmlspecialchars($user['dob']) ?></p>
                <p>Gender: <?= htmlspecialchars($user['gender']) ?></p>
                <p>Marks: <?= htmlspecialchars($user['marks']) ?></p>
                <p>Preferred Course: <?= $preferredCourse['course_name'] ?? 'Not Selected' ?></p>
                
                <br><hr><br>

                <?php if (!$courseAssigned): ?>
                    <h3>Your Course</h3> 
                <?php endif; ?>

                <?php if ($user['isVerified'] && !$courseAssigned): ?>
                    <p style='color:green;'>You are a verified Student!</p>
                    <p style='color:red;'>You are not eligible for your preferred course!</p>
                    <p>Your Admission is <strong style='color:brown;'>Not Confirmed</strong>.</p>

                <?php elseif ($user['isVerified'] && $courseAssigned): ?>
                    <p>You are enrolled in: <strong><?= $courseAssigned['course_name'] ?></strong></p>

                    <?php if (!$user['is_admitted']): ?>
                        <div>
                            To confirm Admission:
                            <a href="FeeDetails/fee_details.php?student_id=<?= $user['id'] ?>" class="pay-button">Proceed to Pay Fee</a>
                        </div><br>
                        
                        <button class="chatbot-pop">AI Assistant</button>

                    <?php else: ?>
                        <p style='color:green;'>Your Admission is CONFIRMED!</p>
                        
                        <a href="FeeDetails/fee_details.php?student_id=<?= $user['id'] ?>" class="pay-button">Fee Details</a><br>

                        <button class="chatbot-pop">AI Assistant</button>
                        
                    <?php endif; ?>

                <?php else: ?>
                    <p>Your Verification is <strong style='color:brown;'>Pending</strong>.</p><br>
                    <button class="chatbot-pop">AI Assistant</button>

                <?php endif; ?>

            <?php elseif ($user['role'] == 'faculty' || $user['role'] == 'admin'): 
                    if (in_array($user['role'], ['admin', 'faculty'])) {
                        // Total students
                        $sql1 = "SELECT COUNT(*) as total_students FROM students";
                        $totalStudents = getVal($sql1)['total_students'];

                        // Total verified
                        $sql2 = "SELECT COUNT(*) as total_verified FROM students WHERE isVerified = 1";
                        $totalVerified = getVal($sql2)['total_verified'];

                        // Total admitted
                        $sql3 = "SELECT COUNT(*) as total_admitted FROM students WHERE is_admitted = 1";
                        $totalAdmitted = getVal($sql3)['total_admitted'];

                        // Total fee collected
                        $sql4 = "SELECT SUM(amount_paid) as total_fees FROM payments WHERE pay_status = 'Success'";
                        $totalFees = getVal($sql4)['total_fees'] ?? 0;

                        // Total Courses
                        $sql5 = "SELECT COUNT(*) as total_courses FROM courses";
                        $totalCourses = getVal($sql5)['total_courses'];

                        $sql6 = "SELECT COUNT(*) as total_faculty FROM  users";
                        $totalFaculty = getVal($sql6)['total_faculty'];

                        $sql7 = "SELECT username FROM users WHERE role = ?";
                        $username = getVal($sql7,['admin']);
                        $adminName = $username['username'];

                        $sql8 = "SELECT question FROM chat_knowledge 
                                ORDER BY count DESC 
                                LIMIT 5";
                        $topQueries = getVal($sql8,[],true);
                    }
            ?>
                

                <?php  if($user['role'] == 'admin'): ?>
                    
                    <p>You are the ADMIN!</p>
                <?php else: ?>
                    <p>You are a Faculty Member.</p>
                <?php endif; ?>

                <h2>📊 Admission Stats</h2> <br>
                
                <div class="stats-grid">
                    <div class="stats-box">  
                        <ul>
                            <li>Students Registered:</li>
                            <li><strong><?= $totalStudents ?></strong></li>
                        </ul> 
                    </div>
                    <div class="stats-box">             
                        <ul>
                            <li>Verified Students:</li>
                            <li><strong><?= $totalVerified ?></strong></li>

                        </ul> 
                    </div>

                    <div class="stats-box">             
                        <ul>
                            <li>Admitted Students:</li>
                            <li> <strong><?= $totalAdmitted ?></strong></li>
                        </ul> 
                    </div>

                    <div class="stats-box">             
                        <ul>
                            <li>Total Courses:</li>
                            <li> <strong><?= $totalCourses ?></strong></li>
                        </ul> 
                    </div>

                    <div class="stats-box">             
                        <ul>
                            <li>Total Faculty:</li>
                            <li> <strong><?= $totalFaculty ?></strong></li>
                        </ul> 
                    </div>

                    <div class="stats-box">             
                        <ul>
                            <?php  if($user['role'] == 'admin'): ?>
                                <li>Fees Collected:</li>
                                <li> <strong>₹<?= number_format($totalFees) ?></strong></li>
                            <?php  else: ?>
                                <li>Admin Name:</li>
                                <li> <strong><?= $adminName ?></strong></li>
                            <?php endif; ?>
                        </ul> 
                    </div>
                </div>
                    <br>
                <!-- <a href="html/chatbot.html" class="chatbot-button">
                    AI Assistant
                </a> -->
                    
                <div class="chat_summary">
                    
                    <h2>💬 Chat Summary (Top 5 Queries)</h2>
                    <div class="stats-box">   
                        <ul>
                            <?php foreach ($topQueries as $query): ?>
                                <li><?= htmlspecialchars($query['question']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                </div>
                
                <button class="chatbot-pop">AI Assistant</button>
            <?php endif; ?>
        </div>
        </div>

        
    </div>

    <script src="chatbot/chatbot.js"></script>

<footer class="dashboard-footer">
    <div class="footer-content">
        <p>&copy; <?= date('Y') ?> My College. All Rights Reserved.</p>
        <p>Made by the College IT Team</p>
    </div>
</footer>


