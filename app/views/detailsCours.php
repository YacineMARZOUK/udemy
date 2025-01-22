<?php
require_once('C:\xampp\htdocs\udemy\app\controller\impl\Courcontrollerimpl.php');
require_once('C:\xampp\htdocs\udemy\app\controller\base\baseController.php'); 

$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
var_dump($courseId);

$contrl = new Courcontrollerimpl();
$course = $contrl->getCourseById($courseId);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details - Youdemy Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Keep your existing header here -->

    <!-- Alert Messages -->
    

    <!-- Course Details Section -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Course Header -->
            <div class="relative">
                <img src="/assets/images/cover4.png" alt="Course Cover" class="w-full h-64 object-cover">
                <div class="absolute top-4 right-4">
                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                        <?= htmlspecialchars($course->getTitre()) ?>
                    </span>
                </div>
            </div>

            <!-- Course Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($course->getTitre()) ?></h1>
                        
                        <!-- Course Meta -->
                        <div class="flex items-center text-sm text-gray-500 mb-6 space-x-4">
                            <span class="flex items-center">
                                <i class="ri-calendar-line mr-1"></i>
                                <span>Last Updated: <?= date('M d, Y') ?></span>
                            </span>
                            <span class="flex items-center">
                                <i class="ri-time-line mr-1"></i>
                                <span>Duration: 8 hours</span>
                            </span>
                        </div>

                        <!-- Course Description -->
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Description</h2>
                            <p class="text-gray-600"><?= nl2br(htmlspecialchars($course->getdescription())) ?></p>
                        </div>

                        <!-- Course Content -->
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Course Content</h2>
                            <div class="border rounded-lg">
                                <?= nl2br(htmlspecialchars($course->getcontenu())) ?>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <div class="text-center mb-6">
                                <span class="text-3xl font-bold text-gray-900">Free</span>
                            </div>

                            <!-- Enrollment Form -->
                            <form action="../controller/base/baseController.php" method="POST">
                                <input type="hidden" name="course_id" value="<?= $course->getId() ?>">
                                
                                <?php if (!isset($_SESSION['y'])): ?>
                                    <a href="../../user/login.php" 
                                       class="block w-full py-3 px-4 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-colors">
                                        Login to Enroll
                                    </a>
                                <?php else: ?>
                                    <button type="submit" 
                                            name="enrollintoit" 
                                            class="w-full py-3 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        Enroll Now
                                    </button>
                                <?php endif; ?>
                            </form>

                            <!-- Course Features -->
                            <div class="mt-6 space-y-4">
                                <div class="flex items-center text-gray-600">
                                    <i class="ri-video-line mr-2"></i>
                                    <span>Full lifetime access</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="ri-smartphone-line mr-2"></i>
                                    <span>Access on mobile and desktop</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="ri-award-line mr-2"></i>
                                    <span>Certificate of completion</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keep your existing footer here -->
</body>
</html>