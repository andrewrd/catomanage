/*Helper Functions*/

//Helper function to check for the length of a string
function checkLength(str, minLim, maxLim){
    length =  str.length;
    if(length > minLim && length <= maxLim){
        return true;
    }
    return false;
}

function isEmpty(str){
    return str.length == 0;
}

function isAlphanumeric(str){
    return str.match(/^[\w\-\s]+$/);
}

function isSKU(str){
    return str.match(/^[a-zA-Z0-9-]+$/)
}

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
Error fields must be named exactly how the input fields are 
but with error instead of input
*/
function checkString(elemId,len){
    var correctInput = true;
    
    var nameElement = document.getElementById(elemId);
    var errorElement = document.getElementById(elemId.replace("input", "error"));
    var currentVal = nameElement.value;
    var errorMessage = "";
    if(!checkLength(currentVal, 0, len)){
        correctInput = false;
        errorMessage += "The length of this field is either too short or too long."; 
    }
    if(isEmpty(currentVal)){
        correctInput = false;
        if(errorMessage.length > 0){
            errorMessage += "<br>";
        }
        errorMessage += "This field cannot be empty."; 
    }
    if(!isAlphanumeric(currentVal)){
        correctInput = false;
        if(errorMessage.length > 0){
            errorMessage += "<br>";
        }
        errorMessage += "This field can only contain numbers and letters."; 
    }
    if(!correctInput){
        changeColour(nameElement, nameElement.tagName, "red");
        
        errorElement.innerHTML = errorMessage;
    }
    else{
        changeColour(nameElement, nameElement.tagName, "green");
        errorElement.innerHTML = "";
    }
    
}

function checkNumber(elemId){
    var nameElement = document.getElementById(elemId);
    var currentVal = nameElement.value;
    var errorElement = document.getElementById(elemId.replace("input", "error"));
    var errorMessage = "";
    var correctInput = true;
    
    //check that the input holds a value
    correctInput = nameElement.value.length > 0;
    if(!correctInput){
        errorMessage = "You didn't enter a value, please enter a value";
    }
    
    if(correctInput){
        //Cast the value to a Number
        var intValue = Number(nameElement.value);
        
        //if the value is not a number, correct input is false
        if(!isNumber(nameElement.value)){
            correctInput = false;
            errorMessage += "The value you entered isn't a number."
        }

    }
    if(!correctInput){
        changeColour(nameElement, nameElement.tagName, "red");
        errorElement.innerHTML = errorMessage;
        
        
    }
    else if(correctInput){
        changeColour(nameElement, nameElement.tagName, "green");
        errorElement.innerHTML = "";
    }
    
}

function checkSKU(elemId){
    var nameElement = document.getElementById(elemId);
    var currentVal = nameElement.value;
    var errorElement = document.getElementById(elemId.replace("input", "error"));
    var errorMessage = "";
    var correctInput = true;

    //check that the input holds a value
    correctInput = currentVal.length > 0;
    if(!correctInput){
        errorMessage = "You didn't enter a value, please enter a value";
    }

    if(correctInput){
        //Cast the value to a Number

        //if the value is not a number, correct input is false
        if(!isSKU(currentVal)){
            correctInput = false;
            errorMessage += "The value you entered isn't an SKU."
        }
    }
    if(!correctInput){
        changeColour(nameElement, nameElement.tagName, "red");
        errorElement.innerHTML = errorMessage;


    }
    else if(correctInput){
        changeColour(nameElement, nameElement.tagName, "green");
        errorElement.innerHTML = "";
    }

}



