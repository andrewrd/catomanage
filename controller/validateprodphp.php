<?php

function validateProd(){

    //Set validated to true, all other checks upon fail will set it to false.
    //If all attributes pass these checks, submission happens
    //Else, we don't want to submit
    $validated = true;

    //Set the variables to 0 for a starting value
    $prod_name = $cat = $prod_desc = $prod_img_url = $prod_long_desc = $prod_sku = $prod_disp_cmd = 0;
    $prod_weight = $prod_l = $prod_w = $prod_h = 0;

    //If the product name is set
    if(isset($_POST['prod_name'])){
        //set the variable to the sanitised version of the string
        $prod_name = sanitise_string($_POST['prod_name']);

        //Initialise the input error message
        $prod_name_error = "";

        //If the variable is empty
        if(isEmpty($prod_name)){
            //Set the error message
            $prod_name_error = "The product name cannot be empty" ;

            //Validation hasn't passed, validate is now false
            $validated = false;
        }

        //Check if the input length is equal to or less than what is allowed in the database
        if(!isValidLength($prod_name, 40)){
            //Set the error message
            $prod_name_error = "<br>The product name that you entered was either too short or too long(max " . 40 . " characters )";
            //Validation hasn't passed, validate is now false
            $validated = false;
        }
//        If the input contains characters other than letters, numbers, spaces or dashes
        if(!isAlphanumeric($prod_name)){
            //Set error message
            $prod_name_error .= "<br>The product name that you entered included characters that weren't alphanumeric or spaces";
            //Validation hasn't passed, validate is now false
            $validated = false;
        }
        //Post the error message back to the page
        $_POST['prod_name_error'] = "<span class='errorMessage'>". $prod_name_error."</span>";
    }
    //If the post variable isn't set, validation hasn't passed
    else if(!isset($_POST['prod_name'])){
        $validated = false;
    }

    //If the product short description is set
    if(isset($_POST['prod_desc'])){
        //set the variable to the sanitised version of the string
        $prod_desc = sanitise_string($_POST['prod_desc']);
        //Initialise the input error message
        $prod_desc_error = "";

        //If the variable is empty
        if(isEmpty($prod_desc)){
            //Set the error message
            $prod_desc_error = "The product description cannot be empty";

            //Validation hasn't passed, validate is now false
            $validated = false;
        }

        //If is longer than what is allowed in the databse, it isn't valid
        if(!isValidLength($prod_desc, 128)){
            //Set the error message
            $prod_desc_error = "The product description that you entered was either too short or too long(max " . 128 . " characters )";
            //Validation hasn't passed, validate is now false
            $validated = false;
        }

        //If the input contains characters other than letters, numbers, spaces or dashes
        if(!isAlphanumeric($prod_desc)){
            //Set the error message
            $prod_desc_error = "The product name that you entered included characters that weren't alphanumeric or spaces";
            //Validation hasn't passed, validate is now false
            $validated = false;
        }
        //Post the error message back to the page
        $_POST['prod_desc_error'] = "<span class='errorMessage'>". $prod_desc_error . "</span>";
    }
    //If the variable isn't set, set validated variable to false
    else if(!isset($_POST['prod_desc'])){
        $validated = false;
    }

    //iF the product long description isn't set
    if(isset($_POST['prod_long_desc'])){
        //set the variable to the sanitised version of the string
        $prod_long_desc = sanitise_string($_POST['prod_long_desc']);
        //Initialise the input error message
        $prod_long_desc_error = "";

        //if the variable is empty
        if(isEmpty($prod_long_desc)){
            //Set the error message
            $prod_long_desc_error = "The product long description cannot be empty";
            //Validation hasn't passed, validate is now false
            $validated = false;
        }

        //If is longer than what is allowed in the databse, it isn't valid
        if(!isValidLength($prod_long_desc, 256)){
            $prod_long_desc_error = "The product description that you entered was either too short or too long(max " . 128 . " characters )";
            $validated = false;
        }
        //If the input contains characters other than letters, numbers, spaces or dashes
        if(!isAlphanumeric($prod_long_desc)){
            $prod_long_desc_error = "The product name that you entered included characters that weren't alphanumeric or spaces";
            $validated = false;
        }
        //Post the error message back to the page
        $_POST['prod_long_desc_error'] = "<span class='errorMessage'>" . $prod_long_desc_error ."</span>";
    }
    //If the variable isn't set, set the validated variable to false
    else if(!isset($_POST['prod_long_desc'])){
        $validated = false;
    }
    //If the category variable has been set
    if(isset($_POST['cat'])){
        //Set the variable to the post variable, sanistisation isn't needed
        //since categorys are inserted by admins, and sanitised/validated at that point
        $cat = $_POST['cat'];
        $cat_error = "";

        //If the post is false, validated is false
        if(empty($cat)){
            $validated = false;
            $cat_error = "You must select a category to add a product into.";
        }

        //Post the category editor back to the page
        $_POST['cat_error'] = $cat_error;
    }

    if(isset($_POST['prod_disp_cmd'])){
        $prod_disp_cmd = sanitise_string($_POST['prod_disp_cmd']);

        $prod_disp_cmd_error = "";

        if(isEmpty($prod_disp_cmd)){
            $prod_disp_cmd_error .= "<br>The display command you entered cannot be empty";
        }

        if(!isPHPFileName($prod_disp_cmd)){
            $prod_disp_cmd_error .= "<br> The display command has to be a PHP filename.";
        }
        $_POST['prod_disp_cmd_error'] = $prod_disp_cmd_error;
    }
    /*
    ISSUE: Since checkboxes are either posted or not depending on if they are clicked or not
    It is somewhat impossible to validate if it is set or not upon POST
    */
    //If the variable isn't set
    else if(!isset($_POST['cat'])){
        //Validated set to false
        $validated = false;

        //Set error and pass it to the next page
        $cat_error = "You must select a category to add a product into.";
        $_POST['cat_error'] = "<span class='errorMessage'>" . $cat_error . "</p>";
    }

    //Packaging validation checks
    //If the product length is set
    if(isset($_POST['prod_l'])){
        //Error message initialised
        $prod_l_error = "";

        //Set and sanitise the variable
        $prod_l = sanitise_number($_POST['prod_l']);

        //If the variable isn't a number
        if(!isNumber($prod_l)){
            //Set validated to false
            $validated = false;
            //Set the error message
            $prod_l_error = "This field must only contain numbers or decimals";
        }

        //Pass the error message back to the next page
        $_POST['prod_l_error'] ="<span class='errorMessage'>". $prod_l_error."</p>";
    }

    //If the POST variable isn't set, validated can't pass
    else if(!isset($_POST['prod_l'])){
        $validated = false;
    }

    //If the product width is set
    if(isset($_POST['prod_w'])){
        //Error message initialised
        $prod_w_error = "";
        //Set and sanitise the variable to that of the post variable
        $prod_w = sanitise_number($_POST['prod_w']);

        //If the variable isn't a number
        if(!isNumber($prod_w)){
            //Set validated to false
            $validated = false;
            //Set the error message
            $prod_w_error = "This field must only contain numbers or decimals";
        }
        //Post the error message back to the next page
        $_POST['prod_w_error'] = "<span class='errorMessage'>".$prod_w_error."</p>";
    }
    //If the POST variable isn't set, validated can't pass
    else if(!isset($_POST['prod_w'])){
        $validated = false;
    }

    //if the product height is set
    if(isset($_POST['prod_h'])){

        //Error message variable initialised
        $prod_h_error = "";

        //Set and sanitise the variable to that of the post variable
        $prod_h = sanitise_number($_POST['prod_h']);

        //If the variable isn't a number
        if(!isNumber($prod_h)){
            //Set validated to false
            $validated = false;

            //Set the error message
            $prod_h_error = "This field must only contain numbers or decimals";
        }

        //Post the error message back to the next page
        $_POST['prod_h_error'] = "<span class='errorMessage'>".$prod_h_error."</p>";
    }
    //If the product height isn't set, validated can't pass
    else if(!isset($_POST['prod_h'])){
        $validated = false;
    }

    //If the product weight is set
    if(isset($_POST['prod_weight'])){
        //Initialise the error message
        $prod_weight_error = "";
        //Set and sanitise the input to our variable
        $prod_weight = sanitise_number($_POST['prod_weight']);

        //Check if the input isn't a number, validation is false
        if(!isNumber($prod_weight)){
            $validated = false;
            $prod_weight_error = "This field must only contain numbers or decimals";
        }
        //Post the error message set back to the next page
        $_POST['prod_weight_error'] = "<span class='errorMessage'>".$prod_weight_error."</p>";
    }
    //If the product weight isn't set, validated is false.
    else if(!isset($_POST['prod_weight'])){
        $validated = false;
    }

    //If the product SKU is set
    if(isset($_POST['prod_sku'])){
        //Initialise the error message
        $prod_sku_error = "";
        //Set and sanitise the input into our variable
        $prod_sku = sanitise_string($_POST['prod_sku']);

        //If the input doesn't match what should be in an sku
        if(!isSKU($prod_sku)){
            //Set the validation variable to false
            $validated = false;
            //Set the error message
            $prod_sku_error = "The SKU you entered included characters that weren't alphanumeric";
        }

        //Post the error message back to the page
        $_POST['prod_sku_error'] = "<span class='errorMessage'>".$prod_sku_error."</p>";
    }
    //If the SKU isn't set, validation doesn't pass
    else if(!isset($_POST['prod_sku'])){
        $validated = false;
    }
    //If the attributes json is set
    if(isset($_POST['json'])){
        //Count the number of json attributes set, if there are none
        if(count(json_decode($_POST['json']))==0){

            //Products don't have to have attribute values, so do nothing.
        }
        //If there are attributes added
        else if(count(json_decode($_POST['json']))>0) {
            //Set our error message
            $prod_attr_error = "";

            //Decode the json into a variable
            $json = json_decode($_POST['json']);

            //For each of the variables in the json, put them into pairs of property and value
            foreach(get_object_vars($json) as $property=>$value) {

                //If the attribute name is empty
                if(sanitise_string($property)=="_empty_"){
                    //Set the error message
                    $prod_attr_error="You have to enter a attribute name";
                    //Set validated to false
                    $validated = false;
                }

                else{
                    //For each of the values in a property
                    for($i = 0; $i < sizeOf($value); $i++){
                        //Set and sanitise the input value into a variable, value
                        $valueAttr = sanitise_string($value[$i]->Value);
                        //Set and sanitise the price for the value into a variable, price
                        $price = sanitise_number($value[$i]->Price);

                        //if the value attribute is empty
                        if(isEmpty($valueAttr)){
                            //Set the validated variable to false
                            $validated = false;
                            //Set the error message
                            $prod_attr_error .= "Product attribute value cannot be empty";
                        }

                        //If the price is empty
                        if(isEmpty($price)){
                            //Set the validated variable to false
                            $validated = false;
                            //Set the error message
                            $prod_attr_error .= "Product Price cannot be empty";

                        }
                        //If the price isn't a number
                        else if(!isNumber($price)){
                            //Set validated to false
                            $validated = false;
                            //Set the error message
                            $prod_attr_error .="Product Price must be a number";
                        }
                    }
                }
                //Send the error message back as a post to the next page
                $_POST['prod_price_error'] = "<span class='errorMessage'>".$prod_attr_error. "</p>";
            }
        }
    }
    //If the attributes... Should this be here?
    else if(!isset($_POST['json'])){
        $validated = false;
    }
    //if the prices post variable is set
    if(isset($_POST['prod_prices'])){
        //Initialise the error message
        $prod_prices_error = "";

        //Decode the json in the post into our variable
        $json_input = json_decode($_POST['prod_prices']);
        if(empty($_POST['prod_prices'])){
            $validated = false;
            $prod_prices_error = "You have to enter at least one product price and shopper group for this product";

        }
        //if there is no prices entered into the prices variable
        if(count($json_input)==0){
            //Set the error message
            $prod_prices_error = "You have to enter at least one product price and shopper group for this product";
        }

        //If there are prices set
        else if(count($json_input)>0){
            //For every price that is set
            for($i = 0; $i < sizeOf($json_input); $i++){
                //Set and sanitise the input for price into our price variable
                $price = sanitise_number($json_input[$i]->Price);
                //Set and sanitise the input for the group into our Group variable
                $shopGrp = sanitise_string($json_input[$i]->Group);

                //If the Shopper group hasn't been selected
                if(isEmpty($shopGrp)){
                    //Set the error message
                    $prod_prices_error .= "A shopper group was not selected, please select a shopper group";
                    //Set validated to false.
                    $validated = false;
                }
                //If there is no price set,
                if(isEmpty($price)){
                    //Set the error message
                    $prod_prices_error .= "A product price was not entered. Please enter a product price.";
                    //Set validated to false
                    $validated = false;
                }
                if(!isNumber($price)){
                    //Set the error message
                    $prod_prices_error .= "Sorry only numbers can be entered into this field";
                    //Validated is set to false
                    $validated = false;
                }
            }
        }
        //Set the error message to a post so it can be sent to the next page
        $_POST['product_shopGrp_error'] = "<span class='errorMessage'>".$prod_prices_error."</p>";
    }
    //If prices aren't set, validation hasn't passed
    else if(!isset($_POST['prod_prices'])){

        $validated = false;
    }

    if(isset($_FILES['prod_img_url']) && $validated){
        $errors = "";
        $file_name = $_FILES['prod_img_url']['name'];
        $file_size = $_FILES['prod_img_url']['size'];
        $file_tmp = $_FILES['prod_img_url']['tmp_name'];
        $file_type = $_FILES['prod_img_url']['type'];
        $exploded = explode('.', $_FILES['prod_img_url']['name']);
        $file_ext = strtolower(end($exploded));

        $allowedExtensions = array("jpeg","jpg","png");
        if(empty($_FILES['prod_img_url']['name'])){
            $errors .="You have to add an image";
        }
        if(in_array($file_ext,$allowedExtensions)=== false){
            $errors .="<br>That extension isn't allowed, please only use JPEG, JPG or PNG";
        }
        if($file_size > 2097152){
            $errors .= "<br>File must be under 2MB";
        }
        if(empty($errors)==true){
            move_uploaded_file($file_tmp, "../img/".$file_name);
        } else{
            $_POST['prod_img_error'] = "<span class='errorMessage'>".$errors. "</span>";
            $validated = false;
        }
    }
    else if(!isset($_FILES['prod_img_url'])){
        $validated = false;
    }

    //If all the inputs have gotten to this stage without setting
    //Validated to false, the form has been validated
    return $validated;
}

