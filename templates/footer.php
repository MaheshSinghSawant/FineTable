<footer class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <aside class="col-md-3">
                    <h3>About us</h3>
                    <ul class="list-unstyled footer-links">
                        <li>
                            <a href="about.php">
                                <span class="glyphicon glyphicon-bookmark"></span>&nbsp;Our company</li>
                        </a>
                    </ul>
                </aside>
                <!--aside class="col-md-6">
                    <h3>Links</h3>
                    <ul class="list-inline footer-links">
                        <li>
                            <a href="#">
                                <span class="glyphicon glyphicon-envelope"></span>&nbsp;Contact us</a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="glyphicon glyphicon-list-alt"></span>&nbsp;Terms</a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="glyphicon glyphicon-question-sign"></span>&nbsp;Help</a>
                        </li>
                    </ul>
                </aside-->
            </div>
            <div class="col-md-2">
                <div class="footer-logo">FineTable</div>
            </div>
        </div>
        <!-- <div class="row">
          Copyright &copy; FineTable 2015 Copyright Holder All Rights Reserved.
        </div> -->
    </div>
</footer>
</div>
<script src="js/jquery-2.1.4.min.js" charset="utf-8"></script>
<script src="js/jquery.cookie.js" charset="utf-8"></script>
<script src="js/jquery.validate.min.js" charset="utf-8"></script>
<script src="js/additional-methods.min.js" charset="utf-8"></script>
<script src="js/bootstrap.min.js" charset="utf-8"></script>
<script src="js/bootstrap-select.min.js" charset="utf-8"></script>
<script src="js/global.js" charset="utf-8"></script>
<script src="js/finetable.js" charset="utf-8"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-71156536-1', 'auto');
  ga('send', 'pageview');

</script>
<?php
    $page = $_SERVER['PHP_SELF'];
    $page = basename($page).PHP_EOL;
    $page = trim($page);
    if (strcmp($page, 'search.php') == 0) {
        echo '<script src="js/reservation.js" charset="utf-8"></script>';
    }
    else if (strcmp($page, 'restaurant.php') == 0) {
        echo '<script src="js/restaurant.js" charset="utf-8"></script>';
        echo '<script src="js/reservation.js" charset="utf-8"></script>';
    }
    else if (strcmp($page, 'sign-up-restaurant.php') == 0)
        echo '<script src="js/sign-up-restaurant.js" charset="utf-8"></script>';
?>
</body>

</html>
