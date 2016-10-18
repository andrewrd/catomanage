<!--this form deals with adding new product information to the database-->
<h3 class="text-center">Add New Product</h3>
<h4>Product Details</h4>
<form>
  <div class="form-group">
    <label for="new-prod-name">Product Name</label>
    <input id="new-prod-name" class="form-control" name="prod_name">
  </div>
  <div class="form-group">
    <label for="new-short-desc">Short Description (Max 128 Characters)</label>
    <textarea id="new-short-desce" class="form-control" name="prod_desc" rows="2"></textarea>
  </div>
  <div class="form-group">
    <label for="new-long-desc">Long Description (Max 256 Characters)</label>
    <textarea id="new-long-desce" class="form-control" name="prod_long_desc" rows="3"></textarea>
  </div>
  <button type="submitt" class="btn btn-default">Submit</button>
</form>