//Function that unsets all post variables that were set from the form on the addprod.php page
function unsetProdForm(){
    unset($_POST['prod_name']);
    unset($_POST['prod_desc']);
    unset($_POST['prod_long_desc']);
    unset($_POST['cat']);
    unset($_POST['prod_l']);
    unset($_POST['prod_h']);
    unset($_POST['prod_w']);
    unset($_POST['prod_weight']);
    unset($_POST['prod_sku']);
    unset($_POST['prod_json']);
    unset($_POST['prod_prices']);
}

//Function to unset all the error messages that were set within validateProd() above
function unsetProdFormErrors(){
    if(isset($_POST['prod_name_error'])){
        unset($_POST['prod_name_error']);
    }

    if(isset($_POST['prod_desc_error'])){
        unset($_POST['prod_desc_error']);

    }

    if(isset($_POST['prod_long_desc_error'])){
        unset($_POST['prod_long_desc_error']);

    }
    if(isset($_POST['cat_error'])){
        unset($_POST['cat_error']);

    }
    if(isset($_POST['prod_l_error'])){
        unset($_POST['prod_l_error']);

    }
    if(isset($_POST['prod_w_error'])){
        unset($_POST['prod_w_error']);

    }
    if(isset($_POST['prod_h_error'])){
        unset($_POST['prod_h_error']);

    }
    if(isset($_POST['prod_weight_error'])){
        unset($_POST['prod_weight_error']);
    }

    if(isset($_POST['prod_sku_error'])){
        unset($_POST['prod_sku_error']);

    }
    if(isset($_POST['prod_price_error'])){
        unset($_POST['prod_price_error']);

    }
    if(isset($_POST['prod_shpGrp_error'])){
        unset($_POST['prod_shopGrp_error']);

    }
}


?>
