<?php
require_once __DIR__ . '/../config/database.php'; // Include database connection
require_once __DIR__ . '/../controllers/MenuCategoryController.php';
require_once __DIR__ . '/../controllers/UserController.php';

$pdo = Database::getConnection();

$categoryController = new MenuCategoryController($pdo);
$categories = $categoryController->index();

$usercontroller = new UserController($pdo);
$adminphonenumber = $usercontroller->getPhoneNumber();
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cater | Craft</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="../assets/organi/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/style.css" type="text/css">
</head>
<body>
<?php include('includes/navbar.php');?>
    <!-- Hero Section Begin -->
    <section class="hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
            <div class="hero__categories">
            <div class="hero__categories__all">
        <i class="fa fa-bars"></i>
        <span>Categories</span>
    </div>
    <ul>
    <?php foreach ($categories as $category): ?>
        <li>
            <a href="categories.php?category_id=<?= $category['id'] ?>">
                <?= htmlspecialchars($category['category']) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

    </div>
    <br>
    <button class="site-btn" data-toggle="modal" data-target="#dateAvailabilityModal">
                            CHECK DATE AVAILABILITY
                        </button>
</div>

            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form action="#">
                            <input type="text" placeholder="What do you need?">
                            <button type="submit" class="site-btn">SEARCH</button>
                        </form>

                    </div>
                    <div class="hero__search__phone">
                        <div class="hero__search__phone__icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="hero__search__phone__text">
                            <h5><?= htmlspecialchars($adminphonenumber)?></h5>
                            <span>support 24/7 time</span>
                        </div>
                    </div>
                </div>
                <div class="hero__item set-bg" data-setbg="../assets/organi/img/hero/banner1.jpg">
                    <div class="hero__text">
                        <span>PREMIUM CATERING</span>
                        <h2>Delicious <br>Menus for Every Occasion</h2>
                        <p>Quality Catering Service for Any Event</p>
                        <a href="#" class="primary-btn">EXPLORE PACKAGES</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

   <!-- Featured Section Begin 
   <section class="featured spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>List of Packages</h2>
                    </div>
                    <div class="featured__controls">
                        <ul>
                            <li class="active" data-filter="*">All</li>
                            <li data-filter=".oranges">Regular</li>
                            <li data-filter=".fresh-meat">Premium</li>
                            <li data-filter=".vegetables">Special</li>
                            <li data-filter=".fastfood">VIP</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row featured__filter">
                <div class="col-lg-3 col-md-4 col-sm-6 mix oranges fresh-meat">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="../assets/organi/packages/1.jpg">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <h6><a href="#">Package1</a></h6>
                            <h5>₱30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mix vegetables fastfood">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="../assets/organi/packages/2.jpg">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                        <h6><a href="#">Package1</a></h6>
                        <h5>₱30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mix vegetables fresh-meat">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="../assets/organi/packages/3.jpg">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                        <h6><a href="#">Package1</a></h6>
                        <h5>₱30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mix fastfood oranges">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="../assets/organi/packages/3.jpg">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                        <h6><a href="#">Package1</a></h6>
                        <h5>₱30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mix fresh-meat vegetables">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="../assets/organi/packages/3.jpg">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                        <h6><a href="#">Package1</a></h6>
                        <h5>₱30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mix oranges fastfood">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="../assets/organi/packages/3.jpg">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                        <h6><a href="#">Package1</a></h6>
                        <h5>₱30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mix fresh-meat vegetables">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="../assets/organi/packages/3.jpg">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                        <h6><a href="#">Package1</a></h6>
                        <h5>₱30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mix fastfood vegetables">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="../assets/organi/packages/3.jpg">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                        <h6><a href="#">Package1</a></h6>
                            <h5>₱30.00</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>-->
    <!-- Featured Section End -->

     <!-- MODAL FOR CHECKING DATE AVAILABILITY -->
<div class="modal fade" id="dateAvailabilityModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Check Date Availability</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <p>Select a date from the calendar:</p> -->
                <div id="calendar"></div> 
                <br>
                <!-- <button class="btn btn-success" onclick="checkAvailability()">Check Availability</button> -->
                <p id="availability-message" class="mt-3"></p>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>
   
    <!-- Js Plugins -->
    <script src="../assets/organi/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/organi/js/bootstrap.min.js"></script>
    <script src="../assets/organi/js/jquery.nice-select.min.js"></script>
    <script src="../assets/organi/js/jquery-ui.min.js"></script>
    <script src="../assets/organi/js/jquery.slicknav.js"></script>
    <script src="../assets/organi/js/mixitup.min.js"></script>
    <script src="../assets/organi/js/owl.carousel.min.js"></script>
    <script src="../assets/organi/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');

    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        dateClick: function(info) {
            let selectedDate = info.dateStr;
            checkAvailability(selectedDate);
        }
    });

    $('#dateAvailabilityModal').on('shown.bs.modal', function () {
        calendar.render();
    });
});

function checkAvailability(selectedDate) {
    if (!selectedDate) {
        document.getElementById("availability-message").innerHTML = "<span style='color: red;'>Please select a date!</span>";
        return;
    }

    $.ajax({
        url: "/cater-craft/routes.php?route=check_date",
        type: "POST",
        data: { date: selectedDate },
        dataType: "json",
        success: function(response) {
            if (response.available) {
                $("#availability-message").html("<span style='color: green;'>Date is available!</span>");
            } else {
                $("#availability-message").html("<span style='color: red;'>Date is fully booked!</span>");
            }
        },
        error: function() {
            $("#availability-message").html("<span style='color: red;'>Error checking availability.</span>");
        }
    });
}
</script>
</body>
</html>