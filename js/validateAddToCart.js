
function validateAddToCart() {
  /* validates dropdowns by iterating through all dropdown <select> tags
  and checking to see whether their <option> tag has a value */
    var attr = document.getElementsByClassName('productAttr');
    var len = attr.length;
    var i;
    for (i = 0; i < len; i++) {
      var name = attr[i].getAttribute("name");
      var drop = document.forms["addtocart"][name].value;
    if (drop == null || drop == "") {
      alert("Please specify " + name + ".");
      return false;
    }
  }

  /*checks to see if the quantity has been specified*/
  var quantity = document.forms["addtocart"]["quantity"].value;
    if (quantity == null || quantity == "") {
        alert("Please specify a quantity");
        return false;
    }

}

/*stores the base price of the product when the page is loaded (later used
by the updatePrice() function) */
var d = document.getElementById("productPrice"); //gets the price element
var currentPrice = d.innerHTML; //gets the price with $ prepended

function updatePrice(dropdown) {
  /* Updates the price of the product based on the attribute value selected.
  Note: the price will be calculated seperately in the shopping cart server side,
  and the purporse of this is to simply provide convenience of seeing the
  price dynamically update without a page reload */

  var basePrice = currentPrice.substr(1,currentPrice.length);
  var attr = document.getElementsByClassName('productAttr');
  var len = attr.length;
  var i;
  var newPrice = 0;
  var additionalPrices = 0;
  for (i = 0; i < len; i++) {
    var name = attr[i].getAttribute("name");
    var attrPrice = document.forms["addtocart"][name].value;
    if (attrPrice) {
      additionalPrices += parseFloat(attrPrice);
    }
  }

  newPrice = parseFloat(basePrice) + additionalPrices; //parse the base price and attribute value price
  d.innerHTML = "$" + newPrice.toFixed(2); //update the price element
}
