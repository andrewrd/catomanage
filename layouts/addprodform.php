<!-- Form Container starts here -->
<div class="container">
    <!-- Main content starts here -->

    <form action="addprod.php" method="POST" class="form-horizontal">

        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 header">
                <h3 class="title">Add Product</h3>

            </div>
        </div>
        <section>
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-13 col-lg-12">
                    <h3 class="subHeader">Product Details</h3>
                </div>
                <!--Product Name Info-->
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Product Name</p>
                </div>
                <div class="col-xs-12 col-sm-9  col-md-3 col-lg-4">
                    <input name="prod_name" type="text" class="text-input-underline" placeholder="Product Name" id="form-input-productName" oninput="checkString(this.id, 40)">
                    <p id="form-error-productName">
                        <?php 

                        if(isset($_POST['prod_name_error']))
                        {
                            echo $_POST['prod_name_error'];
                        }
                        ?>
                    </p>
                </div>
                <!--Product name Info ends-->
            </div>
            <!--Short Description Starts-->
            <div class="form-group row">
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Short Description
                        <br>(Max 128 Characters)</p>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                    <textarea name="prod_desc" class="form-control" id="form-input-shortDesc" rows="2" placeholder="Description Text" oninput="checkString(this.id, 128)"></textarea>
                    <p id="form-error-shortDesc">
                        <?php 
                        if(isset($_POST['prod_desc_error']))
                        {
                            echo $_POST['prod_desc_error'];
                        }
                        ?>
                    
                    </p>
                </div>
            </div>
            <!--Short Description Ends-->
            <!--Long Description Starts-->
            <div class="form-group row">
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Long Description
                        <br>(Max 256 Characters)</p>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                    <textarea name="prod_long_desc" class="form-control exampleTextarea" id="form-input-longDesc" rows="4" placeholder="Description Text" oninput="checkString(this.id, 256)" ></textarea>
                    <p id="form-error-longDesc">
                        <?php 
                        if(isset($_POST['prod_long_desc_error']))
                        {
                            echo $_POST['prod_long_desc_error'];
                        }
                        ?>
                    </p>
                </div>
            </div>
            <!--Long Description Ends-->
            <!--Add to Category Starts-->
            <div class="form-group row">
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Add to Category</p>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-9  col-lg-10">
                  <?php get_all_categories($dbo); ?>
                </div>
                <p id="form-error-cat">
                    <?php 
                    if(isset($_POST['cat_error']))
                    {
                        echo $_POST['cat_error'];
                    }
                    ?>
                
                </p>
            </div>
            <!--Add to Category Ends-->
        </section>
        <section>
            <!--Packaging Information Starts-->
            <div class="form-group">
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Packaging Dimensions</p>
                </div>
                <!--Length Label-->
                <div class="hidden-xs col-sm-1 col-md-1 col-lg-1">
                    <p class="labelText">Length:</p>
                </div>
                <!--Length Input-->
                <div class="col-xs-12 col-sm-1  col-md-1 col-lg-2">
                    <input name="prod_l" type="number" class="text-input-underline" placeholder="Length" id="form-input-length"   oninput="checkNumber(this.id)">
                    <p id="form-error-length">
                        <?php 
                        if(isset($_POST['prod_l_error'])){
                            echo $_POST['prod_l_error'];
                        }
                        ?>
                    </p>
                </div>
                <!--Width Label-->
                <div class="hidden-xs col-sm-1 col-md-1 col-lg-1">
                    <p class="labelText">Width</p>
                    
                </div>
                <!--Width input-->
                <div class="col-xs-12 col-sm-2  col-md-2 col-lg-2">
                    <input name="prod_w" type="number" class="text-input-underline" placeholder="Width" id="form-input-width"   oninput="checkNumber(this.id)">
                    <p id="form-error-width">
                        <?php 

                        if(isset($_POST['prod_w_error'])){
                            echo $_POST['prod_w_error'];
                        }

                        ?>
                    </p>
                </div>
                <div class="hidden-xs col-sm-1 col-md-1 col-lg-1">
                    <p class="labelText">Height:</p>
                </div>
                <div class="col-xs-12 col-sm-2  col-md-2 col-lg-2">
                    <input name="prod_h" type="number" class="text-input-underline" placeholder="Height" id="form-input-height"   oninput="checkNumber(this.id)">
                    <p id="form-error-height">
                        <?php 

                        if(isset($_POST['prod_h_error'])){
                            echo $_POST['prod_h_error'];
                        }

                        ?>
                    
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <!--Weight Starts Here-->
                <div class="hidden-xs col-sm-3 col-md-2 col-lg-2">
                    <p class="labelText">Weight</p>
                </div>
                <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3">
                    <input name="prod_weight" type="number" class="text-input-underline" placeholder="Weight" id="form-input-weight"   oninput="checkNumber(this.id)">
                    <p id="form-error-weight">
                        <?php 

                        if(isset($_POST['prod_weight_error'])){
                            echo $_POST['prod_weight_error'];
                        }

                        ?>
                    
                    
                    </p>
                </div>
                <!--Weight Ends here-->
                <!--Product SKU Starts here-->
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Product SKU</p>
                </div>
                <div class="col-xs-12 col-sm-3  col-md-3 col-lg-4">
                    <input name="prod_sku" type="text" class="text-input-underline" placeholder="Product SKU" id="form-input-sku"   oninput="checkSKU(this.id)">
                    <p id="form-error-sku">
                    <?php
                        
                        
                        if(isset($_POST['prod_sku_error'])){
                            echo $_POST['prod_sku_error'];
                        }
                    ?>
                    
                    
                    </p>
                </div>
                <!--Product SKU Ends here-->
            </div>
            <div class="form-group row">
                
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Image URL</p>
                </div>
                <div class="col-xs-12 col-sm-3  col-md-3 col-lg-4">
                    <input name="prod_img_url" type="text" class="text-input-underline" placeholder="Image URL" id="form-url-input">
                </div>
            </div>
            <!--Packaging Information Ends-->
            <!--Display Product Starts-->
            <div class="form-group row">
                <div class="hidden-xs col-sm-3 col-md-2 col-lg-2">
                    <p class="labelText">Display Product</p>
                </div>
                <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3">
                    <div class="radio">
                        <label>
                            <input name="prod_disp_cmd" value="yes" type="radio" name="optradio" id="form-input-dispProd-yes"  >Yes</label>
                    </div>
                    <div class="radio">
                        <label>
                            <input name="prod_disp_cmd" value="no" type="radio" name="optradio" id="form-input-dispProd-no">No</label>
                    </div>
                </div>
            </div>
            <!--Product Display Ends-->
            <!--Attribute Management Starts here-->
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h3 class="subHeader">Attribute Management</h3>
                </div>
            </div>
            <!--Current Set Attributes Starts here-->
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h4 class="subHeader">Current Set Attributes</h4>
                </div>
            </div>
            <!--Current Set attributes ends here-->
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-13 col-lg-12">
                    <ul id="attribute-output">

                    </ul>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-3 col-sm-2 col-md-2 col-lg-12 ">
                    <button type="button" id="btn-addNewAttr" class="btn btn-default">Add New Attribute +</button>
                </div>
            </div>
            <!--Attribute Input Starts here-->
            <!--Attribute Name-->
            <div class="form-group row">
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Attribute Name</p>
                </div>
                <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3">
                    <input type="text" class="text-input-underline" placeholder="Attribute Name" id="form-input-attributeName" oninput="checkString(this.id, 45)">
                    <p id="form-error-attributeName"></p>
                </div>
            </div>
            <!--Attribute Value and Price Inputs-->
            <div class="form-group">
                <!--Attribute Value Label and Input-->
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Attribute Value</p>
                </div>
                <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3">
                    <input type="text" class="text-input-underline" placeholder="Attribute Value" id="form-input-attributeValue" oninput="checkString(this.id, 45)">
                    <p id="form-error-attributeValue"></p>
                </div>
                <!--Attribute Price Label and Input-->
                <div class="hidden-xs col-sm-3 col-md-2 col-lg-2">
                    <p class="labelText">Attribute Price</p>
                </div>
                <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3">
                    <input type="text" class="text-input-underline" placeholder="Attribute Price" id="form-input-attributePrice" oninput="checkNumber(this.id)">
                    <p id="form-error-attributePrice"></p>
                </div>
                <input name="json" id="json-input">
                
                <!--Submit button-->
            </div>
            <div class="form-group">
                <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3"
                <p id="prod_price_error">
                    <?php

                    if(isset($_POST['prod_price_error'])){
                        echo $_POST['prod_price_error'];
                    }   
                    ?>

                </p>
                </div>
            </div>
            <!--Price Management Section Starts here-->
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h3 class="subHeader">Price Management</h3>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h4 class="subHeader">Current Set Prices</h4>
                </div>
            </div>
            <!--Current Set attributes ends here-->
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-13 col-lg-12">
                    <ul id="price-output">

                    </ul>
                </div>
            </div>
            <input type="hidden" name="prod_prices" class="text-input-underline" id="prod_prices">
            <!--Product Base Price Label and Input-->
            <div class="form-group">
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Product Price</p>
                </div>
                <div class="col-xs-12 col-sm-9  col-md-3 col-lg-4">
                    <input type="text" class="text-input-underline" placeholder="Product Price" id="form-input-productPrice"   oninput="checkNumber(this.id)">
                    <p id="form-error-productPrice"></p>
                </div>
            </div>
            <!--Shopper Group Special -->
            <div class="form-group row">
                <!--Special for shopper group label-->
                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Shopper Group</p>
                </div>
                <!--Shopper group selection-->
                <div class="col-xs-12 col-sm-3 col-md-3  col-lg-4">
                    <select multiple class="form-control" id="form-input-specialShopGroup">
                        <?php get_all_shopper_groups($dbo); ?>
                    </select>
                </div>
            </div>
            <!--Add new shopper group discount button-->
            <div class="form-group row">
                <div class="col-xs-3 col-sm-2 col-md-2 col-lg-12 ">
                    <button type="button" value="Add New Shopper Group Discount +" id="btn-addNewShopGrpDisc" class="btn btn-default">Add New Price +</button>
                </div>
            </div>
            <!--Form Submit button-->
            <div class="form-group row">
                <div class="form-group">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                        <input type="submit"  id="btn-submitAddProductForm" class="btn btn-default" />
                    </div>
                </div>
            </div>
        </section>
        <!--                        Supporting Documents ends-->
    </form>
<!--Form Container Ends here-->
<script type="text/javascript" src="../js/addprod.js"></script>
<!--<script type="text/javascript" src="../js/validateForm.js"></script>-->
</div>
<!--Form Container ends here-->
