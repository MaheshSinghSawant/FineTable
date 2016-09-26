<section class="container-fluid main-body">
    <div class="container">
        <h3 class="section-header">Newest Restaurants on FineTable for you</h3>
        <div class="row">
            <?php
            require_once '../lib/helper.functions.php';
            newest_restaurants(4);
            ?>
        </div>
    </div>
</section>
