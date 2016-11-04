<!-- Form Container starts here -->
<div class="container">
    <!-- Main content starts here -->
    <form action="addcategory.php" method="POST" class="form-horizontal" enctype="multipart/form-data">

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
                    <input name="cat_name_title" type="text" class="text-input-underline" placeholder="<?php $name = get_cat_info($dbo, $_POST['cats']); echo $name[1];?>" id="form-input-catName" oninput="checkString(this.id, 40)" required 
                    value="<?php $name = get_cat_info($dbo, $_POST['cats']); echo $name[1];?>">
                    <p id="form-error-catName">
                        <?php

                        if(isset($_POST['cat_name_error'])){
                            echo $_POST['cat_name_error'];
                        }   
                        ?>
                    </p>
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
                    <textarea name="cat_desc" class="form-control" id="form-input-desc" rows="2" placeholder="<?php $name = get_cat_info($dbo, $_POST['cats']); echo $name[2];?>" value=""required oninput="checkString(this.id, 128)"><?php $name = get_cat_info($dbo, $_POST['cats']); echo $name[2];?></textarea>
                    <p id="form-error-desc">
                    
                        <?php

                        if(isset($_POST['cat_desc_error'])){
                            echo $_POST['cat_desc_error'];
                        }   
                        ?>
                    </p>
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
                     <?php get_all_categories_editor($dbo); ?>
                    <p id="form-error-desc">

                        <?php

                        if(isset($_POST['cat_error'])){
                            echo $_POST['cat_error'];
                        }   
                        ?>
                    </p>
                </div>
                
            </div>
            <!-- Add one for child category -->

            
            <!--Long Description Ends-->
        </section>
        <section>
            <!--Packaging Information Starts-->

           
            <div class="form-group row">

                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Image URL</p>
                </div>
                <div class="col-xs-3 col-sm-3  col-md-3 col-lg-4">
                    <input name="cat_img_url" type="text" class="text-input-underline" placeholder="Insert a link here" value="<?php $name = get_cat_info($dbo, $_POST['cats']); echo $name[3];?>" id="form-url-input">
                    <p id="form-error-img">
                        <?php


                        if(isset($_POST['cat_img_error'])){
                            echo $_POST['cat_img_error'];
                        }
                        ?>


                    </p>
                    
                </div>
            </div>
            <!--Packaging Information Ends-->
            <div class="form-group row">

                <div class="hidden-xs col-sm-3 col-md-3 col-lg-2">
                    <p class="labelText">Display Command</p>
                </div>
                <div class="col-xs-3 col-sm-3  col-md-3 col-lg-4">
                    <input name="cat_disp_cmd" id="form-input-dispCmd" class="text-input-underline" maxlength="128" type="text" placeholder="DisplayCategory.php" value="DisplayCategory.php" oninput="checkFilename(this.id,128 )">
                    
                    <p id="form-error-dispCmd">
                        <?php


                        if(isset($_POST['cat_disp_cmd_error'])){
                            echo $_POST['cat_disp_cmd_error'];
                        }
                        ?>


                    </p>
                    
                </div>
            </div>


            
           
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
