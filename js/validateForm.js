/*Helper Functions*/

//Helper function to check for the length of a string
function checkLength(str, minLim, maxLim){
    length =  str.length;
    if(length > minLim && length <= maxLim){
        return true;
    }
    return false;
}
//Function to check if  a string is empty
function isEmpty(str){
    return str.length == 0;
}
/*
Function that checks whether a string is alphanumeric, plus some special characters
that are needed for inputting data, like commas, periods, apostrophes, &#; for alt codes  
*/
function isAlphanumeric(str){
    return str.match(/^[\w\-\s&#;'",.]+$/);
}

/*Function that checks whether a string is in the form of a php filename,
accepts characters, numbers and dashes, but only if they end in .php.*/
function isFilename(str){
    return str.match(/^[a-zA-Z0-9-]+\.php$/);
}
/*Function that checks whether or not a string contains characters that 
are allowed in an SKU, such as all alphabetical characters, numbers, dashes*/
function isSKU(str){
    return str.match(/^[a-zA-Z0-9-]+$/)
}
/*Function that checks whether or not a string is in the form of a number
allows both integers and decimals*/
function isNumber(str){
    return str.match(/^(\d*\.)?\d+$/);
}

/*
Function to change the colour of input fields.
Parameters:
ele - the element id of the field to change
eleType - the type of field that is being changed, i.e INPUT is normal but has a different style to TEXTAREA
colour - the colour that that the field will turn into
*/
function changeColour(ele,eleType,colour){
    if(eleType ==="INPUT"){
        ele.style.borderBottomColor = colour;
    }
    else if(eleType === "TEXTAREA"){
        ele.style.borderColor = colour;
    }
}

/*Composite Functions*/

/*
    Function that checks whether or not a string can fulfill our checks.
    Inputs and error divs must be named exactly apart from having input and error changed
    to describe what they are. 
    E.g. input field - form-input-name
         error field - form-error-name
*/
function checkString(elemId,len){
    /*Acts as a boolean value throughout the code, will get turned off 
    if our tests fail*/
    var correctInput = true;
    
    /*Stores the element that we are checking into a variable*/
    var nameElement = document.getElementById(elemId);
    
    /*Since we are naming things correctly, 
    grab the error field id by replacing input with error*/
    var errorElement = document.getElementById(elemId.replace("input", "error"));
    
    /*Get the value of the element that we are testing*/
    var currentVal = nameElement.value;
    /*Intialise our error message variable*/
    var errorMessage = "";
    
    /*Check the length of our string*/
    if(!checkLength(currentVal, 0, len)){
        /*If not set our boolean to false*/
        correctInput = false;
        /*Set the error message*/
        errorMessage = "The length of this field is either too short(minimum 0) or too long("+len+" character max length)."; 
    }
    /*Check whether or not the field is empty*/
    if(isEmpty(currentVal)){
        /*If its empty, set boolean to false*/
        correctInput = false;
        /*If the error message already has something in it, add a break for formatting*/
        if(errorMessage.length > 0){
            errorMessage += "<br>";
        }
        /*Set and concatenate our error message*/
        errorMessage += "This field cannot be empty."; 
    }
    /*If the input doesn't pass our alphanumeric test defined above,*/
    if(!isAlphanumeric(currentVal)){
        /*Set boolean to false*/
        correctInput = false;
        
        /*If the error message already has something in it, add a break for formatting*/
        if(errorMessage.length > 0){
            errorMessage += "<br>";
        }
        /*Set and concatenate our error message*/
        errorMessage += "This field can only contain numbers,letters, ampersand, hash, comma, apostrophe, quotation mark and period"; 
    }
    
    /*If the input isn't the result we want*/
    if(!correctInput){
        /*Change the input field to red*/
        changeColour(nameElement, nameElement.tagName, "red");
        /*Put our error message into the element thats defined*/
        errorElement.innerHTML = errorMessage;
    }
    
    /*If not*/
    else{
        /*Change the colour to green since its correct*/
        changeColour(nameElement, nameElement.tagName, "green");
        /*Destroy any error message that was in there before*/
        errorElement.innerHTML = "";
    }
    
}

function checkNumber(elemId){
    /*Stores the element that we are checking into a variable*/
    var nameElement = document.getElementById(elemId);
    /*Get the value of the element that we are testing*/
    var currentVal = nameElement.value;
    /*Since we are naming things correctly, 
    grab the error field id by replacing input with error*/
    var errorElement = document.getElementById(elemId.replace("input", "error"));
    /*Get the value of the element that we are testing*/
    var errorMessage = "";
    /*Acts as a boolean value throughout the code, will get turned off 
    if our tests fail*/
    var correctInput = true;
    
    //check that the input holds a value
    correctInput = nameElement.value.length > 0;
    if(!correctInput){
        errorMessage = "You didn't enter a value, please enter a value";
    }
    /*If the value is currently correct*/
    if(correctInput){        
        //check if the value is a number
        if(!isNumber(nameElement.value)){
            /*If it isn't, set the correct input boolean to false
            and concatenate our error message*/
            correctInput = false;
            errorMessage += "The value you entered isn't a number."
        }
    }
    /*If the value isn't a correct input*/
    if(!correctInput){
        /*change the colour of the input field to red*/
        changeColour(nameElement, nameElement.tagName, "red");
        errorElement.innerHTML = errorMessage;
    }
    /*If the value is a correct input*/
    else if(correctInput){
        /*Set the field to green, and destroy our error message*/
        changeColour(nameElement, nameElement.tagName, "green");
        errorElement.innerHTML = "";
    }
    
}

function checkSKU(elemId){
    /*Stores the element that we are checking into a variable*/
    var nameElement = document.getElementById(elemId);
    /*Get the value of the element that we are testing*/
    var currentVal = nameElement.value;
    /*Since we are naming things correctly, 
    grab the error field id by replacing input with error*/
    var errorElement = document.getElementById(elemId.replace("input", "error"));
    /*Get the value of the element that we are testing*/
    var errorMessage = "";
    /*Acts as a boolean value throughout the code, will get turned off 
    if our tests fail*/
    var correctInput = true;

    //check that the input holds a value
    correctInput = currentVal.length > 0;
    if(!correctInput){
        errorMessage = "You didn't enter a value, please enter a value";
    }

    if(correctInput){
        //if the value passes our SKU check 
        if(!isSKU(currentVal)){
            /*If not, set the boolean to false, and concatenate our error message*/
            correctInput = false;
            errorMessage += "The value you entered isn't an SKU."
        }
    }
    /*If the value isn't correct, and didn't pass our checks*/
    if(!correctInput){
        /*Set the colour of the input field to red*/
        changeColour(nameElement, nameElement.tagName, "red");
        errorElement.innerHTML = errorMessage;
    }
    /*If the value does pass our checks*/
    else if(correctInput){
        /*Change the colour of the input field to green*/
        changeColour(nameElement, nameElement.tagName, "green");
        /*Destroy any error message that might exist*/
        errorElement.innerHTML = "";
    }

}

function checkFilename(elemId,len){
    /*Acts as a boolean value throughout the code, will get turned off 
    if our tests fail*/
    var correctInput = true;
    
    /*Stores the element that we are checking into a variable*/
    var nameElement = document.getElementById(elemId);
    
    /*Since we are naming things correctly, 
    grab the error field id by replacing input with error*/
    var errorElement = document.getElementById(elemId.replace("input", "error"));
    
    /*Get the value of the element that we are testing*/
    var currentVal = nameElement.value;
    
    /*Get the value of the element that we are testing*/
    var errorMessage = "";
    
    /*Check whether or not the field is empty*/
    if(!checkLength(currentVal, 0, len)){
        /*If it isn't set our boolean to false*/
        correctInput = false;
        /*Set our error message*/
        errorMessage = "The length of this field is either too short or too long."; 
    }
    /*Check if the field is empty or not*/
    if(isEmpty(currentVal)){
        /*If it is, set our boolean to flase*/
        correctInput = false;
        /*If the error messaage has a value in it already, add a break*/
        if(errorMessage.length > 0){
            errorMessage += "<br>";
        }
        /*Set our error message*/
        errorMessage += "This field cannot be empty."; 
    }
    /*Check if the value can complete our filename check*/
    if(!isFilename(currentVal)){
        /*Set our boolean to false*/
        correctInput = false;
        /*If the error message has a value in it, add a break*/
        if(errorMessage.length > 0){
            errorMessage += "<br>";
        }
        /*Concatenate our error message*/
        errorMessage += "The display command has to be a PHP filename."; 
    }
    /*If the input wasn't correct*/
    if(!correctInput){
        /* change the input field to red*/
        changeColour(nameElement, nameElement.tagName, "red");
        /*Set the error message*/
        errorElement.innerHTML = errorMessage;
    }
    /*If the value is correct*/
    else if(correctInput){
        /*Change the colour to green*/
        changeColour(nameElement, nameElement.tagName, "green");
        /*Destroy any error message in there currently*/
        errorElement.innerHTML = "";
    }

}





