<section class="container-fluid main-body">
    <div class="container">
        <h3 class="section-header">Register your restaurant on FineTable</h3>
        <div class="col-md-8 register-container">
            <form action="" class="form-horizontal sign-up-restaurant-form" method="post">
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">Restaurant name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="input restaurant name" name="name">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">Address</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="input address" name="address">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">City</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="city" name="city">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">State</label>
                    <div class="col-sm-3">
                        <select name="state" class="form-control states-dropdown" data-dropup-auto="false">
                            <option value="">Select state</option>
                        </select>
                    </div>
                    <label class="col-sm-2 control-label">Zip code</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="zipcode" name="zipcode">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">Phone</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="phone number" name="phone">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">Cuisine type</label>
                    <div class="col-sm-8">
                        <select name="cuisine" class="form-control cuisines-dropdown" data-dropup-auto="false">
                            <option value="">Select cuisine</option>
                        </select>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="3" name="description" placeholder="restaurant description"></textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">Table capacities</label>
                    <div class="col-sm-8 capacity-options">
                        <label>
                            <input class="capacity2 require-one" type="checkbox" name="capacityCheckbox2" value="false"> 2 people
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input class="capacity4 require-one" type="checkbox" name="capacityCheckbox4" value="false"> 4 people
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input class="capacity6 require-one" type="checkbox" name="capacityCheckbox6" value="false"> 6 people
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input class="capacity8 require-one" type="checkbox" name="capacityCheckbox8" value="false"> 8 people
                        </label>
                    </div>
                </div>
                <div class="form-group number-picker-2 hidden">
                    <label class="col-sm-3 control-label">2 people tables</label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control" name="numberCapacity2" min="1" value="1">
                    </div>
                    <div class="col-sm-7">
                        <p class="help-block">Pick the number of tables with 2 people capacity</p>
                    </div>
                </div>
                <div class="form-group number-picker-4 hidden">
                    <label class="col-sm-3 control-label">4 people tables</label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control" name="numberCapacity4" min="1" value="1">
                    </div>
                    <div class="col-sm-7">
                        <p class="help-block">Pick the number of tables with 4 people capacity</p>
                    </div>
                </div>
                <div class="form-group number-picker-6 hidden">
                    <label class="col-sm-3 control-label">6 people tables</label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control" name="numberCapacity6" min="1" value="1">
                    </div>
                    <div class="col-sm-7">
                        <p class="help-block">Pick the number of tables with 6 people capacity</p>
                    </div>
                </div>
                <div class="form-group number-picker-8 hidden">
                    <label class="col-sm-3 control-label">8 people tables</label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control" name="numberCapacity8" min="1" value="1">
                    </div>
                    <div class="col-sm-7">
                        <p class="help-block">Pick the number of tables with 8 people capacity</p>
                    </div>
                </div>
                <hr>
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">Image</label>
                    <div class="col-sm-8">
                        <input type="file" name="image">
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <input type="reset" class="btn btn-danger" value="reset">
                    <input type="submit" class="btn btn-success sign-up-restaurant-submit" value="submit">
                </div>
            </form>
        </div>
    </div>
</section>
