<?php

require_once('../controller/impl/Courcontrollerimpl.php');
require_once('../controller/impl/CategorieControllerimpl.php');
require_once('../controller/impl/TagControllerimpl.php');
$contrl = new Courcontrollerimpl();
$result = $contrl->fetchCours();
$Categories = new CategorieControllerimpl();
$allCategories = $Categories->getAllCategories();
$Tags= new TagControllerimpl();
$allTags = $Tags->getAllTags();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./assets/images/favicon.svg">
    <script src="./assets/scripts/main.js" defer></script>
    <style>
        .text-gradient {
            background: linear-gradient(to right, rgb(70, 18, 242), rgb(51, 16, 250));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body>

    <!-- main container -->
    <div class="flex flex-col">
        <div class="hidden md:block w-full bg-blue-600 text-white">
            <div class="container mx-auto px-4 py-2">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center space-x-6">
                        <span class="flex items-center">
                            <i class="ri-phone-line mr-2"></i> +212 772508881
                        </span>
                        <span class="flex items-center">
                            <i class="ri-mail-line mr-2"></i> contact@youdemy.com
                        </span>
                    </div>
                    <span class="flex items-center">
                        <i class="ri-map-pin-line mr-2"></i> Massira N641 Safi, Morocco
                    </span>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="border-b bg-white">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between py-4">
                    <a href="./index.php">
                        <img src="./assets/images/Youdemy_Logo.svg" alt="Youdemy Platform">
                    </a>
                    <nav class="hidden md:flex items-center space-x-6">
                    <a href="cours.php" class="p-2 px-4 bg-blue-600 text-white rounded-full hover:bg-white hover:text-blue-600 hover:border hover:border-blue-600 transition-colors">all courses</a>

                    </nav>

                    <?php
                    
                    if (!isset($_SESSION["user"])) {
                    ?>
                        
                    <?php
                    } else {
                    ?>
                        <div class="flex items-center space-x-4">
                            <button
                                class="p-2 px-4 bg-blue-600 text-white rounded-full hover:bg-white hover:text-blue-600 hover:border hover:border-blue-600 transition-colors">
                                <a href="../user/login.php">Log Out</a>
                            </button>

                            <button
                                class="p-2 px-4 border border-blue-600 text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-colors">
                                <a href="../app/user/register.php"><?php echo $_SESSION['user']['nom'] ?></a>
                            </button>

                            <button class="p-2 hover:text-bg-blue-600 transition-colors">
                                <i class="ri-menu-4-fill text-2xl"></i>
                            </button>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </header>

        <!-- Form Section -->
        <section>
        <div class="py-10 md:px-12 px-6">
            <h2 class="text-4xl font-bold text-gray-800 mb-6 text-center md:mb-11">
                Course Enrollment Form
            </h2>

            <form action="../controller/base/baseController.php"  method="POST"  enctype="multipart/form-data"  class="max-w-lg mx-auto space-y-6">
                <div>
                    <label for="course_title" class="block text-sm font-medium text-gray-700">Course Title</label>
                    <input type="text" id="course_title" name="titre" required class="mt-2 p-2 w-full border border-gray-300 rounded-lg" placeholder="Enter the course title">
                </div>

                <div>
                    <label for="student_name" class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" id="student_name" name="description" required class="mt-2 p-2 w-full border border-gray-300 rounded-lg" placeholder="Enter your name">
                </div>

                <div>
                      <label for="video_upload" class="block text-sm font-medium text-gray-700">Course Video</label>
                      <input type="file" 
                             id="video_upload" 
                             name="video" 
                             accept="video/*"
                                  required 
                          class="mt-2 p-2 w-full border border-gray-300 rounded-lg">
                       <p class="mt-1 text-sm text-gray-500">Upload your course video (MP4, WebM formats recommended)</p>
                </div>

                <div>
                    <label for="contenu" class="block text-sm font-medium text-gray-700">Content</label>
                    <input type="text" id="contenu" name="contenu" required class="mt-2 p-2 w-full border border-gray-300 rounded-lg" placeholder="Enter your phone number">
                </div>

                <!-- Select for Categories -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category" name="idCategorie" required class="mt-2 p-2 w-full border border-gray-300 rounded-lg">
                        <option value="">Select a category</option>
                        <?php
                        foreach ($allCategories as $category) {
                            echo '<option value="' . $category['id'] . '">' . $category['titre'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- New Select for Tags -->
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                    <div class="mt-2 p-2 border border-gray-300 rounded-lg max-h-40 overflow-y-auto">
                        <?php
                        foreach ($allTags as $tag) {
                            echo '<div class="flex items-center mb-2">';
                            echo '<input type="checkbox" id="tag_' . $tag['id'] . '" name="tags[]" value="' . $tag['id'] . '" class="mr-2">';
                            echo '<label for="tag_' . $tag['id'] . '" class="text-sm text-gray-700">' . $tag['nom'] . '</label>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit" name="addCour" class="py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Add Course</button>
                </div>
            </form>
        </div>
    </section>

            </div>
        </section>
    </div>

    <!-- Footer Section -->
    <footer class="bg-blue-10 py-16">
        <div class="px-10">
            <div class="mb-16">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                    <div class="bg-blue-50 p-6 rounded-lg text-center hover:bg-transparent hover:border hover:border-blue-600 hover:scale-95 transition-transform duration-300">
                        <i class="ri-team-line text-2xl text-blue-600 mb-2"></i>
                        <p class="font-medium">Community</p>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-lg text-center hover:bg-transparent hover:border hover:border-blue-600 hover:scale-95 transition-transform duration-300">
                        <i class="ri-link text-2xl text-blue-600 mb-2"></i>
                        <p class="font-medium">Referrals</p>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-lg text-center hover:bg-transparent hover:border hover:border-blue-600 hover:scale-95 transition-transform duration-300">
                        <i class="ri-book-2-line text-2xl text-blue-600 mb-2"></i>
                        <p class="font-medium">Assignments</p>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-lg text-center hover:bg-transparent hover:border hover:border-blue-600 hover:scale-95 transition-transform duration-300">
                        <i class="ri-medal-line text-2xl text-blue-600 mb-2"></i>
                        <p class="font-medium">Certificates</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="./assets/images/Youdemy_Logo.svg" height="200" width="200">
                    </div>
                    <p class="text-gray-600 mb-6">Eros in cursus turpis massa tincidunt Faucibus scelerisque eleifend vulputate sapien nec sagittis.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Pages</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Home</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Courses</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">My Account</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">About</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Pricing</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Features</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Sign In / Register</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Our Newsletter</h3>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Enter Your Email"
                            class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-blue-600">
                        <button
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-600 transition-colors">Submit</button>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">
                        By clicking "Subscribe", you agree to our
                        <a href="#" class="text-gray-900 hover:underline">Privacy Policy</a>.
                    </p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center pt-12 mt-12 border-t border-gray-200">
                <p class="text-gray-600">&copy; 2024. All Rights Reserved.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-600 hover:text-gray-900">Terms & Conditions</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">Privacy policy</a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
