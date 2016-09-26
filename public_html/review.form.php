<div class="row">
    <div class="review-block" style="width: 90%; margin: 0 auto;">
    <form class="review-form" action="review.form.php" method="post">
        <div class="row">
            <p>How many stars do you want to gave to the restaurant?</p>
            <label for="1star"><input type="radio" id="1star" name="starts" value="1">&nbsp;1 star</label>&nbsp;&nbsp;
            <label for="2star"><input type="radio" id="2star" name="starts" value="2">&nbsp;2 stars</label>&nbsp;&nbsp;
            <label for="3star"><input type="radio" id="3star" name="starts" value="3">&nbsp;3 stars</label>&nbsp;&nbsp;
            <label for="4star"><input type="radio" id="4star" name="starts" value="4">&nbsp;4 stars</label>&nbsp;&nbsp;
            <label for="5star"><input type="radio" id="5star" name="starts" value="5">&nbsp;5 stars</label>&nbsp;&nbsp;
            <p>Comment:</p>
            <textarea class="form-control" name="content" rows="4" cols="40"></textarea>
            <br>
            <input type="submit" class="btn btn-primary" name="review-submit" value="Submit">
        </div>
    </form>
    </div>
</div>
