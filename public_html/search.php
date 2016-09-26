<?php
require_once('../lib/Search.class.php');
require_once('../lib/helper.functions.php');
$init_perpage = 1;
$init_start = 0;
if (!isset($_GET['search_key'])) {
    header('Location: index.php');
} else {

    $search_key = $_GET['search_key'];
    $perpage = 5;
    $current_page = $_GET['pagenumber'];
    $start = ($current_page - 1) * $perpage;
    $search_key_arry = explode(' ', $search_key);
    $search_key_in_link = join('+', $search_key_arry);

    // $url = ltrim($_SERVER['REQUEST_URI'], '/');
    // echo $url . '<br>';
    try {
        $search_tool = new Search($search_key);
        $results = $search_tool->blur_search($search_key);
        require_once('../templates/header.php');
        if ($results->num_rows <= 0) {
          // header('Location: index.php');
        } else {

          $number_results = $results->num_rows;
          $number_pages= ceil($number_results / $perpage);

          if (intval($current_page) > $number_pages) {
              header('Location: search.php?search_key=' . $search_key_in_link . '&perpage=' . $perpage . '&pagenumber=1');
          } else {

              $page_results = $search_tool->blur_search($search_key, $start, $perpage);

          }
        }
    } catch (Exception $e) {
        header('Location: index.php');
    }
}
?>


    <section class="container-fluid main-body">
        <div class="container">
            <h3 class="section-header">Search result for "
                <span class="text-primary">
                    <strong><?php echo $search_key ?></strong>
                </span>",&nbsp;
                <span class="text-muted text-transform">
                  <?php if (isset($number_results)) echo $number_results . " results"; else echo '0' . " results"; ?></span>
            </h3>
            <p class="text-muted text-center"></p>
            <div class="col-sm-10 search-result">
                <?php
                // render search results
                if (isset($page_results) && $page_results->num_rows > 0) {
                    while ($row = $page_results->fetch_row()) {
                        render_search_results($row);
                    }
                } else {
                  echo "No result found for this keyword.";
                }
                ?>
            </div>
        </div>
        <div class="container text-center">
            <?php
                if (isset($page_results) && $page_results->num_rows > 0) {
                  echo '<ul class="pagination">';
                  if ($current_page == 1) {
                      echo '<li class="disabled"><span aria-hidden="true">&laquo;</span></li>';
                  } else {
                      echo '<li class=""><a href="search.php?search_key=' . $search_key_in_link . '&pagenumber=' . ($current_page - 1) . '"><span aria-hidden="true">&laquo;</span></a></li>';
                  }
                  for ($i = 1; $i <= $number_pages; $i++) {
                      if ($i == $current_page) {
                          echo '<li class="active"><a href="#">' . $i . '</a></li>';
                      } else {
                          echo '<li><a href="search.php?search_key=' . $search_key_in_link . '&pagenumber=' . $i . '">' . $i . '</a></li>';
                      }
                  }
                  if ($current_page == $number_pages) {
                      echo '<li class="disabled"><span aria-hidden="true">&raquo;</span></li>';
                  } else {
                      echo '<li class=""><a href="search.php?search_key=' . $search_key_in_link . '&pagenumber=' . ($current_page + 1) . '"><span aria-hidden="true">&raquo;</span></a></li>';
                  }
                }
            ?>
        </div>
    </section>

<?php
    require_once('../templates/footer.php');
?>
