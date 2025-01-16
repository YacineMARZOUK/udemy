<?php
session_start();
require_once('../controller/impl/Courcontrollerimpl.php');

// Get course ID from URL and validate it
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : null;
echo $courseId;
if (!$courseId) {
    header('Location: cours.php?error=invalid_id');
    exit();
}

$controller = new Courcontrollerimpl();

try {
    $course = $controller->getCourseById($courseId);
    var_dump($course);
    if (!$course) {
        // header('Location: cours.php?error=course_not_found');
        exit();
    }
} catch (Exception $e) {
    error_log("Error fetching course: " . $e->getMessage());
    header('Location: cours.php?error=system_error');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-center mb-6">Update Course</h2>
            
            <form action="../controller/base/baseController.php" method="POST" class="space-y-4">
                <input type="hidden" name="courseId" value="<?php echo htmlspecialchars($courseId); ?>">
                
                <div class="mb-4">
                    <label for="titre" class="block text-gray-700 font-medium mb-2">Title</label>
                    <input type="text"
                           id="titre"
                           name="titre"
                           value="<?php echo htmlspecialchars($course->getTitre()); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                           required>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea id="description"
                            name="description"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required><?php echo htmlspecialchars($course->getDescription()); ?></textarea>
                </div>

                <button type="submit"
                        name="updateCourseSubmit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                    Update Course
                </button>
                
                <a href="cours.php" 
                   class="block w-full text-center mt-4 text-blue-600 hover:text-blue-700">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</body>
</html>