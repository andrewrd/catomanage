<?php

include '/header.php';

?>


<!-- Form Container starts here -->
    <div class="container">
        <!-- Main content starts here -->

        <form class="form-horizontal">

            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 header">
                    <h3 class="title">Add Product</h3>



                </div>
            </div>
            <section data-scroll-index="1">
                <div class="form-group">

                    <div class="col-xs-12 col-sm-12 col-md-13 col-lg-12">
                        <h3 class="subHeader">Product Details</h3>


                    </div>
                    <!--Product Name Info-->
                    <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                        <p class="labelText">Product Name</p>

                    </div>

                    <div class="col-xs-12 col-sm-9  col-md-3 col-lg-4">
                        <input type="text" class="text-input-underline" placeholder="Product Name" id="form-input-productName">
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

                        <textarea class="form-control" id="form-input-shortDesc" rows="2" placeholder="Description Text"></textarea>
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

                        <textarea class="form-control exampleTextarea" id="form-input-longDesc" rows="4" placeholder="Description Text"></textarea>
                    </div>
                </div>
                <!--Long Description Ends-->

                <!--Add to Category Starts-->
                <div class="form-group row">
                    <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                        <p class="labelText">Add to Category</p>
                    </div>
                    <div class="col-xs-12 col-sm-9 col-md-9  col-lg-10">


                        <select multiple class="form-control" id="form-input-addToCategory">
                            <option>1. Category 1</option>
                            <option>2. Category 2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>

                    </div>
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
                        <input type="text" class="text-input-underline" placeholder="Length" id="form-length-input">
                    </div>
                    <!--Width Label-->
                    <div class="hidden-xs col-sm-1 col-md-1 col-lg-1">
                        <p class="labelText">Width</p>

                    </div>
                    <!--Width input-->
                    <div class="col-xs-12 col-sm-2  col-md-2 col-lg-2">
                        <input type="text" class="text-input-underline" placeholder="Width" id="form-input-width">
                    </div>
                    <div class="hidden-xs col-sm-1 col-md-1 col-lg-1">
                        <p class="labelText">Height:</p>

                    </div>
                    <div class="col-xs-12 col-sm-2  col-md-2 col-lg-2">
                        <input type="text" class="text-input-underline" placeholder="Height" id="form-height-input">
                    </div>

                </div>
                <div class="form-group row">
                    <!--Weight Starts Here-->
                    <div class="hidden-xs col-sm-3 col-md-2 col-lg-2">
                        <p class="labelText">Weight</p>

                    </div>
                    <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3">
                        <input type="text" class="text-input-underline" placeholder="Weight" id="form-weight-input">
                    </div>
                    <!--Weight Ends here-->
                    <!--Product SKU Starts here-->
                    <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                        <p class="labelText">Product SKU</p>

                    </div>
                    <div class="col-xs-12 col-sm-3  col-md-3 col-lg-4">
                        <input type="text" class="text-input-underline" placeholder="Product SKU" id="form-sku-input">
                    </div>
                    <!--Product SKU Ends here-->

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
                                <input type="radio" name="optradio" id="form-input-dispProd-yes">Yes</label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="optradio" id="form-input-dispProd-no">No</label>
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
                        <ul>
                            <li>Colour - Values: Red(+$5), Blue(+$4), Green(+$3) X Remove</li>
                            <li>Size - Values: Large(+$5), Medium(+$4), Small(+$3) X Remove</li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-3 col-sm-2 col-md-2 col-lg-12 ">
                        <input type="submit" value="Add New Attribute +" id="btn-addNewAttr" class="btn btn-default" />
                    </div>
                </div>
                <!--Attribute Input Starts here-->
                <!--Attribute Name-->
                <div class="form-group row">

                    <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">

                        <p class="labelText">Attribute Name</p>
                    </div>
                    <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3">
                        <input type="text" class="text-input-underline" placeholder="Attribute Name" id="form-input-attributeName">
                    </div>

                </div>
                <!--Attribute Value and Price Inputs-->
                <div class="form-group">
                    <!--Attribute Value Label and Input-->
                    <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">

                        <p class="labelText">Attribute Value</p>
                    </div>
                    <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3">
                        <input type="text" class="text-input-underline" placeholder="Attribute Value" id="form-input-attributeValue">
                    </div>

                    <!--Attribute Price Label and Input-->
                    <div class="hidden-xs col-sm-3 col-md-2 col-lg-2">

                        <p class="labelText">Attribute Price</p>
                    </div>
                    <div class="col-xs-12 col-sm-3  col-md-3 col-lg-3">
                        <input type="text" class="text-input-underline" placeholder="Attribute Price" id="form-input-attributePrice">
                    </div>
                    <!--Submit button-->
                    <div class="col-xs-3 col-sm-2 col-md-2 col-lg-12 ">
                        <input type="submit" value="Add New Value +" id="btn-addNewAttrVal" class="btn btn-default" />
                    </div>

                </div>
                <!--Price Management Section Starts here-->
                <div class="form-group">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h3 class="subHeader">Price Management</h3>

                    </div>
                </div>
                <!--Product Base Price Label and Input-->
                <div class="form-group">
                    <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                        <p class="labelText">Product Base Price</p>

                    </div>

                    <div class="col-xs-12 col-sm-9  col-md-3 col-lg-4">
                        <input type="text" class="text-input-underline" placeholder="Product Price" id="form-input-productPrice">
                    </div>
                </div>
                <!--On Special Label and Input-->
                <div class="form-group row">
                    <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                        <p class="labelText">On Special</p>

                    </div>
                    <div class="col-xs-12 col-sm-3  col-md-3 col-lg-4">
                        <div class="radio">
                            <label>
                                <input type="radio" name="optradio" id="form-input-onSpecial-yes">Yes</label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="optradio" id="form-input-dispProd-no">No</label>
                        </div>
                    </div>
                    <!--Special Discount Label and input-->
                    <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                        <p class="labelText">Special Discount</p>

                    </div>
                    <div class="col-xs-12 col-sm-9  col-md-3 col-lg-4">
                        <input type="text" class="text-input-underline" placeholder="Special Discount" id="form-input-specialDiscount">
                    </div>
                </div>
                <!--Shopper Group Special -->
                <div class="form-group row">
                    <!--Special for shopper group label-->
                    <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">

                        <p class="labelText">Special for Shopper Group</p>
                    </div>
                    <!--Shopper group selection-->
                    <div class="col-xs-12 col-sm-3 col-md-3  col-lg-4">



                        <select multiple class="form-control" id="form-input-specialShopGroup">

                            <option>1. Shopper Group 1</option>
                            <option>2. Shopper Group 2</option>
                            <option>3. Shopper Group 3</option>
                            <option>4. Shopper Group 4</option>
                            <option>5. Shopper Group 5</option>
                        </select>



                    </div>
                    <!--Group Discount Label and Input-->
                    <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                        <p class="labelText">Group Discount</p>

                    </div>
                    <div class="col-xs-12 col-sm-9  col-md-3 col-lg-4">
                        <input type="text" class="text-input-underline" placeholder="Group Discount" id="form-input-groupDiscount">
                    </div>
                </div>
                <!--Add new shopper group discount button-->
                <div class="form-group row">
                    <div class="col-xs-3 col-sm-2 col-md-2 col-lg-12 ">
                        <input type="submit" value="Add New Shopper Group Discount +" id="btn-addNewShopGrpDisc" class="btn btn-default" />
                    </div>
                </div>
                <!--Form Submit button-->
                <div class="form-group row">
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                            <input type="submit" value="Add Product" id="btn-submitAddProductForm" class="btn btn-default" />
                        </div>

                    </div>
                </div>
            </section>
            <!--                        Supporting Documents ends-->
        </form>
    <!--Form Container Ends here-->




    </div>
    <!--Form Container ends here-->


<?php

include '/footer.php';

?>
