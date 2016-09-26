<?php
require_once '../lib/User.class.php';
require_once '../lib/Restaurant.class.php';
require_once '../templates/header.php';
?>

    <section class="container-fluid main-body">
      <div class="container">
        <h3 class="section-header">Administration</h3>
        <div class="container">
          <div class="col-sm-6">
            <h3 class="section-sub-header">Users Management</h3>
            <div class="admin-ul-group">
                <h4> Manage Registered Users' Information</h4>
                <?php
                  $ret = User::query_all_users();
                  echo '<ul>';
                  for ($i = 0; $i < $ret->num_rows; ++$i) {
                      $row = $ret->fetch_row();
                      echo '<li>User('.$row[7].'): '.$row[1];
                      if ($row[7] == 'user') {
                          echo '<a href="admin/delete-user.php?id='.$row[0].'" class="btn btn-info btn-sm">Delete</a></li>';
                      }
                  }
                  echo '</ul>';
                ?>

                <!--<ul class="pagination">
                    <li class="pagination-previous"><a href="#" rel="previous">Previous</a></li>
                    <li class="pagination-next"><a href="#" rel="next">Next</a></li>
                </ul>-->
            </div>
          </div>

          <div class="col-sm-6">
            <h3 class="section-sub-header">Restaurants Management</h3>
              <div class="admin-ul-group">
                  <h4>Manage Restaurant Information</h4>
                  <?php
                    $ret = Restaurant::query_all_restaurants();
                    echo '<ul>';
                    for ($i = 0; $i < $ret->num_rows; ++$i) {
                        $row = $ret->fetch_row();
                        echo '<li class="admin-li-group">'.$row[1].'(';
                        if ($row[13]) {
                            echo 'Approved';
                        } else {
                            echo 'Denied';
                        }
                        echo ')
                                <a href="admin/approve-restaurant.php?id='.$row[0].'"
                                  class="btn btn-info btn-sm">Approve</a>
                                <a href="admin/deny-restaurant.php?id='.$row[0].'"
                                  class="btn btn2 btn-info btn-sm">Deny</a>
                              </li>';
                    }
                    echo '</ul>';
                  ?>
                  <!--<ul class="pagination">
                      <li class="pagination-previous"><a href="#" rel="previous">Previous</a></li>
                      <li class="pagination-next"><a href="#" rel="next">Next</a></li>
                  </ul>-->
              </div>
        </div>
      </div>
    </section>
<?php require_once('../templates/footer.php') ?>
