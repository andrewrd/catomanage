



<!-- Form Container starts here -->
<div class="container">
    <!-- Main content starts here -->

    <form action="addcategory.php" method="POST" class="form-horizontal">

        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 header">
                <h3 class="title">Add Category</h3>

            </div>
        </div>
        <section>
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-13 col-lg-12">
                    <h3 class="subHeader">Category Details</h3>
                </div>
                <!--Product Name Info-->
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Category Name</p>
                </div>
                <div class="col-xs-12 col-sm-9  col-md-3 col-lg-4">
                    <input name="cat_name" type="text" class="text-input-underline" placeholder="Category Name" id="form-input-catName" oninput="checkString(this.id, 40)" required>
                    <p id="form-error-catName"></p>
                </div>
                <!--Product name Info ends-->
            </div>
            <!--Short Description Starts-->
            <div class="form-group row">
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText"> Description
                        </p>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                    <textarea name="cat_desc" class="form-control" id="form-input-desc" rows="2" placeholder="Description Text" required oninput="checkString(this.id, 128)"></textarea>
                    <p id="form-error-desc"></p>
                </div>
            </div>
            <!--Short Description Ends-->
            <div class="form-group row">
                <!--Special for shopper group label-->
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Parent Category</p>
                </div>
                <!--Shopper group selection-->
                <div class="col-xs-12 col-sm-9 col-md-9  col-lg-10">
                    <select multiple class="form-control" id="form-input-parentCategory">
                        
                    </select>
                </div>
            </div>
            <!--Long Description Ends-->
        </section>
        <section>
            <!--Packaging Information Starts-->

           
            <div class="form-group row">
                
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Image URL</p>
                </div>
                <div class="col-xs-12 col-sm-3  col-md-3 col-lg-4">
                    <input name="cat_img_url" type="text" class="text-input-underline" placeholder="Image URL" id="form-url-input">
                </div>
            </div>
            <!--Packaging Information Ends-->
            <!--Display Product Starts-->
            <div class="form-group row">
                <div class="hidden-xs col-sm-3 col-md-2 col-lg-2">
                    <p class="labelText">Display Category</p>
                </div>
                <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3">
                    <div class="radio">
                        <label>
                            <input name="cat_disp_cmd" value="yes" type="radio" name="optradio" id="form-input-dispProd-yes" required>Yes</label>
                    </div>
                    <div class="radio">
                        <label>
                            <input name="cat_disp_cmd" value="no" type="radio" name="optradio" id="form-input-dispProd-no">No</label>
                    </div>
                </div>
            </div>
            <!--Product Display Ends-->
            <!--Attribute Management Starts here-->

            <!--Attribute Value and Price Inputs-->
            
            <!--Price Management Section Starts here-->
            
           
            <!--Form Submit button-->
            <div class="form-group row">
                <div class="form-group">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                        <input type="submit"  id="btn-submitAddProductForm" class="btn btn-success" />
                    </div>
                </div>
            </div>
        </section>
    </form>
<!--Form Container Ends here-->
<script type="text/javascript" src="../js/validateForm.js"></script>
</div>
<!--Form Container ends here-->
