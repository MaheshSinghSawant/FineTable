<?php
    if (empty($_GET['restaurant_id'])) {
        header('Location: index.php');
    } else {
        require_once('../lib/Restaurant.class.php');
        require_once('../lib/User.class.php');
        require_once('../lib/Review.class.php');
        require_once('../lib/Image.class.php');
        require_once('../lib/helper.functions.php');
        $restaurant = new Restaurant();
        if ($restaurant->find($_GET['restaurant_id'])) {
            $restaurant_info = $restaurant->get_restaurant_info();
            $restaurant_raw_data = $restaurant->get_raw_data();
            $image = new Image();
            if ($image->find($restaurant_info['restaurant_id'])) {
                $image_path = $image->get_medium();
            }
            $review = new Review();
            if ($review->find_reviews_by_restaurant_id($_GET['restaurant_id'])) {
                $reviews_data = $review->get_reviews_raw_data();
            }
        } else {
            header('Location: index.php');
        }
    }
    require_once('../templates/header.php');
    if (isset($_SESSION['login_user_id'])) {
      $current_user_id = $_SESSION['login_user_id'];
    }
?>
<section class="container-fluid main-body">
    <div class="container">
        <h3 class="section-header">Take a look at the restaurant and reserve a table</h3>
        <div class="col-sm-10 restaurant-view">
            <?php
                if(isset($restaurant_raw_data)) {
                    if (isset($current_user_id)) {
                        render_restaurant_view($restaurant_raw_data, $current_user_id);
                    } else {
                        render_restaurant_view($restaurant_raw_data);
                    }
                }
            ?>
        </div>
        <div class="col-sm-10 restaurant-view">
            <h3 class="section-header">Restaurant Review</h3>
            <?php
                if (isset($reviews_data)) {
                    render_reviews($reviews_data);
                }
            ?>
        </div>
    </div>
    </div>
</section>
<?php
    require_once('../templates/footer.php');
?>
